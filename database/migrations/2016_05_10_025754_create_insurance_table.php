<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id');
            $table->string('new_filename')->nullable();
            $table->string('liability_filename')->nullable();
            $table->date('liability_start')->nullable();
            $table->date('liability_end')->nullable();
            $table->integer('liability_single_limit')->nullable();
            $table->integer('liability_combined_limit')->nullable();
            $table->string('umbrella_filename')->nullable();
            $table->date('umbrella_start')->nullable();
            $table->date('umbrella_end')->nullable();
            $table->integer('umbrella_limit')->nullable();
            $table->string('auto_filename')->nullable();
            $table->date('auto_start')->nullable();
            $table->date('auto_end')->nullable();
            $table->integer('auto_limit')->nullable();
            $table->string('workerscomp_filename')->nullable();
            $table->date('workerscomp_start')->nullable();
            $table->date('workerscomp_end')->nullable();
            $table->integer('workerscomp_limit')->nullable();
            $table->boolean('compliant')->default(true);
            $table->integer('notice_count')->default(0);
            $table->date('last_notice_sent')->nullable();
            $table->string('endorsement_filename')->nullable();
            $table->string('tempfile')->nullable();
            $table->string('upload_token')->nullable();
            $table->string('rejection_msg')->nullable();
            $table->string('filepath')->default("files/insurance/");
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
        Schema::drop('insurance');
    }
}
