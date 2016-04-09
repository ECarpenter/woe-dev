<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;

use App\Http\Requests;
use App\ProblemType;
use App\WorkOrder;
use App\Tenant;

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
            'description' => 'required|min:10'
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


        return view('thankyou');
    }
}


