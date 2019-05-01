<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countrys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name','80');
            $table->string('phone_code','8');
            $table->string('code_2','2');
            $table->string('code_3','3');
            $table->enum('unit',['KM','MILES'])->default('KM');
            $table->string('currency_code','3');
            $table->string('currency_sign','2');
            $table->string('utc_diff',30);
            $table->enum('status',['active','in-active'])->default('active');
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
        Schema::dropIfExists('countrys');
    }
}
