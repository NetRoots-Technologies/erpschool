<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicEvaluationsKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('academic_evaluations_key')) {
            Schema::create('academic_evaluations_key', function (Blueprint $table) {
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
        Schema::dropIfExists('academic_evaluations_key');
    }
}
