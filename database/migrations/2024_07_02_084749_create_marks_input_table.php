<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksInputTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('marks_input')) {

            Schema::create('marks_input', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('company')->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreignId('branch_id')->constrained('branches')->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreignId('section_id')->constrained('sections')->cascadeOnUpdate()->cascadeOnDelete();
                $table->unsignedBigInteger('session_id');
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('subject_id');
                $table->unsignedBigInteger('component_id');
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
        Schema::dropIfExists('marks_input');
    }
}
