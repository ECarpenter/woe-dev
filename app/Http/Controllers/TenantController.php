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

        $state = Helper::insuranceCheck($tenant);


        return view('tenant.show', compact('tenant','state'));
    }

    public function tenantlist()
    {
        $tenants = Tenant::orderByRaw('company_name COLLATE NOCASE')->get();

        return view('tenant.viewlist',compact('tenants'));
    }

    public function tenantuploadlist()
    {
        $insurances = Insurance::whereNotNull('tempfile')->get();
        $tenants = array();
        foreach ($insurances as $insurance) {
            $tenants[] = $insurance->tenant;
        }

        return view('tenant.viewlist',compact('tenants'));
    }

    public function tenantnoncompliancelist()
    {
        $insurances = Insurance::where('compliant', false)->get();
        $tenants = array();
        foreach ($insurances as $insurance) {
            $tenants[] = $insurance->tenant;
        }

        return view('tenant.viewlist',compact('tenants'));
    }


    public function upload(Tenant $tenant, Request $request)
    {

        $this->validate($request, [ 
            'insurance_cert' => 'required|mimes:pdf'
            ]);
        
        $used = false;

        $fname = 'ins-'.date('ymd-His', strtotime(\Carbon\Carbon::now())).'.pdf';

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


    

    //takes an .xls file to import in a mass amount of tenants at once. 
    public function import(Request $request)
    {

        $file = $request->tenantimport;
        $file->move('tmp/','import.xls');

        Helper::importTenant('tmp/import.xls');

        return redirect('/tenant/list');
    }
}
