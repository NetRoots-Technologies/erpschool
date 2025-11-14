<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_allocations', function (Blueprint $table) {
             $table->id();
             $table->unsignedBigInteger('journal_entry_id')->nullable()->index();
            $table->unsignedBigInteger('customer_invoice_id')->nullable()->index();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->decimal('amount',14,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_allocations');
    }
}
