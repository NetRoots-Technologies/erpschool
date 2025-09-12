<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('mark_inputs')) {

            Schema::create('mark_inputs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('company')->cascadeOnDelete();
                // $table->foreignId('session_id')->constrained('sessions')->cascadeOnDelete();
                $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
                $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
                $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
                // $table->foreignId('subject_id')->constrained('class_subjects')->cascadeOnDelete();
                $table->foreignId('component_id')->constrained('components')->cascadeOnDelete();

                $table->unsignedBigInteger('sub_component_id');
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
        Schema::dropIfExists('mark_inputs');
    }
}
