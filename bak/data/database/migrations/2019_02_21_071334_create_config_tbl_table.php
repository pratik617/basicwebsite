<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigTblTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_tbl', function (Blueprint $table) {
            $table->increments('id');
            $table->string('map_key')->nullable()->comment('google map api key');
            $table->tinyInteger('radius')->nullable()->comment('near by with in radius display');
            $table->tinyInteger('near_count')->nullable()->comment('near by cab count display');
            $table->timestamps();
            $table->biginteger('updated_by')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_tbl');
    }
}
