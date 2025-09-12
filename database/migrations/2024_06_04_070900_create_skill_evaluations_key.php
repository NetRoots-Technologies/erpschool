<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkillEvaluationsKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('skill_evaluations_key')) {

            Schema::create('skill_evaluations_key', function (Blueprint $table) {
                $table->id();
                $table->string('abbr');
                $table->unsignedBigInteger('user_id');
                $table->integer('status')->default(1);
                $table->string('key');
                $table->timestamps();
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
        Schema::dropIfExists('skill_evaluations_key');
    }
}
