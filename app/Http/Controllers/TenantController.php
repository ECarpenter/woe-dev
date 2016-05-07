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
}
