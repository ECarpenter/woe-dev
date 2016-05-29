<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Response;

use App\Http\Requests;
use App\Helpers\Helper;
use App\Tenant;
use App\User;
use App\Insurance;


class TenantController extends Controller
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

    public function add()
    {
    	return view('tenant.add');
    }

    public function save(Request $request)
    {
    	$user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'timezone' => "America/Los_Angeles",
            'job_title' => $request->job_title,
            'verified' =>true
        ]);

        $role = DB::table('roles')->where('name', '=', 'tenant')->pluck('id');
        $user->Roles()->attach($role);


        $tenant = new Tenant;
        $tenant->user_id = $user->id;
        $tenant->unit = $request->suite;
        $tenant->property_id = $request->property;
        $tenant->company_name = $request->company_name;
        $tenant->tenant_system_id = $request->tenant_system_id;
        $tenant->save();

        $ins = new Insurance;
        $ins->tenant_id = $tenant->id;
        $ins->save();
        return redirect('/property/'.$tenant->property_id);
    }

    public function viewid(Request $request)
    {
        $tenant = Tenant::where('tenant_system_id',$request->tenant_system_id)->first();
        
            if ($tenant != null) {
                       
            return TenantController::show($tenant);
        }
        else {
            return TenantController::tenantlist();
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('workorder', 'workorder.problemtype','user');

        $state = TenantController::insuranceCheck($tenant);


        return view('tenant.show', compact('tenant','state'));
    }

    public function tenantlist()
    {
        $tenants = Tenant::orderByRaw('company_name COLLATE NOCASE')->get();

        return view('tenant.viewlist',compact('tenants'));
    }

    public function upload(Tenant $tenant, Request $request)
    {

        $this->validate($request, [ 'insurance_cert' => 'required|mimes:pdf'
            ]);
        
        $used = false;

        $fname = 'ins-'.date('ymd-His', strtotime(\Carbon\Carbon::now(\Auth::user()->timezone))).'.pdf';

        $ins = $tenant->Insurance;


        // Associate the file name of the insurance certificate 
        // with the various possible insurance types.
        // first determines file type
        
        if ($request->typeSelect == 'certificate') {
            if ($request->liability == 'Y') {
                $ins->liability_filename = 'files/insurance/'.$fname;
                $used = true;
            }
            if ($request->auto == 'Y') {
                $ins->auto_filename = 'files/insurance/'.$fname;
                $used = true;
            }
            if ($request->workerscomp == 'Y') {
                $ins->workerscomp_filename = 'files/insurance/'.$fname;
                $used = true;
            }
            if ($request->umbrella == 'Y') {
                $ins->umbrella_filename = 'files/insurance/'.$fname;
                $used = true;
            }
        }
        elseif ($request->typeSelect == 'endorsement'){
            $ins->endorsement_filename = 'files/insurance/'.$fname;
            $used = true;
        }

        if ($used) {
            $ins->save();
            $file = $request->insurance_cert;
            $file->move('files/insurance/', $fname);
        }


        return back();

    }

    public function edit(Tenant $tenant)
    {
        $tenant->load('user');
        return Response::json($tenant);
    }

    public function update(Tenant $tenant, Request $request)
    {

        $tenant->tenant_system_id = $request->tenant_system_id;
        $tenant->unit = $request->unit;
        $tenant->company_name = $request->company_name;
        $tenant->active = $request->active_switch;
        $tenant->User()->verified = $request->verified_switch;
        $tenant->save();

        return redirect('/tenant/'.$tenant->id);
    }


    //Check for insurance compliance
    //each key gets either success or danger depending on situation
    public function insuranceCheck(Tenant $tenant)
    {
        
        $state = array(
            "lfile" => "danger",
            "ufile" => "danger",
            "afile" => "danger",
            "wfile" => "danger",
            "efile" => "danger",
            "lexpire" => "danger",
            "uexpire" => "danger",
            "aexpire" => "danger",
            "wexpire" => "danger",
            "llimit" => "danger",
            "ulimit" => "danger",
            "alimit" => "danger",
            "wlimit" => "danger",
            "elink" => "",
            "llink" => "",
            "ulink" => "",
            "alink" => "",
            "wlink" => "",
            );

        $today = date("Y-m-d");
        
        if ($tenant->Insurance->endorsement_filename != null) {
            $state["efile"] = "success";
            $state["elink"] = "window.open('/".$tenant->Insurance->endorsement_filename."')";
        } 
        if ($tenant->Insurance->liability_filename != null) {
            $state["lfile"] = "success";
            $state["llink"] = "window.open('/".$tenant->Insurance->liability_filename."')";
        }    
        if ($tenant->Insurance->umbrella_filename != null) {
            $state["ufile"] = "success";
            $state["ulink"] = "window.open('/".$tenant->Insurance->umbrella_filename."')";
        } 
        if ($tenant->Insurance->auto_filename != null) {
            $state["afile"] = "success";
            $state["alink"] = "window.open('/".$tenant->Insurance->auto_filename."')";
        } 
        if ($tenant->Insurance->workerscomp_filename != null) {
            $state["wfile"] = "success";
            $state["wlink"] = "window.open('/".$tenant->Insurance->workerscomp_filename."')";
        }    
        if ($tenant->Insurance->liability_end > $today) {
            $state["lexpire"] = "success";
        }  
        if ($tenant->Insurance->umbrella_end > $today) {
            $state["uexpire"] = "success";
        }
        if ($tenant->Insurance->auto_end > $today) {
            $state["aexpire"] = "success";
        }
        if ($tenant->Insurance->workerscomp_end > $today) {
            $state["wexpire"] = "success";
        }
        if ($tenant->req_liability_single_limit != null &&  $tenant->req_liability_combined_limit != null  ) {
            if ( $tenant->req_liability_single_limit <= $tenant->Insurance->liability_single_limit  && $tenant->req_liability_combined_limit <= $tenant->Insurance->liability_combined_limit) {
                $state["llimit"] = "success";
            }
        }
        elseif ($tenant->Property->req_liability_single_limit <= $tenant->Insurance->liability_single_limit  && $tenant->Property->req_liability_combined_limit <= $tenant->Insurance->liability_combined_limit) {
            $state["llimit"] = "success";
        }
        if ($tenant->req_umbrella_limit != null){
            if ($tenant->req_umbrella_limit <= $tenant->Insurance->umbrella_limit) {
                $state["ulimit"] = "success";
            }
        }
        elseif ($tenant->Property->req_umbrella_limit <= $tenant->Insurance->umbrella_limit) {
            $state["ulimit"] = "success";
        }

        if ($tenant->req_auto_limit != null){
            if ($tenant->req_auto_limit <= $tenant->Insurance->auto_limit) {
                $state["alimit"] = "success";
            }
        }
        elseif ($tenant->Property->req_auto_limit <= $tenant->Insurance->auto_limit) {
            $state["alimit"] = "success";
        }

        if ($tenant->req_workerscomp_limit != null){
            if ($tenant->req_workerscomp_limit <= $tenant->Insurance->workerscomp_limit) {
                $state["wlimit"] = "success";
            }
        }
        elseif ($tenant->Property->req_workerscomp_limit <= $tenant->Insurance->workerscomp_limit) {
            $state["wlimit"] = "success";
        }

        return $state;
    }

    //takes an .xls file to import in a mass amount of tenants at once. 
    public function import(Request $request)
    {

        $file = $request->tenantimport;
        $file->move('tmp/','import.xls');

        Helper::importTenant('tmp/import.xls');

        return redirect('/tenant/list');
    }
}
