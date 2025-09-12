<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTableExamDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('exam_details')) {
            Schema::table('exam_details', function (Blueprint $table) {
                // Add exam_term_id if missing
                if (!Schema::hasColumn('exam_details', 'exam_term_id')) {
                    $table->unsignedBigInteger('exam_term_id')->nullable();
                    $table->foreign('exam_term_id')
                          ->references('id')
                          ->on('exam_terms')
                          ->onDelete('cascade');
                }

                // Add test_type_id if missing
                if (!Schema::hasColumn('exam_details', 'test_type_id')) {
                    $table->unsignedBigInteger('test_type_id')->nullable();
                    $table->foreign('test_type_id')
                          ->references('id')
                          ->on('test_types')
                          ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_details', function (Blueprint $table) {
            $table->dropForeign(['exam_term_id']);
            $table->dropColumn('exam_term_id');

            $table->dropForeign(['test_type_id']);
            $table->dropColumn('test_type_id');
        });
    }
}
