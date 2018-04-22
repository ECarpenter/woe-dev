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

        $general = new App\Permission;
        $general->name = 'general';
        $general->display_name = 'General Access';
        $general->save();

        $setup = new App\Permission;
        $setup->name = 'advanced-setup';
        $setup->display_name = 'Advanced Setup Access';
        $setup->save();
        
        $all = new App\Permission;
        $all->name = 'view-all';
        $all->display_name = 'View All Properties';
        $all->save();

        $admin->attachPermission($manage);
        $admin->attachPermission($insurance);
        $admin->attachPermission($all);
        $admin->attachPermission($general);
        $admin->attachPermission($setup);
        $manager->attachPermission($manage);
        $manager->attachPermission($general);
        $manager->attachPermission($insurance);        
        $accountant->attachPermission($manage);
        $accountant->attachPermission($general);
        $insuranceadmin->attachPermission($insurance);
        $insuranceadmin->attachPermission($all);
        $insuranceadmin->attachPermission($general);
    }
}
