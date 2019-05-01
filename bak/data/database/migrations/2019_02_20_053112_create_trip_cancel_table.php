<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripCancelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_cancel_log', function (Blueprint $table) {
            $table->string('trip_id','64');
            $table->text('reason')->nullable();

            $table->enum('type',['customer','driver','cancel'])->nullable();

            $table->integer('user_id')->comment('if customer cancel customer id, if driver cancel driver id');

            $table->string('latitude','90')->nullable();
            $table->string('longitude','90')->nullable();

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
        Schema::dropIfExists('trip_cancel_log');
    }
}
