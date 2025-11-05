<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInPayrollApprovedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_approval', function (Blueprint $table) {
         $table->unsignedBigInteger('bank_account_ledger')->after('approved');
         $table->foreign('bank_account_ledger')->references('id')->on('account_ledgers')->onDelete('cascade');
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
            $table->dropColumn('bank_account_ledger');
        });
    }
}
