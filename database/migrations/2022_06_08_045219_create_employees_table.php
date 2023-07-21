<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('userID');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname')->nullable();
            $table->string('company');
            $table->string('address')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('email');
            $table->integer('status')->default(1);
            $table->string('contact_number');
            $table->string('date_of_birth')->nullable();
            $table->string('rsa_number')->nullable();
            $table->string('rsa_expire_date')->nullable();
            $table->string('license_no')->nullable();
            $table->string('license_expire_date')->nullable();
            $table->string('image')->nullable();
            $table->string('first_aid_license')->nullable();
            $table->unsignedInteger('user_id')->index();
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
        Schema::dropIfExists('employees');
    }
}
