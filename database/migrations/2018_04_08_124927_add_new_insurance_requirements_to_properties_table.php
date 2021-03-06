<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewInsuranceRequirementsToPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            //New Values
            $table->string('req_cgl')->nullable();
            $table->string('req_cgl_deductible')->nullable();
            $table->string('req_excess')->nullable();
            $table->string('req_excess_coverage')->nullable();
            $table->string('req_umbrella')->nullable();
            $table->string('req_umbrella_coverage')->nullable();
            $table->string('req_cause_of_loss')->nullable();
            $table->string('req_pollution')->nullable();
            $table->string('req_employers_liability')->nullable();
            $table->string('req_auto_liability')->nullable();
            $table->string('req_auto_liability_coverage')->nullable();
            $table->boolean('req_pollution_amend')->default(false);
            $table->boolean('req_additional_ins_endorsement')->default(false);
            $table->boolean('req_tenants_pp')->default(false);
            $table->boolean('req_tenant_improvements')->default(false);
            $table->boolean('req_tenant_fixtures')->default(false);
            $table->boolean('req_earthquake')->default(false);
            $table->boolean('req_flood')->default(false);
            $table->boolean('req_workers_comp')->default(false);
            $table->boolean('req_business_interruption')->default(false);
            $table->boolean('req_waiver_of_subrogation')->default(false);
            $table->boolean('req_data_endorsement')->default(false);
            $table->text('req_cause_of_loss_detail')->nullable();
            $table->text('note')->nullable();
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
