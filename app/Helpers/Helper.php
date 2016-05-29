<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Excel;

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
}