<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Excel;
use Mail;

use Illuminate\Support\Str;
use App\Tenant;
use App\User;
use App\Property;
use App\Owner;
use App\Group;
use App\Insurance;


class Helper
{
    public static function importProperty($fname)
    {
        Excel::load($fname, function($reader) {
           
            $reader->each(function($sheet){
                $sheet->each(function($row){
                    

                    if (Property::where('property_system_id', $row->property_system_id)->first() == null)
                    {
                    	$property = new Property;
                        $property->name = $row->name;
                        $property->property_system_id = $row->property_system_id;
                        $property->address = $row->address;
                        $property->city = $row->city;
                        $property->state = $row->state;
                        $property->zip = $row->zip;
                        $property->owner_id = $row->owner_id;
                        $property->req_liability_single_limit = $row->req_liability_single_limit;
                        $property->req_liability_combined_limit = $row->req_liability_combined_limit;
                        $property->req_auto_limit = $row->req_auto_limit;
                        $property->req_umbrella_limit = $row->req_umbrella_limit;
                        $property->req_workerscomp_limit = $row->req_workerscomp_limit;
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


                    if (Tenant::where('tenant_system_id', $row->tenant_system_id)->first() == null)
                    {
                    	if ($row->tenant_system_id != null)
                    	{
	                        $tenant = new Tenant;

	                        $tenant->company_name = $row->company_name;
	                        $tenant->tenant_system_id = $row->tenant_system_id;
	                        $tenant->insurance_contact_email = $row->insurance_contact_email;
	                        $tenant->unit = $row->unit;
	                        $tenant->req_liability_single_limit = $row->req_liability_single_limit;
	                        $tenant->req_liability_combined_limit = $row->req_liability_combined_limit;
	                        $tenant->req_auto_limit = $row->req_auto_limit;
	                        $tenant->req_umbrella_limit = $row->req_umbrella_limit;
	                        $tenant->req_workerscomp_limit = $row->req_workerscomp_limit;

	                        $property = Property::where('name',$row->property_name)->first();
	                        if ($property != null)
	                        {
	                        	$tenant->property_id = $property->id;
	                        }

	                    	$tenant->save();

	                    	$ins = new Insurance;
	                        $ins->tenant_id = $tenant->id;
	                        $ins->save();
	                    	}
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
	                        $insurance->liability_filename = 'files/insurance/'.$row->liability_filename;
	                        $insurance->auto_limit = $row->auto_limit;
	                        $insurance->auto_start = $row->auto_start;
	                        $insurance->auto_end = $row->auto_end;
	                        $insurance->auto_filename = 'files/insurance/'.$row->auto_filename;
	                        $insurance->umbrella_limit = $row->umbrella_limit;
	                        $insurance->umbrella_start = $row->umbrella_start;
	                        $insurance->umbrella_end = $row->umbrella_end;
	                        $insurance->umbrella_filename = 'files/insurance/'.$row->umbrella_filename;
	                        $insurance->workerscomp_limit = $row->workerscomp_limit;
	                        $insurance->workerscomp_start = $row->workerscomp_start;
	                        $insurance->workerscomp_end = $row->workerscomp_end;
	                        $insurance->workerscomp_filename = 'files/insurance/'.$row->workerscomp_filename;
	                        $insurance->endorsement_filename = 'files/insurance/'.$row->endorsement_filename;
	                    	
	                        $insurance->save();
	                    	
                	}
                });
            });

        });
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
            );

        $today = date("Y-m-d");
        
        if ($tenant->Insurance->endorsement_filename == null) {
            $state["efile"] = "danger";
            $insurance->compliant = false;
        }
        else {
            $state["elink"] = "window.open('/".$tenant->insurance->filepath.$tenant->Insurance->endorsement_filename."')";
        } 
        if ($tenant->Insurance->liability_filename == null) {
            $state["lfile"] = "danger";
            $insurance->compliant = false;
        }
        else {
            $state["llink"] = "window.open('/".$tenant->insurance->filepath.$tenant->Insurance->liability_filename."')";
        }    
        if ($tenant->Insurance->umbrella_filename == null) {
            $state["ufile"] = "danger";
            $insurance->compliant = false;
        }
        else {
            $state["ulink"] = "window.open('/".$tenant->insurance->filepath.$tenant->Insurance->umbrella_filename."')";
        } 
        if ($tenant->Insurance->auto_filename == null) {
            $state["afile"] = "danger";
            $insurance->compliant = false;
        }
        else {
            $state["alink"] = "window.open('/".$tenant->insurance->filepath.$tenant->Insurance->auto_filename."')";
        } 
        if ($tenant->Insurance->workerscomp_filename == null) {
            $state["wfile"] = "danger";
            $insurance->compliant = false;
        }
        else {
            $state["wlink"] = "window.open('/".$tenant->insurance->filepath.$tenant->Insurance->workerscomp_filename."')";
        }    
        if ($tenant->Insurance->liability_end < $today) {
            $state["lexpire"] = "danger";
            $insurance->compliant = false;
        }  
        if ($tenant->Insurance->umbrella_end < $today) {
            $state["uexpire"] = "danger";
            $insurance->compliant = false;
        }
        if ($tenant->Insurance->auto_end < $today) {
            $state["aexpire"] = "danger";
            $insurance->compliant = false;
        }
        if ($tenant->Insurance->workerscomp_end < $today) {
            $state["wexpire"] = "danger";
            $insurance->compliant = false;
        }
        if ($tenant->req_liability_single_limit != null &&  $tenant->req_liability_combined_limit != null  ) {
            if ( $tenant->req_liability_single_limit > $tenant->Insurance->liability_single_limit || $tenant->req_liability_combined_limit > $tenant->Insurance->liability_combined_limit) {
                $state["llimit"] = "danger";
                $insurance->compliant = false;
            }
        }
        elseif ($tenant->Property->req_liability_single_limit > $tenant->Insurance->liability_single_limit  && $tenant->Property->req_liability_combined_limit > $tenant->Insurance->liability_combined_limit) {
            $state["llimit"] = "danger";
            $insurance->compliant = false;
        }
        if ($tenant->req_umbrella_limit != null){
            if ($tenant->req_umbrella_limit > $tenant->Insurance->umbrella_limit) {
                $state["ulimit"] = "danger";
                $insurance->compliant = false;
            }
        }
        elseif ($tenant->Property->req_umbrella_limit > $tenant->Insurance->umbrella_limit) {
            $state["ulimit"] = "danger";
            $insurance->compliant = false;
        }

        if ($tenant->req_auto_limit != null){
            if ($tenant->req_auto_limit > $tenant->Insurance->auto_limit) {
                $state["alimit"] = "danger";
                $insurance->compliant = false;
            }
        }
        elseif ($tenant->Property->req_auto_limit > $tenant->Insurance->auto_limit) {
            $state["alimit"] = "danger";
            $insurance->compliant = false;
        }

        if ($tenant->req_workerscomp_limit != null){
            if ($tenant->req_workerscomp_limit > $tenant->Insurance->workerscomp_limit) {
                $state["wlimit"] = "danger";
                $insurance->compliant = false;
            }
        }
        elseif ($tenant->Property->req_workerscomp_limit > $tenant->Insurance->workerscomp_limit) {
            $state["wlimit"] = "danger";
            $insurance->compliant = false;
        }

        $insurance->save();
        return $state;
    }

    public static function processInsuranceChecks()
    {
    	$tenants = Tenant::all();
    	foreach ($tenants as $tenant) {
    		$state = Helper::insuranceCheck($tenant);
    		if (!$tenant->Insurance->compliant) {
    			
    			echo "$tenant->company_name ";
    			Helper::sendInsuranceNotice($tenant);
    		}
    	}
    	return true;
    }

    public static function sendInsuranceNotice(Tenant $tenant)
    {
    	if ($tenant->insurance_contact_email != null)
    	{
    		$tenant->load('insurance');
    		$token = Str::random(60);
    		$tenant->Insurance->upload_token = $token;
    		$tenant->Insurance->save();
    		Mail::send('email.insurance-notice',compact('tenant', 'token'), function ($message) use ($tenant, $token) {
                $message->from('insurance-admin@davispartners.com', 'Insurance Administrator');
                $message->subject('Insurance Certificate Needs Update');
                $message->to($tenant->insurance_contact_email);
            });
    	}
    }
}