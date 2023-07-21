<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyavailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('myavailabilities', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedInteger('user_id')->nullable();
            $table->string('company_code')->nullable();
            $table->unsignedInteger('employee_id')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('myavailabilities');
    }
}
