<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldInSalarySlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->string('committedTime')->nullable()->after('total_working_hours');
            $table->string('medicalAllowance')->nullable()->after('net_salary');
            $table->string('fund_values')->nullable()->after('payroll_approval_id');
            $table->string('total_late')->nullable()->after('total_leave');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dropColumn('committedTime');
            $table->dropColumn('medicalAllowance');
            $table->dropColumn('fund_values');
           
        });
    }
}
