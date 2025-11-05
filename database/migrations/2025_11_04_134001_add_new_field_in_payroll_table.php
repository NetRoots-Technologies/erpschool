<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldInPayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->string('medicalAllowance')->nullable()->after('net_salary');
            $table->string('fund_values')->nullable()->after('total_fund_amount');
            $table->string('total_late')->nullable()->after('total_leave');
            $table->integer('late_join')->default(0)->after('total_late');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('medicalAllowance');
            $table->dropColumn('fund_values');
            $table->dropColumn('total_late');
            $table->dropColumn('late_join');
        });
    }
}
