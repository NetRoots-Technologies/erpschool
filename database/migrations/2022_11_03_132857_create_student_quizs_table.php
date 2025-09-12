<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentQuizsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_quizs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('session_id')->nullable();
            $table->integer('cour_id')->nullable();
            $table->integer('teacher_id')->nullable();
            $table->date('quiz_start_date')->nullable();
            $table->date('quiz_end_date')->nullable();
            $table->integer('time')->nullable();
            $table->integer('total_num')->nullable();
            $table->integer('passing_marks')->nullable();

            $table->tinyInteger('publish_status')->nullable();
            $table->tinyInteger('student_status')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_quizs');
    }
}
