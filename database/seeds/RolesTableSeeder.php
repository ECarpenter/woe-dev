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

        $insurance = new App\Role;
        $insurance->name = "insurance";
        $insurance->save();

        $manage = new App\Permission;
        $manage->name = 'manage-wo';
        $manage->display_name = 'Manage Work Orders';
        $manage->save();

        $admin->attachPermission($manage);
        $manager->attachPermission($manage);
        $accountant->attachPermission($manage);
        $insurance->attachPermission($manage);

    }
}
