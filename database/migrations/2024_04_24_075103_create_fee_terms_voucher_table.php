<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeTermsVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fee_terms_voucher')) {

            Schema::create('fee_terms_voucher', function (Blueprint $table) {
                $table->id();
                $table->date('voucher_date');
                $table->date('starting_date');
                $table->date('ending_date');
                $table->unsignedBigInteger('fee_terms_id');
                $table->timestamps();
                $table->softDeletes();

            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_terms_voucher');
    }
}
