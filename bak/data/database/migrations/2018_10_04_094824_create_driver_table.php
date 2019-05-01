<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email','191')->unique();
            $table->string('country_code','10')->nullable();
            $table->string('contact_no','30')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('address')->nullable();
            $table->string('otp','6')->nullable();
            $table->string('profile')->nullable();
            $table->string('invite_code','20')->nullable();
            $table->string('invited_code','20')->nullable()->comment('invited code by other user');
            $table->integer('company_id')->nullable();
            $table->enum('driving_status',['online','offline','on_ride'])->nullable();
            $table->enum('status',['inprocess','pending','active','inactive']);
            $table->string('password_reset','191')->nullable();
            // vehicle type
            $table->string('vehicle_number','30')->nullable();
            $table->string('vehicle_type','30')->nullable();
            $table->string('vehicle_category','30')->nullable();
            $table->string('licence_number','30')->nullable();
            $table->string('document')->nullable();

            $table->enum('device_type',['Ios','Android','Other'])->nullable();
            $table->string('device_token')->nullable();

            $table->string('rating','5')->default('0')->nullable();

            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();

            $table->string('socket_id',30)->nullable();
            
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
        Schema::dropIfExists('drivers');
    }
}
