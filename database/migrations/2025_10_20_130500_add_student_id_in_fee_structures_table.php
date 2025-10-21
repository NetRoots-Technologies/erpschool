<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentIdInFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_structures', function (Blueprint $table) {
             $table->unsignedBigInteger('student_id')->index()->nullable()->after('academic_class_id');
             $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
             $table->decimal('final_amount' , 10,2)->default(0)->after('student_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropColumn('student_id');
            $table->dropColumn('final_amount');
            
        });
    }
}
