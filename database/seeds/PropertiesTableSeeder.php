<?php

use Illuminate\Database\Seeder;

use App\Helpers\Helper;

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
        //Helper::importGroup('/files/Group.xls');
        Helper::importProperty('Property.xls');
    	 
    	
    }
}
