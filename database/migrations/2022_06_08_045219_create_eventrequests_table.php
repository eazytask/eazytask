<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventrequests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('event_id')->nullable();
            $table->unsignedInteger('employee_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('company_code');
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('eventrequests');
    }
}
