<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpcomingeventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upcomingevents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedInteger('client_name')->nullable();
            $table->unsignedInteger('project_name')->nullable();
            $table->unsignedInteger('job_type_name');
            $table->date('event_date')->nullable();
            $table->dateTime('shift_start')->nullable();
            $table->dateTime('shift_end')->nullable();
            $table->integer('rate')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('upcomingevents');
    }
}
