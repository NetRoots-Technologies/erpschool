<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveSessionIdInCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'active_session_id')) {

                $table->unsignedBigInteger('active_session_id')->nullable()->after('session_id');

                // Add foreign key constraint
                $table->foreign('active_session_id')
                    ->references('id')
                    ->on('active_sessions')
                    ->onDelete('set null'); // Optional: you can use 'cascade', 'restrict', etc.
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['active_session_id']);
            $table->dropColumn('active_session_id');
        });
    }
}
