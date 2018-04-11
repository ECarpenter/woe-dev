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
            //make old limit values nullable
            $table->integer('req_liability_single_limit')->nullable()->change();
            $table->integer('req_liability_combined_limit')->nullable()->change();
            $table->integer('req_auto_limit')->nullable()->change();
            $table->integer('req_umbrella_limit')->nullable()->change();
            $table->integer('req_workerscomp_limit')->nullable()->change();
            //New Values
            $table->string('req_cgl')->nullable();
            $table->string('req_cgl_deducatible')->nullable();
            $table->string('req_excess')->nullable();
            $table->string('req_excess_coverage')->nullable();
            $table->string('req_umbrella')->nullable();
            $table->string('req_umbrella_coverage')->nullable();
            $table->string('req_cause_of_loss')->nullable();
            $table->string('req_pollution')->nullable();
            $table->string('req_employers_liability')->nullable();
            $table->string('req_auto_liability')->nullable();
            $table->string('req_auto_liability_coverage')->nullable();
            $table->boolean('req_pollution_amend')->default('false');
            $table->boolean('req_additional_ins_endorsement')->default('false');
            $table->boolean('req_tenants_pp')->default('false');
            $table->boolean('req_tenant_improvements')->default('false');
            $table->boolean('req_tenant_fixtures')->default('false');
            $table->boolean('req_earthquake')->default('false');
            $table->boolean('req_flood')->default('false');
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
            $table->integer('req_liability_single_limit')->nullable(false)->change();
            $table->integer('req_liability_combined_limit')->nullable(false)->change();
            $table->integer('req_auto_limit')->nullable(false)->change();
            $table->integer('req_umbrella_limit')->nullable(false)->change();
            $table->integer('req_workerscomp_limit')->nullable(false)->change();
        });
    }
}
