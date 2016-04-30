<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tenant_id')->unsigned()->index();
            $table->integer('problem_id')->unsigned()->index();
            $table->text('description');
            $table->string('status');
            $table->string('cos_filename')->nullable();
            $table->string('vendor_invoice_filename')->nullable();
            $table->string('tenant_invoice_filename')->nullable();
            $table->text('manager_notes')->nullable();
            $table->string('billing_description')->nullable();
            $table->decimal('job_cost',18,2)->nullable();
            $table->decimal('amount_billed',18,2)->nullable();
            $table->boolean('billed')->default(false);
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
        Schema::drop('work_orders');
    }
}
