<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed a test admin
     *
     * @return void
     */
    public function run()
    {
        $user = new App\User;
        $user->name = "admin";
        $user->email = "admin@ejcustom.com";
        $user->password = bcrypt('password');
        $user->timezone = "America/Los_Angeles";
        $user->save();

        $role = DB::table('roles')->where('name', '=', 'admin')->pluck('id');
        $user->Roles()->attach($role);

        $insuranceadmin = new App\User;
        $insuranceadmin->name = "Insurance Administrator";
        $insuranceadmin->email = "insurance@davispartners.com";
        $insuranceadmin->password = bcrypt('insur@nce2016');
        $insuranceadmin->timezone = "America/Los_Angeles";
        $insuranceadmin->save();

        $role = DB::table('roles')->where('name', '=', 'insurance-admin')->pluck('id');
        $insuranceadmin->Roles()->attach($role);
    }
}
