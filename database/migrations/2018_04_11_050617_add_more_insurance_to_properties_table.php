<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreInsuranceToPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            //
            //
            $table->boolean('req_workers_comp')->default('false');
            $table->boolean('req_business_interruption')->default('false');
            $table->boolean('req_waiver_of_subrogation')->default('false');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            //
        });
    }
}
