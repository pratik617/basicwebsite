<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companys', function (Blueprint $table) {
            $table->bigincrements('id');
            
            $table->string('name')->nullable();
            $table->string('email','191')->unique();
            $table->string('country_code','10')->nullable();
            $table->string('contact_no','30')->unique()->nullable();
            $table->string('city');
            $table->string('address')->nullable();
            $table->enum('status',['active','in-active'])->default('active');
            $table->string('logo')->nullable();

            $table->biginteger('created_by')->unsigned()->nullable();
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
        Schema::dropIfExists('companys');
    }
}
