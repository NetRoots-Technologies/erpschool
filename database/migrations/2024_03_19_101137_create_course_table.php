<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('course')) {

            Schema::create('course', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_type_id');
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('session_id');
                $table->string('name');
                $table->string('subject_code');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('course');
    }
}
