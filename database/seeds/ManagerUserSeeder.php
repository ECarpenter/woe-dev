<?php

use Illuminate\Database\Seeder;
use App\Helpers\Helper;

class ManagerUserSeeder extends Seeder
{
    /**
     *	Seed a test user set as a manager
     *
     * @return void
     */
    public function run()
    {
         Helper::importManager('Managers.xls');
    }
}
