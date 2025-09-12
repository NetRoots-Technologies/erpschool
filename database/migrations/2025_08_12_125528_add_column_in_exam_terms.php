<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInExamTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable("exam_terms")) {
            Schema::table('exam_terms', function (Blueprint $table) {
                if (!Schema::hasColumn('exam_terms', 'coordinator_1')) {
                    $table->string('coordinator_1')->nullable();
                }
                if (!Schema::hasColumn('exam_terms', 'staff_id_1')) {
                    $table->integer('staff_id_1')->nullable();
                }
                if (!Schema::hasColumn('exam_terms', 'coordinator_2')) {
                    $table->string('coordinator_2')->nullable();
                }
                if (!Schema::hasColumn('exam_terms', 'staff_id_2')) {
                    $table->integer('staff_id_2')->nullable();
                }
                if (!Schema::hasColumn('exam_terms', 'coordinator_3')) {
                    $table->string('coordinator_3')->nullable();
                }
                if (!Schema::hasColumn('exam_terms', 'staff_id_3')) {
                    $table->integer('staff_id_3')->nullable();
                }
                if (!Schema::hasColumn('exam_terms', 'coordinator_4')) {
                    $table->string('coordinator_4')->nullable();
                }
                if (!Schema::hasColumn('exam_terms', 'staff_id_4')) {
                    $table->integer('staff_id_4')->nullable();
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
        if (Schema::hasTable("exam_terms")) {
            Schema::table('exam_terms', function (Blueprint $table) {
                if (Schema::hasColumn('exam_terms', 'coordinator_1')) {
                    $table->dropColumn('coordinator_1');
                }
                if (Schema::hasColumn('exam_terms', 'staff_id_1')) {
                    $table->dropColumn('staff_id_1');
                }
                if (Schema::hasColumn('exam_terms', 'coordinator_2')) {
                    $table->dropColumn('coordinator_2');
                }
                if (Schema::hasColumn('exam_terms', 'staff_id_2')) {
                    $table->dropColumn('staff_id_2');
                }
                if (Schema::hasColumn('exam_terms', 'coordinator_3')) {
                    $table->dropColumn('coordinator_3');
                }
                if (Schema::hasColumn('exam_terms', 'staff_id_3')) {
                    $table->dropColumn('staff_id_3');
                }
                if (Schema::hasColumn('exam_terms', 'coordinator_4')) {
                    $table->dropColumn('coordinator_4');
                }
                if (Schema::hasColumn('exam_terms', 'staff_id_4')) {
                    $table->dropColumn('staff_id_4');
                }
            });
        }
    }
}
