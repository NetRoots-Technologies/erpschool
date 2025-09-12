<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_approval_id')->nullable();
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
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('payrolls');
    }
}
