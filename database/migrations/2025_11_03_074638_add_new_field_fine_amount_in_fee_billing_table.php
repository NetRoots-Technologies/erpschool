<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldFineAmountInFeeBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_billing', function (Blueprint $table) {
            $table->decimal('fine_amount' , 10 , 2)->default(0)->after('outstanding_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_billing', function (Blueprint $table) {
            $table->dropColumn('fine_amount');

        });
    }
}
