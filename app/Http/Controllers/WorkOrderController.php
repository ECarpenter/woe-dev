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

use App\Http\Requests;
use App\ProblemType;
use App\WorkOrder;
use App\Tenant;
use App\Property;

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
    	

        $tenants = Tenant::all();

    	 
        return view('wo.submit', compact('problemTypes','tenants'));
    }
    

    public function save(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|min:10',
            'tenant' => 'required',
            'type' => 'required',
            ]);

        $newWorkOrder = new WorkOrder;
        $newWorkOrder->description = $request->description;
        $newWorkOrder->status = "Open";
        $newWorkOrder->problem_id = $request->type;
        
        
        $newWorkOrder->tenant_id = $request->tenant;
        

        $newWorkOrder->save();

        WorkOrderController::sendNoticeEmail($newWorkOrder);

        return view('thankyou');
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

        foreach ($workorder->Tenant->Property->Managers() as $manager) {

            $emails[] = $manager->email;
        }

        if (!empty($emails)) {
            Mail::queue('email.notice',compact('workorder'), function ($message) use ($emails) {
                $message->from('davispartners@ejcustom.com', 'Notice');
                $message->subject('New Work Order');
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
        $workorder->amount_billed =15;
        $workorder->billing_description = $request->billing_description;
        $workorder->job_cost = $request->job_cost;
        $workorder->cos_filename = 'files/cos/cos-'.$date.'.pdf';
        $workorder->tenant_invoice_filename = 'files/tenant_invoices/tenant-'.$date.'.pdf';
        $workorder->billed = true;
        $workorder->status = 'Done';
        $workorder->save();
        

        $cpdf = PDF::loadView('pdf.cos', compact('workorder'));
        $cpdf->save($workorder->cos_filename);

        $tpdf = PDF::loadView('pdf.invoice', compact('workorder'));
        $tpdf->save($workorder->tenant_invoice_filename);

        WorkOrderController::sendbillingEmail($workorder);

        return Response::json($workorder);
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
        Mail::queue('email.accounting',compact('workorder'), function ($message) use ($manageremail, $ar_file) {
            $message->from($manageremail, 'PM');
            $message->subject('COS');
            $message->attach($ar_file);
            $message->to('ecarpen905@gmail.com');
        });      

        Mail::queue('email.tenantbill',compact('workorder'), function ($message) use ($manageremail, $workorder) {
            $message->from($manageremail, 'PM');
            $message->subject('Tenant Invoice');
            $message->attach($workorder->tenant_invoice_filename, ['as' => 'Invoice.pdf']);
            $message->to('ecarpen905@gmail.com');
        });
        
    }

}


