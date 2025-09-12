<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentDatabankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('student_databank')) {

            Schema::create('student_databank', function (Blueprint $table) {
                $table->id();
                $table->string('student_name')->nullable();
                $table->integer('student_age')->nullable();
                $table->string('student_email')->nullable(); //new
                $table->string('gender')->nullable(); //new
                $table->string('student_phone')->nullable(); //new
                $table->string('study_perviously')->nullable(); //new
                $table->string('admission_for')->nullable(); //new
                $table->text('reason_for_leaving')->nullable();
                $table->string('father_name')->nullable();
                $table->string('mother_name')->nullable();
                $table->longText('present_address')->nullable();
                $table->string('landline_number')->nullable();
                $table->longText('previous_school')->nullable();
                $table->longText('reason_of_switch')->nullable();
                $table->tinyInteger('status')->default(1)->nullable();

                $table->softDeletes();
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
        Schema::dropIfExists('student_databank');
    }
}
