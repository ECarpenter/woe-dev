<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();   
            $table->integer('property_id')->unsigned()->index();
            $table->string('unit');
            $table->string('company_name');
            $table->string('job_title');
            $table->string('tenant_system_id')->unique()->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('verified')->default(false);
            $table->integer('req_liability_single_limit')->nullable();
            $table->integer('req_liability_combined_limit')->nullable();
            $table->integer('req_auto_limit')->nullable();
            $table->integer('req_umbrella_limit')->nullable();
            $table->integer('req_workerscomp_limit')->nullable();
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
        Schema::drop('tenants');
    }
}
