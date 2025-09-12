<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSkillIdColumnInSkillTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable("skill_types")) {
            if (!Schema::hasColumn('skill_types', 'skill_id')) {
                Schema::table('skill_types', function (Blueprint $table) {
                    $table->unsignedBigInteger('skill_id')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('skill_types')) {
            Schema::table('skill_types', function (Blueprint $table) {
                if (Schema::hasColumn('skill_types', 'skill_id')) {
                    $table->dropColumn('skill_id');
                }
            });
        }
    }
}
