<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnExpiredOnInSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dateTime('expired_on')->after("expire")->nullable();
            $table->enum('is_Active',["0","1"])->after("expire")->default("0"); // If already a subscription is active then it will be 0
            $table->integer('used_days')->after("expire")->default(0); // To count days if plan type is days.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
