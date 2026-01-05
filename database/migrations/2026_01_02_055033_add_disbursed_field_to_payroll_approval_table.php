<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDisbursedFieldToPayrollApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_approval', function (Blueprint $table) {
            $table->tinyInteger('disbursed')->default(0)->after('approved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_approval', function (Blueprint $table) {
            $table->dropColumn('disbursed');
        });
    }
}
