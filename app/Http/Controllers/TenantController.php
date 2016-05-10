<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Http\Requests;
use App\Tenant;
use App\User;


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
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'timezone' => "America/Los_Angeles"
        ]);

        $role = DB::table('roles')->where('name', '=', 'tenant')->pluck('id');
        $user->Roles()->attach($role);

        $tenant = new Tenant;
        $tenant->user_id = $user->id;
        $tenant->unit = $request['suite'];
        $tenant->property_id = $request['property'];
        $tenant->company_name = $request['company_name'];
        $tenant->job_title = $request['job_title'];
        $tenant->tenant_system_id = $request['tenant_system_id'];
        $tenant->save();

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
        $tenant->load('workorder', 'workorder.problemtype');
        return view('tenant.show', compact('tenant'));
    }

    public function tenantlist()
    {
        $tenants = Tenant::orderBy('company_name')->get();

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

        if ($used) {
            $ins->save();
            $file = $request->insurance_cert;
            $file->move('files/insurance/', $fname);
        }


        return back();

    }


}
