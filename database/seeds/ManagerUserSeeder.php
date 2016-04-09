<?php

use Illuminate\Database\Seeder;

class ManagerUserSeeder extends Seeder
{
    /**
     *	Seed a test user set as a manager
     *
     * @return void
     */
    public function run()
    {
        $user = factory(App\User::class)->create();
	    

	    $role = DB::table('roles')->where('name', '=', 'manager')->pluck('id');
	    

	    $prop = App\Property::first();
		
		$user->Roles()->attach($role);
	    $user->Properties()->attach($prop);
    }
}
