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

	public function show(Tenant $tenant)
	{
		if (!\Auth::user()->can('view-all'))
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
		
		$tenants = TenantController::checkPermissions($tenants);


		return view('tenant.viewlist',compact('tenants','active_selector'));
	}

	public function tenantuploadlist()
	{
		$insurances = Insurance::whereNotNull('tempfile')->get();
		$tenants = array();
		foreach ($insurances as $insurance) {
			$tenants[] = $insurance->tenant;
		}
		$tenants = TenantController::checkPermissions($tenants);

		return view('insurance.viewlist',compact('tenants'));
	}

	public function tenantnoncompliancelist()
	{
		$insurances = Insurance::where('compliant', false)->get();
		$tenants = array();
		foreach ($insurances as $insurance) {
			if ($insurance->tenant->active) {
				$tenants[] = $insurance->tenant;
			}
		}
		
		$tenants = TenantController::checkPermissions($tenants);

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
		$tenants = collect();
		$property = Property::where('property_system_id',$request->property_system_id)->first();
		$group = Group::where('group_system_id', $request->property_system_id)->first();
		if ($group != null) 
		{
			$group->load('properties', 'properties.owner');
			
			foreach ($group->Properties as $property) 
			{
				foreach ($property->tenants as $tenant) {
					$tenants->prepend($tenant);
				}
			}
			$tenants = $tenants->sortBy('company_name');
		}
		elseif ($property != null) 
		{
			foreach ($property->Tenants as $tenant) 
			{               
				$tenants->prepend($tenant);
			}
			$tenants = $tenants->sortBy('company_name');
		}
		else 
		{
			$tenants = Tenant::orderBy('company_name')->get();
		}
		
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

		$tenants = TenantController::checkPermissions($tenants);

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

		return back();
	}

	//takes an .xls file to import in a mass amount of tenants at once. 
	public function import(Request $request)
	{

		$file = $request->tenantimport;
		$file->move('tmp/','import.xls');

		Helper::importTenant('tmp/import.xls');

		return redirect('/tenant/list');
	}

	public static function checkPermissions($tenants)
	{
		if (!\Auth::user()->can('view-all'))
		{
			$tenants = $tenants->keyBy('id');
			foreach ($tenants as $tenant)
			{
				if (!$tenant->property->canAccess())
				{	
					$tenants->forget($tenant->id);
				}
				
			}
		}

		return $tenants;
	}
}
