<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('students')) {

            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('admission_class')->nullable();
                $table->date('admission_date')->nullable();
                $table->string('campus')->nullable();
                $table->string('special_child')->nullable();
                $table->string('special_needs')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('father_name')->nullable();
                $table->string('father_cnic')->nullable();
                $table->tinyInteger('is_guardian')->default(0);
                $table->string('guardian_name')->nullable();
                $table->string('guardian_cnic')->nullable();
                $table->string('gender')->nullable();
                $table->date('student_dob')->nullable();
                $table->string('student_current_address')->nullable();
                $table->string('student_permanent_address')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->nullable();
                $table->string('cell_no')->nullable();
                $table->string('landline')->nullable();
                $table->string('student_email')->nullable();
                $table->string('native_language')->nullable();
                $table->string('first_language')->nullable();
                $table->string('second_language')->nullable();
                $table->string('meal_option')->nullable();
                $table->tinyInteger('easy_urdu')->default(0);
                $table->string('student_id')->nullable();
                $table->unsignedBigInteger('class_id')->nullable();
                $table->unsignedBigInteger('session_id')->nullable();
                $table->unsignedBigInteger('section_id')->nullable();
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('leave_date')->nullable();
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
        Schema::dropIfExists('students');
    }
}
