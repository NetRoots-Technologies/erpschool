<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_streamings', function (Blueprint $table) {
            $table->id();
            $table->integer('session_id');
            $table->integer('teacher_id')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->string('vimeo_id')->nullable();
            $table->date('link_start_date')->nullable();
            $table->date('link_end_date')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('streaming_link');
            $table->string('title');
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
        Schema::dropIfExists('live_streamings');
    }
};
