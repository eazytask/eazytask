<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoasterTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roaster_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('roaster_id')->index();
            $table->string('roaster_type')->default('Unschedueled');
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
        Schema::dropIfExists('roaster_types');
    }
}
