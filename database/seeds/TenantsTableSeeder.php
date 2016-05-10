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
        factory(App\Tenant::class, 20)->create()->each(function($t) {
        	App\Insurance::create([
        			'tenant_id' => $t->id,
        		]);
        });
    }
}
