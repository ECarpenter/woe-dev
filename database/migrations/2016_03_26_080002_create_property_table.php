<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('property_system_id')->unique();
            $table->string('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('insured_name')->nullable();
            $table->integer('owner_id')->unsigned();
            $table->boolean('active')->default(true);
            $table->boolean('allow_workorder')->default(true);
            $table->integer('req_liability_single_limit');
            $table->integer('req_liability_combined_limit');
            $table->integer('req_auto_limit');
            $table->integer('req_umbrella_limit');
            $table->integer('req_workerscomp_limit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('properties');
    }
}
