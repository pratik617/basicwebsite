<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailableDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_drivers', function (Blueprint $table) {
            $table->biginteger("driver_id")->unique();
            $table->string('latitude',60);
            $table->string('longitude',60);
            $table->string('vehicle_type',30);
            $table->string('vehicle_category',30);
            $table->enum('status',["on","off"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('available_drivers');
    }
}
