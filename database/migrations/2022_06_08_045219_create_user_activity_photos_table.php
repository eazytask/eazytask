<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activity_photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('timekeeper_id');
            $table->string('sign_in')->nullable();
            $table->string('sign_out')->nullable();
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_activity_photos');
    }
}
