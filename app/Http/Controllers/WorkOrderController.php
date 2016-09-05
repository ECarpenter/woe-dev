<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $newWorkOrder->status = "Open";
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
        $properties = Property::all();
        $tenants = Tenant::all();


        return view('wo.viewlist',compact('workorders','properties','tenants'));
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

        $workorder->problem_id = $request->type;
        $workorder->status = $request->status;
        $workorder->description = $request->description;
        $workorder->save();

        
        return redirect()->action('WorkOrderController@show', [$workorder->id]);
    }

    public function sendNoticeEmail(WorkOrder $workorder)
    {
        $emails = array();

        foreach ($workorder->Managers() as $manager) {

            $emails[] = $manager->email;
        }

        $file = null;
        $mime = null;
        if ($workorder->support_file != null)
        {
            $file = Helper::getS3URL(SUPPORT_PATH.$workorder->support_file);
        }
        
        
        if (!empty($emails)) {
            Log::info('Work Order notification e-mail sent to',[$emails]);
            Mail::queue('email.notice',compact('workorder'), function ($message) use ($emails, $workorder, $file, $mime) {
                $message->from('davispartners@ejcustom.com', 'Notice');
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
        $workorder->cos_filename = 'files/cos/cos-'.$date.'.pdf';
        $workorder->tenant_invoice_filename = 'files/tenant_invoices/tenant-'.$date.'.pdf';
        $workorder->billed = true;
        $workorder->status = 'Done';
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
        $cpdf->save($workorder->cos_filename);


        $tpdf = PDF::loadView('pdf.invoice', compact('workorder'));
        $tpdf->save($workorder->tenant_invoice_filename);

        //WorkOrderController::sendbillingEmail($workorder);

        return response()->json($workorder);
    }

    public function upload(WorkOrder $workorder, Request $request)
    {
        $this->validate($request, [ 'vendorinvoice' => 'required|mimes:pdf'
            ]);
        $fname = 'vendor-'.date('ymd-His', strtotime(\Carbon\Carbon::now(\Auth::user()->timezone))).'.pdf';
        $workorder->vendor_invoice_filename = 'files/vendor_invoices/'.$fname;
        $workorder->save();
        $file = $request->vendorinvoice;
        $file->move('files/vendor_invoices/', $fname);


        return back();

    }

    public function sendbillingEmail(WorkOrder $workorder)
    {
        $managers = $workorder->Managers();
        $manageremail = $managers[0]->email;
        $ar_file = $workorder->cos_filename;

        if ($workorder->vendor_invoice_filename != null)
        {
            $ar_file = 'tmp\AR.pdf';
            $ap_file = 'tmp\AP.pdf';

            $ap = new \LynX39\LaraPdfMerger\PdfManage;
            $ap->addPDF($workorder->vendor_invoice_filename, 'all');
            $ap->addPDF($workorder->cos_filename, 'all');
            $ap->merge('file', $ap_file, 'P');          

            Mail::queue('email.accounting',compact('workorder'), function ($message) use ($manageremail, $ap_file) {
                $message->from($manageremail, 'PM');
                $message->subject('AP Invoice');
                $message->attach($ap_file);
                $message->to('ecarpen905@example.com');
            });

            
            $ar = new \LynX39\LaraPdfMerger\PdfManage;
            $ar->addPDF($workorder->cos_filename, 'all');
            $ar->addPDF($workorder->vendor_invoice_filename, 'all');
            $ar->merge('file', $ar_file,'P'); 

        }

           

        Mail::queue('email.tenantbill',compact('workorder'), function ($message) use ($manageremail, $workorder) {
            $message->from($manageremail, 'PM');
            $message->subject('Tenant Invoice');
            $message->attach($workorder->tenant_invoice_filename, ['as' => 'Invoice.pdf']);
            $message->to('ecarpen905@gmail.com');
        });

        Mail::queue('email.accounting',compact('workorder'), function ($message) use ($manageremail, $ar_file) {
            $message->from($manageremail, 'PM');
            $message->subject('COS');
            $message->attach($ar_file, ['as' => 'COS.pdf']);
            $message->to('ecarpen905@gmail.com');
        });   
        
    }

}


