<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBFormNoInStudentDatabank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_databank', function (Blueprint $table) {
            if (!Schema::hasColumn('student_databank', 'mother_cnic')) {
                $table->string('b_form_no')->after('mother_cnic');
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
        Schema::table('student_databank', function (Blueprint $table) {
            $table->dropColumn('b_form_no');
        });
    }
}
