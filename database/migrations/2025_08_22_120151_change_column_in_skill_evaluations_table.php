<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnInSkillEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skill_evaluations', function (Blueprint $table) {
            if (Schema::hasColumn('skill_evaluations', 'company_id')) {
                $table->dropColumn('company_id');
            }
            if (Schema::hasColumn('skill_evaluations', 'branch_id')) {
                $table->dropColumn('branch_id');
            }
            if (Schema::hasColumn('skill_evaluations', 'class_id')) {
                $table->dropColumn('class_id');
            }
            if (Schema::hasColumn('skill_evaluations', 'section_id')) {
                $table->dropColumn('section_id');
            }
            if (Schema::hasColumn('skill_evaluations', 'json_data')) {
                $table->dropColumn('json_data');
            }

            if (!Schema::hasColumn('skill_evaluations', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable();
            }
            if (!Schema::hasColumn('skill_evaluations', 'skill_group_id')) {
                $table->unsignedBigInteger('skill_group_id')->nullable();
            }
            if (!Schema::hasColumn('skill_evaluations', 'skill_id')) {
                $table->unsignedBigInteger('skill_id')->nullable();
            }
            if (!Schema::hasColumn('skill_evaluations', 'skill_evaluation_key_id')) {
                $table->unsignedBigInteger('skill_evaluation_key_id')->nullable();
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
        Schema::table('skill_evaluations', function (Blueprint $table) {
            if (!Schema::hasColumn('skill_evaluations', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable();
            }
            if (!Schema::hasColumn('skill_evaluations', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable();
            }
            if (!Schema::hasColumn('skill_evaluations', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable();
            }
            if (!Schema::hasColumn('skill_evaluations', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable();
            }
            if (!Schema::hasColumn('skill_evaluations', 'json_data')) {
                $table->longText('json_data')->nullable();
            }

            if (Schema::hasColumn('skill_evaluations', 'subject_id')) {
                $table->dropColumn('subject_id');
            }
            if (Schema::hasColumn('skill_evaluations', 'skill_group_id')) {
                $table->dropColumn('skill_group_id');
            }
            if (Schema::hasColumn('skill_evaluations', 'skill_id')) {
                $table->dropColumn('skill_id');
            }
            if (Schema::hasColumn('skill_evaluations', 'skill_evaluation_key_id')) {
                $table->dropColumn('skill_evaluation_key_id');
            }
        });
    }
}
