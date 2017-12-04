<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use DB;
use Auth;
use Mail;
use App;
use PDF;
use PdfMerger;
use Response;
use Log;
use Excel;
use Storage;
use Config;

use App\Http\Requests;
use App\Helpers\Helper;
use App\ProblemType;
use App\WorkOrder;
use App\Tenant;
use App\Property;
use App\User;
use App\Owner;
use App\ChargeCode;
use App\Post;

class WorkOrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function submit()
    {
    	$problemTypes = ProblemType::all();
    	
        $tenants = Tenant::where('active', true)->orderBy('company_name')->get();
        
        $tenants = Helper::checkPermissions($tenants);

        
        return view('wo.submit', compact('problemTypes','tenants'));
    }
    

    public function save(Request $request)
    {

        $this->validate($request, [
            'description' => 'required|min:10',
            'property' => 'required',
            'type' => 'required',
            'support_file' => 'mimes:jpeg,bmp,png,zip,mp3,avi,mpeg,mp4,mpg,gif,pdf,m4a',
            'phone' => 'required'
            ]);
        $newWorkOrder = new WorkOrder;
        $newWorkOrder->description = $request->description;
        $newWorkOrder->status = "Submited";
        $newWorkOrder->problem_id = $request->type;
        $newWorkOrder->tenant_id = $request->tenant;

        $user = Auth::user();
        $newWorkOrder->user_id = $user->id;
        if ($request->phone != 'NONE')
        {
            $user->phone = $request->phone;
            $user->save();
        }
        
        if ($request->urgent == 'Y') {
            $newWorkOrder->urgent = true;
        }
        
        $file = $request->file('support_file');
        if ($file != null){
            $fname = date('ymd-His', strtotime(\Carbon\Carbon::now())).'-'.$request->file('support_file')->getClientOriginalName();

            Storage::put(SUPPORT_PATH.$fname, file_get_contents($file));
            $newWorkOrder->support_file = $fname;
        }

        $newWorkOrder->save();
        if (\Auth::user()->hasRole('tenant'))
        {
            WorkOrderController::sendNoticeEmail($newWorkOrder);
            return view('thankyou');
        }
        else
        {
            return redirect('/home');
        }
    }

    public function viewlist()
    {
        $workorders = WorkOrder::all();
        $workorders = $workorders->keyBy('id');

        foreach ($workorders as $workorder)
        {
            if(!$workorder->property()->canAccess())
            {
                $workorders->forget($workorder->id);
            }
        }


        return view('wo.viewlist',compact('workorders'));
    }

    public function show(WorkOrder $workorder)
    {

        $workorder->load('tenant.property','problemtype');

        return view('wo.show', compact('workorder'));
    }

    public function showtenant(WorkOrder $workorder)
    {

        $workorder->load('tenant.property','problemtype');

        return view('wo.showtenant', compact('workorder'));
    }

    public function edit(WorkOrder $workorder)
    {
        $workorder->load('tenant.property','problemtype');
        $typeList = ProblemType::all();

        return view('wo.edit', compact('workorder','typeList'));
    }

    public function update(Request $request, WorkOrder $workorder)
    {
        if ($request->update == "submit")
        {
            $workorder->problem_id = $request->type;
            $workorder->status = $request->status;
            $workorder->save();

            Session::flash('success', 'Your Changes have been saved!');
        }
        else
        {
            Session::flash('warning', 'Your changes have been discarded');
        }

        
        return redirect()->action('WorkOrderController@show', [$workorder->id]);
    }

    public function post(Request $request, WorkOrder $workorder)
    {
        if ($request->post == 'submit')
        {
            if ($request->post_message != null)
            {
                $post = new Post;
                $post->user_id = Auth::user()->id;
                $post->message = $request->post_message;
                $post->work_order_id = $workorder->id;
                $post->save();

                $emails = array();

                if (Auth::user()->hasRole('tenant'))
                {
                    foreach ($workorder->Managers() as $manager) 
                    {
                        $emails[] = $manager->email;
                    }
                }
                else
                {
                    $emails[] = $workorder->User->email;
                }

                Helper::sendPost($post, $workorder, $emails);

                Session::flash('success', 'Your message was sent!');
            }
            else 
            {
                Session::flash('danger', 'Your message was blank');
            }
            
        }
        else 
        {
            Session::flash('warning', 'Your message has been deleted');
        }

        return back();
    }

    public function sendNoticeEmail(WorkOrder $workorder)
    {
        $emails = array();

        foreach ($workorder->Managers() as $manager) {

            $emails[] = $manager->email;
        }

        $file = null;
        if ($workorder->support_file != null)
        {
            $file = Helper::getS3URL(SUPPORT_PATH.$workorder->support_file);
        }
        
        
        if (!empty($emails)) {
            Log::info('Work Order notification e-mail sent to',[$emails]);
            Mail::queue('email.notice',compact('workorder'), function ($message) use ($emails, $workorder, $file) {
                $message->from('Do-Not-Reply@ejcustom.com', 'Notice');
                $message->subject($workorder->Property()->name.' - New Work Order');
                if ($file != null)
                {
                    $message->attach($file, ['as' => substr($workorder->support_file, 14)]);
                }
                $message->to($emails);
            });
        }

    }

    public function bill(WorkOrder $workorder)
    {
        return Response::json($workorder);
    }

    public function processbill(WorkOrder $workorder, Request $request)
    {

        
        
        $date=date('ymd-His', strtotime(\Carbon\Carbon::now(\Auth::user()->timezone)));
        $workorder->amount_billed =$request->amount_billed;
        $workorder->billing_description = $request->billing_description;
        $workorder->job_cost = $request->job_cost;
        $workorder->cos_filename = 'cos-'.$date.'.pdf';
        $workorder->tenant_invoice_filename = 'tenant-'.$date.'.pdf';
        $workorder->billed = true;
        $workorder->status = 'Closed';
        $workorder->invoice_number = $workorder->Tenant->tenant_system_id.'-'.date('ymdH', strtotime(\Carbon\Carbon::now(\Auth::user()->timezone)));
        $workorder->save();
        
        if ($request->job_cost > 0)
        {
            $chargecode = $workorder->Tenant->Property->Owner->ChargeCodes()->where('name', '=', 'Tenant Billback')->first();
        }
        else
        {
            $chargecode = $workorder->Tenant->Property->Owner->ChargeCodes()->where('name', '=', 'Misc/Other')->first();
        }

        $cpdf = PDF::loadView('pdf.cos', compact('workorder','chargecode'));
        Storage::put(COS_PATH.$workorder->cos_filename, $cpdf->output());


        $tpdf = PDF::loadView('pdf.invoice', compact('workorder'));
        Storage::put(TENANT_INVOICE_PATH.$workorder->tenant_invoice_filename, $tpdf->output());

        WorkOrderController::sendbillingEmail($workorder);

        return response()->json($workorder);
    }

    public function upload(WorkOrder $workorder, Request $request)
    {
        $this->validate($request, [ 'vendorinvoice' => 'required|mimes:pdf'
            ]);
        $fname = 'vendor-'.date('ymd-His', strtotime(\Carbon\Carbon::now(\Auth::user()->timezone))).'.pdf';
        $workorder->vendor_invoice_filename = $fname;
        $workorder->save();
        $file = $request->vendorinvoice;
        Storage::put(VENDOR_INVOICE_PATH.$fname, file_get_contents($file));


        return back();

    }

    public function sendbillingEmail(WorkOrder $workorder)
    {
       
        $useremail = $workorder->User->email;

        $cos = Helper::getS3URL(COS_PATH.$workorder->cos_filename);
        $ar_file = $cos;
        if ($workorder->vendor_invoice_filename != null)
        {

            
            $vendor_invoice = Helper::getS3URL(VENDOR_INVOICE_PATH.$workorder->vendor_invoice_filename);

            $ar_file = 'tmp\AR.pdf';
            $ap_file = 'tmp\AP.pdf';

            Log::info($cos);
            Log::info($vendor_invoice);

            $ap = new \LynX39\LaraPdfMerger\PdfManage;
            $ap->addPDF($vendor_invoice, 'all');
            $ap->addPDF($cos, 'all');
            $ap->merge('file', $ap_file, 'P');          

            Mail::queue('email.accounting',compact('workorder'), function ($message) use ($ap_file) {
                $message->from(Auth::user()->email, Auth::user()->name);
                $message->subject('AP Invoice');
                $message->attach($ap_file);
                $message->to(AP_EMAIL);
            });

            
            $ar = new \LynX39\LaraPdfMerger\PdfManage;
            $ar->addPDF($cos, 'all');
            $ar->addPDF($vendor_invoice, 'all');
            $ar->merge('file', $ar_file,'P'); 

        }

           

        Mail::queue('email.tenantbill',compact('workorder'), function ($message) use ($workorder, $useremail) {
            $message->from(Auth::user()->email, Auth::user()->name);
            $message->subject('Tenant Invoice');
            $message->attach(Helper::getS3URL(TENANT_INVOICE_PATH.$workorder->tenant_invoice_filename), ['as' => 'Invoice.pdf']);
            $message->to($useremail);
        });

        Mail::queue('email.accounting',compact('workorder'), function ($message) use ($ar_file) {
            $message->from(Auth::user()->email, Auth::user()->name);
            $message->subject('COS');
            $message->attach($ar_file, ['as' => 'COS.pdf']);
            $message->to(AR_EMAIL);
        });   
        
    }

}


