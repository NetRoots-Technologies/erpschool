<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('students_fee')) {

            Schema::create('students_fee', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('session_id');
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('class_id');
                $table->double('total_monthly_amount')->nullable();
                $table->unsignedBigInteger('student_id');
                $table->double('discount_rupees')->nullable();
                $table->double('claim_1')->nullable();
                $table->double('discount_percent')->nullable();
                $table->double('claim_2')->nullable();
                $table->double('total_amount_after_discount')->nullable();
                $table->string('generated_month')->nullable();
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
        Schema::dropIfExists('students_fee');
    }
}
