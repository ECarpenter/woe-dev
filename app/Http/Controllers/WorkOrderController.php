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
        
        if (Auth::user()->hasRole('tenant'))
        {
            $newWorkOrder->tenant_id = Auth::user()->Tenants()->first()->id;

        }
        else
        {
            $newWorkOrder->tenant_id = $request->tenant;
        }

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

        foreach ($workorder->Manager() as $manager) {

            $emails[] = $manager->email;
        }

        if (!empty($emails))
                {
            Mail::queue('email.notice',compact('workorder'), function ($message) use ($emails) {
                $message->from('us@example.com', 'New Work Order');

                $message->to($emails)->cc('bar@example.com');
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
        $workorder->save();
        

        $cpdf = PDF::loadView('pdf.cos', compact('workorder'));
        $cpdf->save($workorder->cos_filename);

        $tpdf = PDF::loadView('pdf.invoice', compact('workorder'));
        $tpdf->save($workorder->tenant_invoice_filename);

        // $pdfmerge = new \LynX39\LaraPdfMerger\PdfManage;
        // $pdfmerge->addPDF('invoice.pdf', 'all');
        // $pdfmerge->addPDF('invoice2.pdf', 'all');
        // $pdfmerge->merge('file', 'TEST2.pdf', 'P');          

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

}


