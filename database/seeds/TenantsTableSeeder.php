<?php

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
        factory(App\Tenant::class, 10)->create();
    }
}
