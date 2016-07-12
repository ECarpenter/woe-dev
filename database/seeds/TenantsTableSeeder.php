<?php

use App\Helpers\Helper;
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
        Helper::processInsuranceChecks();
        //Helper::importInsurance('Insurance.xls');
    }
}
