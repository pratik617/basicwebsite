<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripChargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_charge', function (Blueprint $table) {
            $table->string('trip_id','64');
            $table->enum('type',['trip','taxes','waiting','retain','other'])->comment('charges types');
            $table->decimal('amount',8,2)->nullable();
            $table->string('description');
            $table->enum('action',['plus','minus']);
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
        Schema::dropIfExists('trip_charge');
    }
}
