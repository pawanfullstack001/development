<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('driver_id');
            $table->integer('plan_id');
            $table->string('booking_id');
            $table->string('source');
            $table->string('destination');
            $table->string('latitude')->comment('Pickup latititude');
            $table->string('longitude')->comment('Pickup longitude');
            $table->string('destination_lat');
            $table->string('destination_lng');
            $table->dateTime('pickup_time');
            $table->dateTime('boarded_time');
            $table->string('total_time');
            $table->string('taxi_lat');
            $table->string('taxi_lng');
            $table->string('duration')->comment("Minutes until pickup");
            $table->string('distance');
            $table->string('cust_name');
            $table->string('cust_mob');
            $table->string('country');
            $table->float('price',8,2);
            $table->text('driver_notes');
            $table->tinyInteger('rating');
            $table->string('review');
            $table->enum('status',["0","1","2","3","4"])->comment("0-Pending,1-Accepted,2-Rejected,3-Completed,4-Cancelled");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
