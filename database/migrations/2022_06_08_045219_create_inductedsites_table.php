<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInductedsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inductedsites', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedInteger('employee_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('company_code')->nullable();
            $table->date('induction_date')->nullable();
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
        Schema::dropIfExists('inductedsites');
    }
}
