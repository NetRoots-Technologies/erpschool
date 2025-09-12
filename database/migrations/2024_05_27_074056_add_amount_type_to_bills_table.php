<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountTypeToBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bills', function (Blueprint $table) {
            if (!Schema::hasColumn('bills', 'amount_type')) {

                $table->string('amount_type')->nullable()->after('voucher_number');
            }
            if (!Schema::hasColumn('bills', 'diff_amount')) {
                $table->string('diff_amount')->nullable()->after('amount_type');
            }
            if (!Schema::hasColumn('bills', 'previous_amount')) {
                $table->string('previous_amount')->nullable()->after('diff_amount');
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
        Schema::table('bills', function (Blueprint $table) {
            //
        });
    }
}
