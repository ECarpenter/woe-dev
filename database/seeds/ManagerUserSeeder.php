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

        $role = DB::table('roles')->where('name', '=', 'manager')->pluck('id');
       
        $prop = App\Property::find(1);
        $prop2 = App\Property::find(2);

        //create first test manager 
        $user = new App\User;
        $user->name = "Sondra Valentine";
        $user->email = "sondra.valentine@example.com";
        $user->password = bcrypt('password');
        $user->timezone = "America/Los_Angeles";
        $user->save();    
		
		$user->Roles()->attach($role);
	    $user->Properties()->attach($prop);

        //add second manager

        $user2 = new App\User;
        $user2->name = "Tina Minook";
        $user2->email = "tina.minook@example.com";
        $user2->password = bcrypt('password');
        $user2->timezone = "America/Los_Angeles";
        $user2->save();    
        
        $user2->Roles()->attach($role);
        $user2->Properties()->attach($prop2);
    }
}
