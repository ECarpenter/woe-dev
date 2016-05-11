<?php

use Illuminate\Database\Seeder;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Seed test properties
     *
     * @return void
     */
    public function run()
    {
    	//factory(App\Property::class, 5)->create();


    	// Properties Table for Demo
    	 
    	$property1 = new App\Property;
    	$property1->name = "4400 MacArthur";
    	$property1->property_system_id = "f0804601";
    	$property1->address = "4400 MacArthur Blvd.";
        $property1->city = "Newport Beach";
        $property1->state = "CA";
        $property1->zip = "92660";
    	$property1->owner_id = 1;
        $property1->req_liability_single_limit = 1000000;
        $property1->req_liability_combined_limit = 2000000;
        $property1->req_auto_limit = 1000000;
        $property1->req_umbrella_limit = 3000000;
        $property1->req_workerscomp_limit = 1000000;
    	$property1->save();

    	$property2 = new App\Property;
    	$property2->name = "Ontario Business Center";
    	$property2->property_system_id = "kar00201";
    	$property2->address = "820 S. Rockefeller Avenue";
        $property2->city = "Ontario";
        $property2->state = "CA";
        $property2->zip = "91760";
    	$property2->owner_id = 1;
        $property2->req_liability_single_limit = 1000000;
        $property2->req_liability_combined_limit = 2000000;
        $property2->req_auto_limit = 1000000;
        $property2->req_umbrella_limit = 3000000;
        $property2->req_workerscomp_limit = 1000000;
    	$property2->save();
    }
}
