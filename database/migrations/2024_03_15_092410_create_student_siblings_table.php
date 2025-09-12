<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentSiblingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('student_siblings')) {
            Schema::create('student_siblings', function (Blueprint $table) {
                $table->id();
                $table->string('sibling_name')->nullable();
                $table->date('sibling_dob')->nullable();
                $table->string('sibling_gender')->nullable();
                $table->unsignedBigInteger('student_id')->nullable();
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
        Schema::dropIfExists('student_siblings');
    }
}
