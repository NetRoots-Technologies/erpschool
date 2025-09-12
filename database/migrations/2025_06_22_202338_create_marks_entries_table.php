<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('marks_entries')) {

            Schema::create('marks_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mark_input_id')
                    ->constrained('mark_inputs')
                    ->cascadeOnDelete();
                $table->foreignId('student_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('max_marks');
                $table->unsignedInteger('allocated_marks');
                $table->timestamps();
                $table->unique(['mark_input_id', 'student_id'], 'markinput_student_unique');
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
        Schema::dropIfExists('marks_entries');
    }
}
