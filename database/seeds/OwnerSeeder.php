<?php

use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $owner = new App\Owner;
        $owner->name = "TA";
        $owner->save();
    }
}