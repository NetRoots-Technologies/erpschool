<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentPerviousSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('student_pervious_schools')) {
        Schema::create('student_pervious_schools', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->nullable();
            $table->string('school_origin')->nullable();
            $table->string('leaving_reason')->nullable();
            $table->string('local_school_name')->nullable();
            $table->string('local_school_address')->nullable();
            $table->unsignedBigInteger('student_id');
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
        Schema::dropIfExists('student_pervious_schools');
    }
}
