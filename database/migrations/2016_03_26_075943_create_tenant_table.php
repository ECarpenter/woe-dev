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
            $table->integer('user_id')->unsigned()->index()->nullable();   
            $table->integer('property_id')->unsigned()->index();
            $table->string('unit');
            $table->string('company_name');
            $table->string('tenant_system_id')->unique();
            $table->boolean('active')->default(true);
            $table->boolean('test')->default(false);
            $table->string('insurance_contact_email')->default(0);
            $table->integer('req_liability_single_limit')->default(0);
            $table->integer('req_liability_combined_limit')->default(0);
            $table->integer('req_auto_limit')->default(0);
            $table->integer('req_umbrella_limit')->default(0);
            $table->integer('req_workerscomp_limit')->default(0);
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
