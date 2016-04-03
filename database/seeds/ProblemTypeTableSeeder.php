<?php

use Illuminate\Database\Seeder;

class ProblemTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $problemType = new App\ProblemType;
        $problemType->type = "Plumbing";
        $problemType->save();

        $problemType = new App\ProblemType;
        $problemType->type = "Doors & Keys";
        $problemType->save();

        $problemType = new App\ProblemType;
        $problemType->type = "HVAC";
        $problemType->save();

        $problemType = new App\ProblemType;
        $problemType->type = "Electrical";
        $problemType->save();

        $problemType = new App\ProblemType;
        $problemType->type = "Janitorial";
        $problemType->save();

        $problemType = new App\ProblemType;
        $problemType->type = "Roofing";
        $problemType->save();

        $problemType = new App\ProblemType;
        $problemType->type = "Other";
        $problemType->save();
    }
}
