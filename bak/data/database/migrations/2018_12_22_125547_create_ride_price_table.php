<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRidePriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ride_price', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_type_id')->nullable();
            $table->integer('vehicle_catehory_id')->nullable();
            // $table->enum('unit',['KM','MILES','Other'])->nullable();
            $table->decimal('km',8,2)->nullable();
            $table->decimal('miles',8,2)->nullable();
            // $table->decimal('price',8,2)->nullable();
            $table->decimal('perminute',8,2)->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            
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
        Schema::dropIfExists('ride_price');
    }
}
