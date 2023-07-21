<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('pName');
            $table->string('cName')->nullable();
            $table->string('Status')->default(1);
            $table->string('cNumber')->nullable();
            $table->string('clientName')->nullable();
            $table->string('project_address')->nullable();
            $table->string('project_state')->nullable();
            $table->string('project_venue')->nullable();
            $table->string('company_code');
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
        Schema::dropIfExists('projects');
    }
}
