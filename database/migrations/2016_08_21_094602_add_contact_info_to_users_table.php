<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactInfoToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users','phone'))
            {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users','fax'))
            {
                $table->string('fax')->nullable();
            }
            if (!Schema::hasColumn('users','address'))
            {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('users','city'))
            {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('users','state'))
            {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('users','zip'))
            {
                $table->string('zip')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            
        });
    }
}
