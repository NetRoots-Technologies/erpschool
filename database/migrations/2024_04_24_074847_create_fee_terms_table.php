<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fee_terms')) {
            Schema::create('fee_terms', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('session_id');
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('class_id');
                $table->string('term');
                $table->string('installment');
                $table->date('voucher_date');
                $table->date('starting_date');
                $table->date('ending_date');
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
        Schema::dropIfExists('fee_terms');
    }
}
