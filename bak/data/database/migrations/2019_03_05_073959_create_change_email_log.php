<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeEmailLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_email_log', function (Blueprint $table) {
            $table->enum('user_type',['customer','driver']);
            $table->integer('user_id');
            $table->string('email')->nullable();
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
        Schema::dropIfExists('change_email_log');
    }
}
