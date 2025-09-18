<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeVoucherDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_voucher_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_voucher_id');
            $table->unsignedBigInteger('fee_head_id');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('fee_voucher_id')->references('id')->on('fee_vouchers')->onDelete('cascade');
            $table->foreign('fee_head_id')->references('id')->on('fee_heads')->onDelete('cascade');

            // Indexes
            $table->index('fee_voucher_id');
            $table->index('fee_head_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_voucher_details');
    }
}
