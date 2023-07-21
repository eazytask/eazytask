<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('Payment_Date');
            $table->unsignedBigInteger('User_ID')->index('payment_master_user_id_foreign');
            $table->unsignedBigInteger('Employee_ID')->index('payment_master_employee_id_foreign');
            $table->string('Company_Code');
            $table->string('Comments')->nullable();
            $table->timestamps();
            $table->string('ExtraDsscription', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_master');
    }
}
