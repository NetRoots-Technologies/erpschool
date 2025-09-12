<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPresentColumnToPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            if (!Schema::hasColumn('payrolls', 'total_present')) {
                $table->integer('total_present')->nullable();
            }
            if (!Schema::hasColumn('payrolls', 'total_absent')) {
                $table->integer('total_absent')->nullable();
            }
            if (!Schema::hasColumn('payrolls', 'total_leave')) {
                $table->integer('total_leave')->nullable();
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
        Schema::table('payrolls', function (Blueprint $table) {
            //
        });
    }
}
