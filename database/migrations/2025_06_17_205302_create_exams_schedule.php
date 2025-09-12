<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('exams_schedule')) {

            Schema::create('exams_schedule', function (Blueprint $table) {
                $table->id();
                //Top Form Columns 
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('branch_id');

                $table->unsignedBigInteger('test_type_id');
                $table->unsignedBigInteger('class_id');


                //Bottom Level Columns

                // $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('component_id');
                // Data fields
                $table->integer('marks')->nullable();
                $table->boolean('grade')->default(false);
                $table->boolean('pass')->default(false);


                // Foreign key constraints (optional)
                $table->foreign('company_id')->references('id')->on('company');
                $table->foreign('branch_id')->references('id')->on('branches');

                // ✅ Correct foreign key (matches exam_terms.id)
                $table->unsignedBigInteger('exam_term_id');
                $table->foreign('exam_term_id')->references('id')->on('exam_terms')->onDelete('cascade');


                $table->foreign('test_type_id')->references('id')->on('test_types');
                $table->foreign('class_id')->references('id')->on('classes');
                // $table->foreign('subject_id')->references('id')->on('class_subjects');
                $table->foreign('component_id')->references('id')->on('components');
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
        Schema::dropIfExists('exams_schedule');
    }
}
