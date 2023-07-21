<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('cname');
            $table->string('cemail');
            $table->string('cnumber');
            $table->string('cstate');
            $table->string('caddress');
            $table->string('cperson');
            $table->string('cpostal_code');
            $table->string('cimage')->nullable();
            $table->integer('status')->default(1);
            $table->string('company_code')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
