<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('student_pictures')) {

            Schema::create('student_pictures', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id')->nullable();

                $table->string('passport_photos')->nullable();
                $table->string('birth_certificate')->nullable();
                $table->string('school_leaving_certificate')->nullable();
                $table->string('guardian_document')->nullable();
                $table->string('picture_permission')->nullable();

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
        Schema::dropIfExists('student_pictures');
    }
}
