<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddToWorkordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders','support_file'))
            {
                $table->string('support_file')->nullable();
            }
            if (!Schema::hasColumn('work_orders','urgent'))
            {
                $table->boolean('urgent')->default(false);
            }
            if (!Schema::hasColumn('work_orders','vendor_id'))
            {
                $table->integer('vendor_id')->nullable();
            }
            if (!Schema::hasColumn('work_orders','invoice_number'))
            {
                $table->string('invoice_number')->nullable();
            }

        });

        //\DB::statement('UPDATE work_orders SET urgent = false');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workorders', function (Blueprint $table) {
            //
        });
    }
}
