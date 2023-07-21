<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeKeepersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_keepers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('time_keepers_user_id_foreign');
            $table->string('company_code');
            $table->unsignedBigInteger('employee_id')->index('time_keepers_employee_id_foreign');
            $table->unsignedBigInteger('client_id')->index('time_keepers_client_id_foreign');
            $table->unsignedBigInteger('project_id')->index('time_keepers_project_id_foreign');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->dateTime('roaster_date');
            $table->dateTime('shift_start');
            $table->dateTime('shift_end');
            $table->dateTime('sing_in')->nullable();
            $table->dateTime('sing_out')->nullable();
            $table->decimal('duration', 10);
            $table->decimal('ratePerHour', 10);
            $table->decimal('amount');
            $table->unsignedBigInteger('job_type_id')->index('time_keepers_job_type_id_foreign');
            $table->unsignedBigInteger('roaster_status_id')->index('time_keepers_roaster_status_id_foreign');
            $table->enum('roaster_type', ['Unschedueled', 'Schedueled'])->default('Unschedueled');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->dateTime('Approved_start_datetime');
            $table->dateTime('Approved_end_datetime');
            $table->boolean('payment_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_keepers');
    }
}
