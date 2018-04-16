<?php

use App\Helpers\Helper;
use App\Tenant;
use Illuminate\Database\Seeder;

class TenantsTableSeeder extends Seeder
{
    /**
     * Seed sample tenants
     *
     * @return void
     */
    public function run()
    {
        Helper::importTenant('Tenant-testing.xls');
        $tenants = Tenant::all();
        Helper::processInsuranceChecks($tenants);
        //Helper::importInsurance('Insurance.xls');
    }
}
