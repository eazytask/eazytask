<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('client_name')->nullable();
            $table->string('project_name')->nullable();
            $table->date('roaster_date_from')->nullable();
            $table->date('roaster_date_to')->nullable();
            $table->integer('hours')->nullable();
            $table->integer('rate')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('revenues');
    }
}
