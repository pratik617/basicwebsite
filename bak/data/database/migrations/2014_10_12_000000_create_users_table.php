<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email','191')->unique();
            $table->string('country_code','10')->nullable();
            $table->string('contact_no','30')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            
            $table->string('otp','6')->nullable();
            
            // $table->enum('user_type',['admin','company','customer'])->default('customer');
            $table->enum('status',['active','in-active'])->default('active');
            $table->string('facebook_id','60')->nullable();
            $table->string('gmail_id','60')->nullable();
            $table->string('linkedin_id','60')->nullable();
            $table->string('profile','191')->nullable();
            $table->string('password_reset','191')->nullable();
            $table->string('invite_code','20')->unique();
            $table->string('invited_code','20')->nullable()->comment('invited code by other user');

            $table->enum('device_type',['Ios','Android','Other'])->nullable();
            $table->string('device_token')->nullable();

            $table->string('rating','5')->default('0')->nullable();
            
            $table->enum('is_login',['yes','no'])->default('no');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();

            $table->string('socket_id',30)->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
