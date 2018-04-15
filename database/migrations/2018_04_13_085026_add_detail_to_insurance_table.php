<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailToInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurance', function (Blueprint $table) {
            //
            $table->text('note')->nullable();;
            $table->boolean('combined_file')->default(false);;
            $table->string('new_filename2')->nullable();
            $table->string('tempfile2')->nullable();
            $table->boolean('auto_notice')->default(false);
            $table->boolean('expired')->default(true);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insurance', function (Blueprint $table) {
            //
        });
    }
}
