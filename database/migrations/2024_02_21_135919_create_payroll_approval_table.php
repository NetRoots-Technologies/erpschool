<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_approval', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hrm_employee_id')->nullable();
            $table->unsignedBigInteger('payroll_approval_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('generated_month_year')->nullable();
            $table->string('generated_month')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->integer('approved')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_approval');
    }
}
