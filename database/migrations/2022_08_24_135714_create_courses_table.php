<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_type_id');
            $table->unsignedBigInteger('class_id');
            $table->string('name');
            $table->integer('credit_hours');
            $table->string('subject_code');
            $table->integer('fee');
            $table->integer('status')->default(0);
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('course_type_id')->references('id')->on('coursetypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
