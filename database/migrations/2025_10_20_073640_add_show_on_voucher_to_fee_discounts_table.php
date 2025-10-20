<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowOnVoucherToFeeDiscountsTable extends Migration
{
    public function up()
    {
        Schema::table('fee_discounts', function (Blueprint $table) {
            $table->boolean('show_on_voucher')->default(false)->after('reason');
        });
    }

    public function down()
    {
        Schema::table('fee_discounts', function (Blueprint $table) {
            $table->dropColumn('show_on_voucher');
        });
    }
}
