<?php

use Illuminate\Database\Seeder;

class ChargeCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cc = new App\ChargeCode;
        $cc->name = 'Tenant Billback';
        $cc->code = 'ctenbill';
        $cc->owner_id = 1;
        $cc-save();

        $cc = new App\ChargeCode;
        $cc->name = 'Misc/Other';
        $cc->code = 'cmisc';
        $cc->owner_id = 1;
        $cc-save();
    }
}
