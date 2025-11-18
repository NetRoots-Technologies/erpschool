<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->date('entry_date');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'posted', 'approved', 'cancelled'])->default('draft');
            $table->enum('entry_type', [
            'journal',
            'payment',
            'receipt',
            'transfer',
            'journal_voucher',
            'cash_payment_voucher',
            'bank_payment_voucher',
            'bank_receipt_voucher',
            'cash_receipt_voucher'
                  ])->default('journal');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->decimal('exchange_rate', 15, 6)->default(1.000000); 
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('profit_center_id')->nullable();
            $table->string('source_module')->nullable(); // hr, inventory, academic, fleet, manual
            $table->unsignedBigInteger('source_id')->nullable(); // ID from source module
            $table->timestamp('posted_at')->nullable();
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            
            $table->index(['entry_date', 'status']);
            $table->index(['source_module', 'source_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_entries');
    }
};