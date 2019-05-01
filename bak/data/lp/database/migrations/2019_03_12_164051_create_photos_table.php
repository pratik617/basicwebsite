<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id');
            $table->string('area');
            $table->string('floor');
            $table->string('building');
            $table->string('unit');
            $table->string('probe_location');
            $table->string('probe_number');
            $table->string('caption')->nullable();
            $table->string('image_url');
            $table->tinyInteger('orientation')->nullable();
            $table->string('image_data')->nullable();
            $table->tinyInteger('display_order')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamp('captured_at')->nullable();
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
        Schema::dropIfExists('photos');
    }
}
