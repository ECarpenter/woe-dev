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
    	factory(App\Property::class, 5)->create();

    }
}
