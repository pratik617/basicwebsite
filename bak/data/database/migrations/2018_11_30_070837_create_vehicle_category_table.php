<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_categorys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_type_id');
            $table->string('name','60');
            $table->enum('status',['active','in-active'])->default('active');
            $table->tinyint('min_person_capacity');
            $table->tinyint('max_person_capacity');

            $table->biginteger('created_by')->unsigned();
            $table->biginteger('updated_by')->unsigned()->nullable();
            $table->biginteger('deleted_by')->unsigned()->nullable();
            
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
        Schema::dropIfExists('vehicle_categorys');
    }
}
