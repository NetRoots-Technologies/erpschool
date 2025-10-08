<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('customer_id');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->string('reference')->nullable();
            $table->decimal('subtotal', 20, 2);
            $table->decimal('tax_amount', 20, 2)->default(0);
            $table->decimal('discount', 20, 2)->default(0);
            $table->decimal('total_amount', 20, 2);
            $table->decimal('received_amount', 20, 2)->default(0);
            $table->decimal('balance', 20, 2);
            $table->enum('status', ['draft', 'sent', 'partially_paid', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('journal_entry_id')->nullable(); // Linked journal entry
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('customer_id')->references('id')->on('account_customers')->onDelete('restrict');
            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            
            $table->index(['customer_id', 'status']);
            $table->index(['due_date', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_invoices');
    }
};