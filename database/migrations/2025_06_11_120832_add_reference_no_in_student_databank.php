<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferenceNoInStudentDatabank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_databank', function (Blueprint $table) {
            if (!Schema::hasColumn('student_databank', 'reference_no')) {
                $table->string('reference_no')->after('id')->nullable()->unique();
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
            $table->dropColumn('reference_no');
        });
    }
}
