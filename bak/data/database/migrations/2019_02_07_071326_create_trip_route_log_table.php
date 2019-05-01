<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripRouteLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_route_log', function (Blueprint $table) {

            $table->string('trip_id','64');
            $table->integer('driver_id');
            $table->integer('customer_id');

            $table->string('latitude','90');
            $table->string('longitude','90');

            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_route_log');
    }
}
