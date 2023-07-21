<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('Payment_ID')->index('payment_details_payment_id_foreign');
            $table->unsignedBigInteger('Timekeeper_ID')->index('payment_details_timekeeper_id_foreign');
            $table->unsignedBigInteger('Client_ID')->index('payment_details_client_id_foreign');
            $table->unsignedBigInteger('Project_ID')->index('payment_details_project_id_foreign');
            $table->string('Roaster_Date');
            $table->dateTime('Approved_start_datetime');
            $table->dateTime('Approved_end_datetime');
            $table->decimal('App_Duration', 10);
            $table->decimal('App_Rate', 10);
            $table->decimal('Other_Pay', 10)->nullable();
            $table->decimal('Approved_Amount', 10);
            $table->decimal('Total_Pay', 10);
            $table->string('Remarks')->nullable();
            $table->timestamps();
            $table->string('PaymentMethod', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
}
