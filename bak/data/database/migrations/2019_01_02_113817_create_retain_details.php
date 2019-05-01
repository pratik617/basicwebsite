<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRetainDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retain_details', function (Blueprint $table) {
            
            $table->string('trip_id','64')->primary();
            
            $table->string('pickup_address');
            $table->string('pickup_latitude','90');
            $table->string('pickup_longitude','90');
            
            $table->string('drop_address');
            $table->string('drop_latitude','90');
            $table->string('drop_longitude','90');
            
            $table->timestamp('pickup_time')->nullable();
            $table->timestamp('drop_time')->nullable();

            $table->string('retain_hours','2')->nullable();
            $table->timestamp('retain_time')->nullable();

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
        Schema::dropIfExists('retain_details');
    }
}
