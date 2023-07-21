<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->text('heading');
            $table->longText('text');
            $table->enum('published', ['Y', 'N'])->default('Y');
            $table->enum('need_confirm', ['Y', 'N'])->default('N');
            $table->date('publish_date')->nullable();
            $table->text('list_venue')->nullable();
            $table->timestamps();
        }); 

        Schema::create('message_replies', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('message_id')->nullable();
            $table->longText('text');
            $table->enum('published', ['Y', 'N'])->default('Y');
            $table->date('publish_date')->nullable();
            $table->timestamps();
        });

        Schema::create('message_confirms', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('message_id')->nullable();
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
        Schema::dropIfExists('messages');
    }
};
