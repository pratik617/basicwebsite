<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_details', function (Blueprint $table) {
            // $table->bigIncrements('id');
            // vehicle type
            $table->string('key','64')->primary();
            $table->biginteger('driver_id')->nullable();
            $table->biginteger('customer_id')->unsigned();
            $table->string('pickup_address');
            $table->string('pickup_latitude','90');
            $table->string('pickup_longitude','90');
            $table->string('drop_address');
            $table->string('drop_latitude','90');
            $table->string('drop_longitude','90');
            $table->enum('status',['finding','booked','ongoing','complete','cancle']);
            $table->enum('retain',['yes','no'])->default('no');
            
            $table->enum('travel_with_friend',['yes','no'])->default('no');

            $table->integer('vehicle_type_id')->unsigned()->nullable();
            $table->integer('vehicle_category_id')->unsigned()->nullable();

            $table->string('fare_distance','10')->nullable();
            $table->string('fare_unit','5')->nullable();
            $table->string('fare_duration','20')->nullable();
            $table->string('currency_code','3')->nullable();

            $table->enum('payment_mode',['cash','card','wallet','paypal','mpesa'])->nullable();
            $table->enum('payment',['success','pending','fail'])->default('pending');

            $table->string('invoice','64')->nullable();
            $table->string('payment_id','64')->nullable();

            $table->timestamp('pickup_time')->nullable();
            $table->timestamp('drop_time')->nullable();
            $table->string('map_image','200')->nullable();
            
            $table->float('total_price',8, 2)->nullable();
            $table->float('total_distance',8, 2)->nullable();
            $table->string('promo_code',20)->nullable();
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
        Schema::dropIfExists('trip_details');
    }
}
