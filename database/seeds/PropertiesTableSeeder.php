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
    	 $property1->name = "4400 McArthur";
    	 $property1->property_system_id = "f0804601";
    	 $property1->address = "4400 McArthur Blvd.";
         $property1->city = "Newport Beach";
         $property1->state = "CA";
         $property1->zip = 90670;
    	 $property1->owner_id = 1;
    	 $property1->save();

    	 $property2 = new App\Property;
    	 $property2->name = "Arbor Courtyard";
    	 $property2->property_system_id = "f0804101";
    	 $property2->address = "45 Arbor Courtyard";
         $property1->city = "El Monte";
         $property1->state = "CA";
         $property1->zip = 90740;
    	 $property2->owner_id = 1;
    	 $property2->save();
    }
}
