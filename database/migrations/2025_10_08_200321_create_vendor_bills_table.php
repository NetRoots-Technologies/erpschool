<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->unsignedBigInteger('vendor_id');
            $table->date('bill_date');
            $table->date('due_date');
            $table->string('vendor_invoice_number')->nullable();
            $table->decimal('subtotal', 20, 2);
            $table->decimal('tax_amount', 20, 2)->default(0);
            $table->decimal('discount', 20, 2)->default(0);
            $table->decimal('total_amount', 20, 2);
            $table->decimal('paid_amount', 20, 2)->default(0);
            $table->decimal('balance', 20, 2);
            $table->enum('status', ['draft', 'pending', 'partially_paid', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('journal_entry_id')->nullable(); // Linked journal entry
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('vendor_id')->references('id')->on('account_vendors')->onDelete('restrict');
            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            
            $table->index(['vendor_id', 'status']);
            $table->index(['due_date', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_bills');
    }
};