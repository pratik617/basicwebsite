<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripPaymentLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_payment_log', function (Blueprint $table) {
            $table->string('trip_id','64');
            $table->integer('customer_id');
            $table->enum('payment_mode',['cash','card','wallet','paypal','mpesa'])->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('payment',['success','pending','fail','other']);
            $table->text('payment_response')->nullable();
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
        Schema::dropIfExists('trip_payment_log');
    }
}
