<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTimeKeepersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('time_keepers', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users');
            $table->foreign(['client_id'])->references(['id'])->on('clients');
            $table->foreign(['job_type_id'])->references(['id'])->on('job_types');
            $table->foreign(['roaster_status_id'])->references(['id'])->on('roaster_statuses');
            $table->foreign(['employee_id'])->references(['id'])->on('employees');
            $table->foreign(['project_id'])->references(['id'])->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('time_keepers', function (Blueprint $table) {
            $table->dropForeign('time_keepers_user_id_foreign');
            $table->dropForeign('time_keepers_client_id_foreign');
            $table->dropForeign('time_keepers_job_type_id_foreign');
            $table->dropForeign('time_keepers_roaster_status_id_foreign');
            $table->dropForeign('time_keepers_employee_id_foreign');
            $table->dropForeign('time_keepers_project_id_foreign');
        });
    }
}
