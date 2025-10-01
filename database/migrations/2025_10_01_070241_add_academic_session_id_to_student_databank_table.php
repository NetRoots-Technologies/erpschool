<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcademicSessionIdToStudentDatabankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_databank', function (Blueprint $table) {
            if (!Schema::hasColumn('student_databank', 'academic_session_id')) {
                $table->unsignedBigInteger('academic_session_id')->nullable()->after('reason_of_switch');

                // Uncomment below if you want to add foreign key constraint
                // $table->foreign('academic_session_id')
                //       ->references('id')->on('academic_sessions')
                //       ->onDelete('set null');
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
        Schema::table('student_databank', function (Blueprint $table) {
            // Uncomment this line if you added the foreign key constraint above
            // $table->dropForeign(['academic_session_id']);

            $table->dropColumn('academic_session_id');
        });
    }
}
