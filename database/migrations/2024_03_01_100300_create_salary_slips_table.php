<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalarySlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('salary_slips')) {
            Schema::create('salary_slips', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->string('salary_per_minute')->nullable();
                $table->string('total_working_hours')->nullable();
                $table->string('advance')->nullable();
                $table->string('loan')->nullable();
                $table->string('total_fund_amount')->nullable();
                $table->string('total_salary')->nullable();
                $table->string('net_salary')->nullable();
                $table->string('cash_in_hand')->nullable();
                $table->string('cash_in_bank')->nullable();
                $table->string('generated_month_year')->nullable();
                $table->string('generated_month')->nullable();
                $table->unsignedBigInteger('payroll_approval_id')->nullable();
                $table->integer('total_present')->nullable();
                $table->integer('total_absent')->nullable();
                $table->integer('total_leave')->nullable();

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
        Schema::dropIfExists('salary_slips');
    }
}
