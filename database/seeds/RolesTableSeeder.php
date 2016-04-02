<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
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

    }
}
