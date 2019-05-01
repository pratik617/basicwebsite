<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeContactLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_contact_log', function (Blueprint $table) {
            $table->enum('user_type',['customer','driver']);
            $table->integer('user_id');
            $table->string('country_code','6')->nullable();
            $table->string('new_contact_no','20')->nullable();
            $table->string('otp','6')->nullable();
            $table->enum('status',['verified','notverified'])->default('notverified');
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
        Schema::dropIfExists('change_contact_log');
    }
}
