<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('vendor_payments', function (Blueprint $table) {

            $table->id(); // Payment ID / Voucher No (auto)

            $table->string('voucher_no')->unique(); // Auto-generated unique payment number

            $table->date('payment_date'); // Payment Date

            $table->unsignedBigInteger('vendor_id'); // Vendor ID

            $table->unsignedBigInteger('invoice_id')->nullable(); // Purchase Invoice ID / GRN Reference

            $table->decimal('invoice_amount', 15, 2)->nullable(); // Total Invoice Amount
            $table->decimal('pending_amount', 15, 2)->nullable(); // Pending Amount
            $table->decimal('payment_amount', 15, 2); // Payment Amount

            $table->string('payment_mode'); // Cash, Cheque, Bank Transfer, etc.

            $table->unsignedBigInteger('account_id')->nullable(); // Bank / Cash Account

            $table->string('cheque_no')->nullable(); // Cheque No
            $table->date('cheque_date')->nullable(); // Cheque Date

            $table->text('remarks')->nullable(); // Narration / Remarks

            $table->string('attachment')->nullable(); // Payment proof file

            $table->unsignedBigInteger('prepared_by')->nullable(); // Prepared By
            $table->unsignedBigInteger('approved_by')->nullable(); // Approved By

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_payments');
    }
}
