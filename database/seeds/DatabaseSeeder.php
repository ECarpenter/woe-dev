<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    

    public function run()
    {
    	Model::unguard();
        
        $this->call('OwnerSeeder');
        $this->call('ChargeCodeSeeder');
        $this->call('RolesTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('TenantsTableSeeder');
        $this->call('PropertiesTableSeeder');
        $this->call('ManagerUserSeeder');
        $this->call('ManagerUserSeeder');
        $this->call('ManagerUserSeeder');
        $this->call('ManagerUserSeeder');
        $this->call('ManagerUserSeeder');
        $this->call('ProblemTypeTableSeeder');


        Model::reguard();
    }

    
}
