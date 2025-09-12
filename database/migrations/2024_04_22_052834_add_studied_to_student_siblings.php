<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudiedToStudentSiblings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_siblings', function (Blueprint $table) {
            if (!Schema::hasColumn('student_siblings', 'studied')) {
                $table->string('studied');
            }
            if (!Schema::hasColumn('student_siblings', 'class_id')) {
                $table->unsignedBigInteger('class_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_siblings', function (Blueprint $table) {
            //
        });
    }
}
