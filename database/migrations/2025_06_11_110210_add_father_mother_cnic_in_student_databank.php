<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFatherMotherCnicInStudentDatabank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('student_databank', function (Blueprint $table) {
            if (!Schema::hasColumn('student_databank', 'father_cnic')) {
                $table->string('father_cnic', 15)->after('father_name');
            }
            if (!Schema::hasColumn('student_databank', 'mother_cnic')) {
                $table->string('mother_cnic', 15)->after('mother_name');
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
            $table->dropColumn(['father_cnic', 'mother_cnic']);
        });
    }
}
