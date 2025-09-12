<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSubjectMarksChangeCourseReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_marks', function (Blueprint $table) {
            // 1. Drop old FK constraint on course_id
            $table->dropForeign(['course_id']);

            // 2. Recreate course_id referencing courses table
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            // 3. Add exam_detail_id column and foreign key
            $table->unsignedBigInteger('exam_detail_id')->nullable()->after('course_id');
            $table->foreign('exam_detail_id')
                ->references('id')
                ->on('exam_details')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_marks', function (Blueprint $table) {
            Schema::table('subject_marks', function (Blueprint $table) {
                // Drop new FK and column
                $table->dropForeign(['exam_detail_id']);
                $table->dropColumn('exam_detail_id');

                // Drop modified FK on course_id
                $table->dropForeign(['course_id']);

                // Revert course_id back to exam_details
                $table->foreign('course_id')
                    ->references('id')
                    ->on('exam_details')
                    ->onDelete('cascade');
            });
        });
    }
}
