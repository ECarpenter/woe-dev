<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Seed roles
     *
     * @return void
     */
    public function run()
    {
        $admin = new App\Role;
        $admin->name = "admin";
        $admin->save();

        $tenant = new App\Role;
        $tenant->name = "tenant";
        $tenant->save();

        $manager = new App\Role;
        $manager->name = "manager";
        $manager->save();

        $accountant = new App\Role;
        $accountant->name = "accountant";
        $accountant->save();

        $insuranceadmin = new App\Role;
        $insuranceadmin->name = "insurance-admin";
        $insuranceadmin->save();

        $manage = new App\Permission;
        $manage->name = 'manage-wo';
        $manage->display_name = 'Manage Work Orders';
        $manage->save();

        $insurance = new App\Permission;
        $insurance->name = 'manage-insurance';
        $insurance->display_name = 'Manage Insurance';
        $insurance->save();

        $admin->attachPermission($manage);
        $admin->attachPermission($insurance);
        $manager->attachPermission($manage);
        $accountant->attachPermission($manage);
        $insuranceadmin->attachPermission($insurance);

    }
}
