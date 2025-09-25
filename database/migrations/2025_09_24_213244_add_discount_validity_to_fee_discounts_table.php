<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountValidityToFeeDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_discounts', function (Blueprint $table) {
            $table->date('valid_from')->nullable()->after('is_active');
            $table->date('valid_to')->nullable()->after('valid_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_discounts', function (Blueprint $table) {
            $table->dropColumn(['valid_from', 'valid_to']);
        });
    }
}
