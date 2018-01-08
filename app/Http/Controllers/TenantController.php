<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use DB;
use Response;

use App\Http\Requests;
use App\Helpers\Helper;
use App\Tenant;
use App\User;
use App\Insurance;
use App\Property;
use App\Group;


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
		$properties = Property::orderBy('name')->get();
		if (!\Auth::user()->can('view-all'))
		{
			$count = 0;
			foreach ($properties as $property)
			{
				if (!$property->canAccess())
				{	
					$properties->forget($count);
				}
				$count++;
			}
		}
		return view('tenant.add', compact('properties'));
	}

	public function save(Request $request)
	{
		$this->validate($request, [
			'suite' => 'required',
			'email' => 'required|email',
			'tenant_system_id' => 'required|unique:tenants',
			'company_name' => 'required',
			'property' => 'required'
			]);
		
		$tenant = new Tenant;
		$tenant->unit = $request->suite;
		$tenant->property_id = $request->property;
		$tenant->company_name = $request->company_name;
		$tenant->tenant_system_id = $request->tenant_system_id;
		$tenant->insurance_contact_email = $request->email;

		$tenant->save();

		$ins = new Insurance;
		$ins->tenant_id = $tenant->id;
		$ins->save();
		return redirect('/tenant/'.$tenant->id);
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

	public function changeactive(Tenant $tenant)
	{

		if ($tenant->active)
		{
			$tenant->active = false;
		}
		else
		{
			$tenant->active = true;
		}
		$tenant->save();
		return redirect('tenant/list');
	}


	public function show(Tenant $tenant)
	{
		if (!\Auth::user()->can('view-all') && !$tenant->property->canAccess())
		{
			return TenantController::tenantlist();
		}
		$tenant->load('workorder', 'workorder.problemtype','user','insurance');

		$state = Helper::insuranceCheck($tenant);
		
		$tempfileurl = '';
		if ($tenant->insurance->tempfile != null)
		{
			$tempfileurl = Helper::getS3URL($tenant->insurance->filepath.$tenant->insurance->tempfile);
		}

		return view('tenant.show', compact('tenant','state','tempfileurl'));
	}

	public function tenantlist()
	{
		$tenants = Tenant::where('active', true)->orderBy('company_name')->get();
		$active_selector = 'active';
		
		$tenants = Helper::checkPermissions($tenants);


		return view('tenant.viewlist',compact('tenants','active_selector'));
	}

	public function unverifiedlist()
	{
		$users = User::all();
		$users = Helper::checkUserStatus($users);


		return view('user.unverifiedlist',compact('users'));
	}

	public function tenantuploadlist()
	{
		$insurances = Insurance::whereNotNull('tempfile')->get();
		$tenants = collect();
		foreach ($insurances as $insurance) {
			$tenants->prepend($insurance->tenant);
		}
		$tenants = Helper::checkPermissions($tenants);

		$tenants = $tenants->sortBy('company_name');

		return view('insurance.viewlist',compact('tenants'));
	}

	public function tenantnoncompliancelist()
	{
		$insurances = Insurance::where('compliant', false)->get();
		$tenants = collect();
		foreach ($insurances as $insurance) {
			if ($insurance->tenant->active) {
				$tenants->prepend($insurance->tenant);
			}
		}
		
		$tenants = Helper::checkPermissions($tenants);

		$tenants = $tenants->sortBy('company_name');

		return view('insurance.viewlist',compact('tenants','active_selector'));
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

	public function refinelist(Request $request)
	{
		$tenants = Helper::filterbyproperty($request->property_system_id);
		
		if ($request->active_selector == 'active') {
			$tenants = $tenants->filter(function ($tenant) {
				return $tenant->active;
			});
		}
		elseif ($request->active_selector == 'inactive') {
			$tenants = $tenants->filter(function ($tenant) {
				return !$tenant->active;
			});
		}
		$active_selector = $request->active_selector;

		$tenants = Helper::checkPermissions($tenants);

		return view('tenant.viewlist',compact('tenants', 'active_selector'));
	}

	public function response(Tenant $tenant)
	{
		$tenant->load('user');
		return Response::json($tenant);
	}


	public function update(Tenant $tenant, Request $request)
	{
		$tenant->tenant_system_id = $request->tenant_system_id;
		$tenant->unit = $request->unit;
		$tenant->company_name = $request->company_name;
		$tenant->insurance_contact_email = $request->insurance_contact_email;
		if ($request->active_switch == 'true'){
			$tenant->active = true;
		}
		else {
			$tenant->active = false;
		}
		//$tenant->User()->verified = $request->verified_switch;
		$tenant->save();
		
		return redirect('/tenant/'.$tenant->id);
	}

	public function notice(Tenant $tenant)
	{
		Helper::sendInsuranceNotice($tenant, 'manual');

		Session::flash('success','Notice has been sent!');

		return Response::json($tenant->id);
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
