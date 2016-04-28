<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Mail;
use App;
use PDF;
use PdfMerger;

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
        $emails = array();

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

        $pdf = PDF::loadView('pdf.invoice');
        $pdf->save('invoice2.pdf');

        $pdfmerge = new \LynX39\LaraPdfMerger\PdfManage;
        $pdfmerge->addPDF('invoice.pdf', 'all');
        $pdfmerge->addPDF('invoice2.pdf', 'all');
        $pdfmerge->merge('file', 'TEST2.pdf', 'P');    

        return redirect()->action('WorkOrderController@show', [$workorder->id]);
    }

    public function sendNoticeEmail(WorkOrder $workorder)
    {
        foreach ($workorder->Manager() as $manager) {

            $emails[] = $manager->email;
        }

        Mail::queue('email.notice',compact('workorder'), function ($message) use ($emails) {
             $message->from('us@example.com', 'New Work Order');

             $message->to($emails)->cc('bar@example.com');
        });


    }

}


