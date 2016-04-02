<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new App\User;
        $user->name = "admin";
        $user->email = "ec@ade.com";
        $user->password = bcrypt('password');
        $user->save();

        $role = DB::table('roles')->where('name', '=', 'admin')->pluck('id');
        $user->Roles()->attach($role);
    }
}
