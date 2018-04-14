<?php
// Code within app\Helpers\Helper.php

namespace App\Helpers;

use Log;
use Excel;
use Mail;
use Storage;
use DB;
use PHPExcel;
use Config;
use Response;

use Illuminate\Support\Str;
use App\Group;
use App\Insurance;
use App\Owner;
use App\Post;
use App\Property;
use App\Remit;
use App\Tenant;
use App\WorkOrder;
use App\User;
use App\Vendor;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Session;

class Helper
{

	public static function attachPrimary()
	{
		$properties = Property::where('id', '>', 147)->get();
		foreach ($properties as $property) 
		{
		 	$user = User::where('id', '=', $property->primary_manager)->first();
		 	$user->Properties()->attach($property->id);
		}        
	}
	public static function importProperty($fname)
	{
		Excel::load($fname, function($reader) {
		   
			$reader->each(function($sheet){
				$sheet->each(function($row){
					
					$property = Property::where('property_system_id', $row->property_system_id)->first();
					if ($property == null)
					{
						$property = new Property;
						$property->name = $row->name;
						$property->property_system_id = $row->property_system_id;
						$property->address = $row->address;
						$property->city = $row->city;
						$property->state = $row->state;
						$property->zip = $row->zip;
						$property->insured_name = $row->insured_name;
						$property->owner_id = $row->owner_id;
						$property->req_liability_single_limit = $row->req_liability_single_limit;
						$property->req_liability_combined_limit = $row->req_liability_combined_limit;
						$property->req_auto_limit = $row->req_auto_limit;
						$property->req_umbrella_limit = $row->req_umbrella_limit;
						$property->req_workerscomp_limit = $row->req_workerscomp_limit;
						$remit = Remit::where('system_id','=', $row->remit_id)->first();
						if ($remit != null)
						{
							$property->remit_id = $remit->id;
						}
						$property->save();
					}
					else 
					{
						$remit = Remit::where('system_id','=', $row->remit_id)->first();
						if ($remit != null)
						{
							$property->remit_id = $remit->id;
							$property->save();
						}
					}
				});
			});

		});      
	}

	public static function importRemit($fname)
	{
		Excel::load($fname, function($reader) {
		   
			$reader->each(function($sheet){
				$sheet->each(function($row){
					
					if ($row->payable_to != null)
					{
						$vendor = Vendor::where('system_id', '=', $row->system_id)->first();
						if ($vendor == null)
						{
							$vendor = new Vendor;
							$vendor->payable_to = $row->payable_to;
							$vendor->address = $row->address;
							$vendor->address_secondline = $row->address_secondline;
							$vendor->city = $row->city;
							$vendor->state = $row->state;
							$vendor->zip = $row->zip;
							$vendor->contact_name = $row->contact_name;
							$vendor->email = $row->email;
							$vendor->phone = $row->phone;
							$vendor->system_id = $row->system_id;
							if ($row->remit == 'TRUE')
							{
								$vendor->remit = true;
							}

							$vendor->save();
						}

						//adds remit to property
						$property = Property::where('property_system_id', $row->property_id)->first();
						if ($property != null)
						{
							$property->remit_id = $vendor->id;
							$property->save();
						}
						

					}
				});
			});

		});      
	}

	public static function importManager($fname)
	{
		Excel::load($fname, function($reader) {

			$reader->each(function($sheet){

				$sheet->each(function($row){
					if ($row->property_id != null)
					{
						$user = User::where('email', $row->email)->first();
						$property = Property::where('property_system_id', $row->property_id)->first();
						if ($property != null)
						{
							if ($user == null)
							{
								$user = new User;
								$user->name = $row->first.' '.$row->last;
								$user->email = $row->email;
								$user->password = bcrypt($row->last);
								$user->timezone = "America/Los_Angeles";
								$user->phone = $row->phone;
								$user->fax = $row->fax;
								$user->address = $row->address;
								$user->city = $row->city;
								$user->state = $row->state;
								$user->zip = $row->zip;
								$user->save();
								$role = DB::table('roles')->where('name', '=', 'manager')->pluck('id');
								$user->Properties()->attach($property->id);
								$user->Roles()->attach($role);
							}					

							$property->primary_manager = $user->id;
							$property->save();
						}
					}
				});
			});
		});      
	}

	public static function importPastTenant($fname)
	{
		Excel::load($fname, function($reader) {
		   
			$reader->each(function($sheet){
				$sheet->each(function($row){

					$tenant = Tenant::where('tenant_system_id', '=', $row->tenant_system_id)->first();
					if ( $tenant != null)
					{
						$tenant->active = false;
						$tenant->save();
					}
				});
			});

		});
	}	

	public static function importSoldProperties($fname)
	{
		Excel::load($fname, function($reader) {
		   
			$reader->each(function($sheet){
				$sheet->each(function($row){
					$property = Property::where('property_system_id', '=', $row->property)->first();
					if ( $property != null)
					{
						$tenants = Helper::filterbyproperty($property->property_system_id);
						foreach ($tenants as $tenant) 
						{
							$tenant->active = false;
							$tenant->save();
						}
						$property->active = false;
						$property->save();
					}
				});
			});
		});
	}	

	public static function importTenant($fname)
	{
		Excel::load($fname, function($reader) {
		   
			$reader->each(function($sheet){
				$sheet->each(function($row){

					if (Tenant::where('tenant_system_id', '=', $row->tenant_system_id)->first() == null)
					{

						if ($row->tenant_system_id != null)
						{
							$tenant = new Tenant;

							$tenant->company_name = $row->company_name;
							$tenant->tenant_system_id = $row->tenant_system_id;
							$tenant->insurance_contact_email = $row->insurance_contact_email;
							$tenant->unit = $row->unit;
							if ($row->req_liability_single_limit != null) {	                        	
								$tenant->req_liability_single_limit = $row->req_liability_single_limit;
							}
							if ($row->req_liability_single_limit != null) {	                    		
								$tenant->req_liability_combined_limit = $row->req_liability_combined_limit;
							}
							if ($row->req_liability_single_limit != null) {	                    		
								$tenant->req_auto_limit = $row->req_auto_limit;
							}
							if ($row->req_liability_single_limit != null) {	                    		
								$tenant->req_umbrella_limit = $row->req_umbrella_limit;
							}
							if ($row->req_liability_single_limit != null) {
								$tenant->req_workerscomp_limit = $row->req_workerscomp_limit;
							}
							
							$property = Property::where('name', '=', $row->property_name)->first();
							if ($property != null)
							{
								
								$tenant->property_id = $property->id;
								$tenant->save();
								$ins = new Insurance;
								$ins->tenant_id = $tenant->id;
								$ins->save();
							}
						}
					}
				});
			});

		});
	}

	public static function importTransfer($fname)
	{
		Excel::load($fname, function($reader) {
		   
			$reader->each(function($sheet){
				$sheet->each(function($row){

					$tenant = Tenant::where('tenant_system_id', $row->old_id)->first();
					if ($tenant != null )
					{
						$tenant->tenant_system_id = $row->new_id;
						$tenant->save();
					} 
				});
			});
		});	
	}

	public static function importInsurance($fname)
	{
		Excel::load($fname, function($reader) {
		   
			$reader->each(function($sheet){
				$sheet->each(function($row){

					$tenant = Tenant::where('tenant_system_id', $row->tenant_system_id)->first();
					if ($tenant != null )
					{
							$insurance = $tenant->Insurance;
							$insurance->liability_single_limit = $row->liability_single_limit;
							$insurance->liability_combined_limit = $row->liability_combined_limit;
							$insurance->liability_start = $row->liability_start;
							$insurance->liability_end = $row->liability_end;
							$insurance->liability_filename = $row->liability_filename;
							$insurance->auto_limit = $row->auto_limit;
							$insurance->auto_start = $row->auto_start;
							$insurance->auto_end = $row->auto_end;
							$insurance->auto_filename = $row->auto_filename;
							$insurance->umbrella_limit = $row->umbrella_limit;
							$insurance->umbrella_start = $row->umbrella_start;
							$insurance->umbrella_end = $row->umbrella_end;
							$insurance->umbrella_filename = $row->umbrella_filename;
							$insurance->workerscomp_limit = $row->workerscomp_limit;
							$insurance->workerscomp_start = $row->workerscomp_start;
							$insurance->workerscomp_end = $row->workerscomp_end;
							$insurance->workerscomp_filename = $row->workerscomp_filename;
							$insurance->endorsement_filename = $row->endorsement_filename;
							
							$insurance->save();
							
					}
				});
			});

		});
	}

	public static function importNewLease($fname)
	{
		Excel::load($fname)->byConfig('excel.import.sheets', function($sheet) {
			if ($sheet->sheetName == 'Lease Summary')
			{
				if ($sheet->valueByindex('lease_summary.Version') == config('excel.import.sheets.lease_summary.Desired-Version'))
				{
					$property_id = $sheet->valueByindex('lease_summary.Property-ID');
					$property = Property::where('property_system_id', '=', $property_id)->first();
					if ($property != null)
					{
						$tenant_id = $sheet->valueByindex('lease_summary.Tenant-ID');
						if ($tenant_id != null)
						{
							
							if ($sheet->valueByindex('lease_summary.Suite') == null)
							{
								Session::flash('warning', $tenant_id . ' - No suite entered');
							}
							elseif ($sheet->valueByIndex('lease_summary.Tenant-Name') == null)
							{
								Session::flash('warning', $tenant_id . ' - No name entered');
							}
							else
							{
								$newTenant = false;
								$tenant = Tenant::where('tenant_system_id', '=', $tenant_id)->first();
								if ($tenant == null)
								{
									$tenant = New Tenant;
									$tenant->tenant_system_id = $tenant_id;
									$newTenant = true;
								}
								
								$tenant->company_name = $sheet->valueByindex('lease_summary.Tenant-Name');
								$tenant->unit = $sheet->valueByindex('lease_summary.Suite');
								$tenant->insurance_contact_email = $sheet->valueByindex('lease_summary.E-Mail');
								$tenant->property_id = $property->id;
								$tenant->save();
								if ($newTenant)
								{
									$ins = new Insurance;
									$ins->tenant_id = $tenant->id;
									$ins->save();
								}

								$success = Helper::readInsuranceRequirements($tenant, $sheet, 'lease_summary');
								if ($success)
								{
									Session::flash('success', ' New Tenant Created and Saved');
								}
							}
						}
						else
						{
							Session::flash('warning', 'No tenant id entered');
						}
		 			}
					else
					{
						Session::flash('warning', $property_id . ' - Not found in system');	 
					}
				}
				else
				{
					Session::flash('warning', 'Wrong version of lease summary please use version - ' . config('excel.import.sheets.lease_summary.Desired-Version'));
				}
			}
		});
	}

	public static function importInsuranceRequirements($fname)
	{

		Excel::load($fname)->byConfig('excel.import.sheets', function($sheet) {
			$property_id = $sheet->valueByindex('general-ins-req.Property-ID');
			$property = Property::where('property_system_id', '=', $property_id)->first();
			if ($property != null)
			{
				$property->insured_name = $sheet->valueByindex('general-ins-req.Additional-Insured');
				$property->save();
				$lease = $sheet->valueByindex('general-ins-req.Lease-to-Use');
				if ($lease != null)
				{
					$success = Helper::readInsuranceRequirements($property, $sheet, 'lease_' . $lease);
					if ($success)
					{
						log::info($property_id . ' - Success');
					}
					else
					{
						log::info($property_id . '- Failure on lease data');
					}
				}
				else
				{
					log::info($property_id . ' - No Lease Selected');
				}
				
			}
			else
			{
				log::info($property_id . ' - Not found in system');
			}
		});
	}

	protected static function readInsuranceRequirements( $object, $sheet, $index)
	{

		
		
		$object->req_cgl = $sheet->valueByindex($index . '.CGL');
		if ($object->req_cgl == 'Other')
		{
			$object->req_cgl = $sheet->valueByindex($index . '.CGL-Other');
		}
        $object->req_cgl_deductible = $sheet->valueByindex($index . '.CGL-Deductible');
        if ($object->req_cgl_deductible == 'Other')
		{
			$object->req_cgl_deductible = $sheet->valueByindex($index . '.CGL-Deductible-Other');
		}
        $object->req_excess = $sheet->valueByindex($index . '.Excess');
        $object->req_excess_coverage = $sheet->valueByindex($index . '.Excess-Coverage');
        if ($object->req_excess_coverage == 'Other')
		{
			$object->req_excess_coverage = $sheet->valueByindex($index . '.Excess-Coverage-Other');
		}
        $object->req_umbrella = $sheet->valueByindex($index . '.Umbrella');
        $object->req_umbrella_coverage = $sheet->valueByindex($index . '.Umbrella-Coverage');
        if ($object->req_umbrella_coverage == 'Other')
		{
			$object->req_umbrella_coverage = $sheet->valueByindex($index . '.Umbrella-Coverage-Other');
		}
        $object->req_cause_of_loss = $sheet->valueByindex($index . '.Cause-of-Loss');
        $object->req_pollution = $sheet->valueByindex($index . '.Pollution-Liability');
        if ($object->req_pollution == 'Other')
		{
			$object->req_pollution = $sheet->valueByindex($index . '.Pollution-Liability-Other');
		}
        $object->req_employers_liability = $sheet->valueByindex($index . '.Employers-Liability');
        if ($object->req_employers_liability == 'Other')
		{
			$object->req_employers_liability = $sheet->valueByindex($index . '.Employers-Liability-Other');
		}
        $object->req_auto_liability = $sheet->valueByindex($index . '.Auto-Liability');
        $object->req_auto_liability_coverage = $sheet->valueByindex($index . '.Auto-Liability-Coverage');
        if ($object->req_auto_liability_coverage == 'Other')
		{
			$object->req_auto_liability_coverage = $sheet->valueByindex($index . '.Auto-Liability-Coverage-Other');
		}
		if ($sheet->valueByindex($index . '.Pollution-Exclusion') != 'Yes')
		{
        	$object->req_pollution_amend = false;
		}
		else
		{
			$object->req_pollution_amend = true;
		}
		if ($sheet->valueByindex($index . '.Additional-Insured-Managers') != 'Yes')
		{
        	$object->req_additional_ins_endorsement = false;
		}
		else
		{
			$object->req_additional_ins_endorsement = true;
		}
		if ($sheet->valueByindex($index . '.TPP') != 'Yes')
		{
       		$object->req_tenants_pp = false;
		}
		else
		{
			$object->req_tenants_pp = true;
		}
		if ($sheet->valueByindex($index . '.TI') != 'Yes')
		{
        	$object->req_tenant_improvements = false;
		}
		else
		{
			$object->req_tenant_improvements = true;
		}
		if ($sheet->valueByindex($index . '.Tenants-fixtures') != 'Yes')
		{
        	$object->req_tenant_fixtures = false;
		}
		else
		{
			$object->req_tenant_fixtures = true;
		}
		if ($sheet->valueByindex($index . '.Earthquake') != 'Yes')
		{
        	$object->req_earthquake = false;
		}
		else
		{
			$object->req_earthquake = true;
		}
		if ($sheet->valueByindex($index . '.Flood') != 'Yes')
		{
        	$object->req_flood = false;
		}
		else
		{
			$object->req_flood = true;
		}
		if ($sheet->valueByindex($index . '.Workers-Comp') != 'Yes')
		{
        	$object->req_workers_comp = false;
		}
		else
		{
			$object->req_workers_comp = true;
		}
		if ($sheet->valueByindex($index . '.Business-Interruption') != 'Yes')
		{
    	    $object->req_business_interruption = false;
		}
		else
		{
			$object->req_business_interruption = true;
		}
		if ($sheet->valueByindex($index . '.Waiver-of-Subrogation') != 'Yes')
		{
	        $object->req_waiver_of_subrogation = false;
		}
		else
		{
			$object->req_waiver_of_subrogation = true;
		}
		if ($sheet->valueByindex($index . '.Data-Endorsement') != 'Yes')
		{
	        $object->req_data_endorsement = false;
		}
		else
		{
			$object->req_data_endorsement = true;
		}
        $object->save();

		return true;	
	}

	//Check for insurance compliance
	//each key gets either success or danger depending on situation
	public static function insuranceCheck(Tenant $tenant)
	{
		$insurance = $tenant->Insurance;
		$insurance->compliant = true;
		$state = array(
			"lfile" => "success",
			"ufile" => "success",
			"afile" => "success",
			"wfile" => "success",
			"efile" => "success",
			"lexpire" => "success",
			"uexpire" => "success",
			"aexpire" => "success",
			"wexpire" => "success",
			"llimit" => "success",
			"ulimit" => "success",
			"alimit" => "success",
			"wlimit" => "success",
			"elink" => "",
			"llink" => "",
			"ulink" => "",
			"alink" => "",
			"wlink" => "",
			"manual_notice" => "valid"
			);

		$today = date("Y-m-d");
		
		if ($tenant->Insurance->endorsement_filename == null) {
			$state["efile"] = "danger";
		}
		else {
			
			$state["elink"] = "window.open('".Helper::getS3URL($tenant->insurance->filepath.$tenant->insurance->endorsement_filename)."')";
		} 
		if ($tenant->Insurance->liability_filename == null) {
			$state["lfile"] = "danger";
			$insurance->compliant = false;
		}
		else {
			
			$state["llink"] = "window.open('".Helper::getS3URL($tenant->insurance->filepath.$tenant->insurance->liability_filename)."')";
		}    
		if ($tenant->Insurance->umbrella_filename == null) {
			$state["ufile"] = "danger";
			//$insurance->compliant = false;
		}
		else {
			$state["ulink"] = "window.open('".Helper::getS3URL($tenant->insurance->filepath.$tenant->insurance->umbrella_filename)."')";
		} 
		if ($tenant->insurance->auto_filename == null) {
			$state["afile"] = "danger";
			//$insurance->compliant = false;
		}
		else {
			$state["alink"] = "window.open('".Helper::getS3URL($tenant->insurance->filepath.$tenant->insurance->auto_filename)."')";
		} 
		if (!$tenant->insurance->workerscomp_applicable) {
			$state["wfile"] = "";
		}
		elseif ($tenant->insurance->workerscomp_filename == null) {
			$state["wfile"] = "danger";
			//$insurance->compliant = false;
		}
		else {
			$state["wlink"] = "window.open('".Helper::getS3URL($tenant->insurance->filepath.$tenant->insurance->workerscomp_filename)."')";
		}    
		if ($tenant->insurance->liability_end < $today) {
			$state["lexpire"] = "danger";
			$insurance->compliant = false;
		}  
		if ($tenant->insurance->umbrella_end < $today) {
			$state["uexpire"] = "danger";
			//$insurance->compliant = false;
		}
		if ($tenant->insurance->auto_end < $today) {
			$state["aexpire"] = "danger";
			//$insurance->compliant = false;
		}
		if (!$tenant->insurance->workerscomp_applicable) {
			$state["wexpire"] = "";
		}
		elseif ($tenant->insurance->workerscomp_end < $today) {
			$state["wexpire"] = "danger";
			//$insurance->compliant = false;
		}
		if ($tenant->req_liability_single_limit > 0 &&  $tenant->req_liability_combined_limit > 0  ) {
			if ( $tenant->req_liability_single_limit > $tenant->insurance->liability_single_limit || $tenant->req_liability_combined_limit > $tenant->insurance->liability_combined_limit) {
				$state["llimit"] = "danger";
				//$insurance->compliant = false;
			}
		}
		elseif ($tenant->Property->req_liability_single_limit > $tenant->insurance->liability_single_limit  || $tenant->Property->req_liability_combined_limit > $tenant->insurance->liability_combined_limit) {
			$state["llimit"] = "danger";
			//$insurance->compliant = false;
		}
		if ($tenant->req_umbrella_limit > 0){
			if ($tenant->req_umbrella_limit > $tenant->insurance->umbrella_limit) {
				$state["ulimit"] = "danger";
				//$insurance->compliant = false;
			}
		}
		elseif ($tenant->Property->req_umbrella_limit > $tenant->insurance->umbrella_limit) {
			$state["ulimit"] = "danger";
			//$insurance->compliant = false;
		}

		if ($tenant->req_auto_limit > 0){
			if ($tenant->req_auto_limit > $tenant->insurance->auto_limit) {
				$state["alimit"] = "danger";
				//$insurance->compliant = false;
			}
		}
		elseif ($tenant->Property->req_auto_limit > $tenant->insurance->auto_limit) {
			$state["alimit"] = "danger";
			//$insurance->compliant = false;
		}
		if (!$tenant->insurance->workerscomp_applicable) {
			$state["wlimit"] = "disabled";
		}
		elseif ($tenant->req_workerscomp_limit > 0){
			if ($tenant->req_workerscomp_limit > $tenant->insurance->workerscomp_limit) {
				$state["wlimit"] = "danger";
				//$insurance->compliant = false;
			}
		}
		elseif ($tenant->Property->req_workerscomp_limit > $tenant->insurance->workerscomp_limit) {
			$state["wlimit"] = "danger";
			//$insurance->compliant = false;
		}

		if ($insurance->compliant){
			$state['manual_notice'] = "invalid";
		}
		if ($insurance->last_notice_sent != null && $insurance->last_notice_sent->addDay()->gt(\Carbon\Carbon::now())) {
			$state['manual_notice'] = "invalid";
		}


		$insurance->save();
		return $state;
	}

	public static function processInsuranceChecks($tenants)
	{
		$noncompliancecollection = collect();
		foreach ($tenants as $tenant) {
			$state = Helper::insuranceCheck($tenant);
			if (!$tenant->Insurance->compliant) {
				
				$noncompliancecollection->push($tenant);
			}
		}
		return $noncompliancecollection;
	}

	public static function sendInsuranceNotice(Tenant $tenant, $type)
	{
		if ($tenant->insurance_contact_email != null)
		{
			$tenant->load('insurance','property');
			$token = Str::random(60);
			$tenant->insurance->upload_token = $token;
			$tenant->insurance->last_notice_sent = \Carbon\Carbon::now();
			$tenant->insurance->save();

			Log::info('Insurance notification e-mail sent to '.$tenant->insurance_contact_email);
			Mail::send('email.insurance-notice',compact('tenant', 'token','type'), function ($message) use ($tenant) {
				$message->from('insurance@davispartners.com', 'Insurance Administrator');
				$message->subject('Insurance Certificate Needs Update');
				$message->to($tenant->insurance_contact_email);
			});
		}
	}

	public static function sendPost(Post $post, $workorder, $useremail)
	{
		Mail::queue('email.response',compact('workorder', 'post'), function ($message) use ($useremail) {
                    $message->from(\Auth::user()->email, \Auth::user()->name);
                    $message->subject('Work Order - New Message');
                    $message->to($useremail);
                });   
	}

	public static function getS3URL($filename)
	{
		$s3 = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
		$getObjectCmd = $s3->getCommand('GetObject', [
				'Bucket' => env('S3_BUCKET'),
				'Key' => $filename
			]);
		$getObjectReq = $s3->createPresignedRequest($getObjectCmd, '+1 hour');
		return (string) $getObjectReq->getUri();
	}

	public static function pdfToTiff()
	{
		$convert = new Process('gswin64c -o document.tiff -sDEVICE=tiffgray -r720x720 -g6120x7920 -sCompression=lzw test.pdf');
		$convert->run();

		if (!$convert->isSuccessful()) 
		{
    		throw new ProcessFailedException($convert);
		}

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

	//processes list of user returns unverified users
	public static function checkUserStatus($users)
	{
		
			
			$users = $users->keyBy('id');
			foreach ($users as $user)
			{
				if ($user->hasRole('tenant'))
				{
					if ( $user->verified)
					{	
						$users->forget($user->id);
					}
				}
				else
				{
					$users->forget($user->id);
				}
				
			}
		

		return $users;
	}

	public static function filterbyproperty($property_system_id)
	{
		$tenants = collect();
		$property = Property::where('property_system_id',$property_system_id)->first();
		$group = Group::where('group_system_id', $property_system_id)->first();
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

		return $tenants;

	}


	//Transfers existing workorder comments to posts
	public static function transferToPost()
	{
		$workorders = WorkOrder::all();

		foreach($workorders as $workorder)
		{
			if ($workorder->manager_notes != null)
			{
				$post = new Post;
				$post->created_at = $workorder->updated_at;
				$post->message = $workorder->manager_notes;
				$post->user_id = $workorder->Property()->primary_manager;
				$post->work_order_id = $workorder->id;
				$post->save();
			}
		}


	}

}