<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('journal_entry_id');
            $table->unsignedBigInteger('account_ledger_id');
            $table->text('description')->nullable();
            $table->decimal('debit', 20, 2)->default(0);
            $table->decimal('credit', 20, 2)->default(0);
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->unsignedBigInteger('profit_center_id')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
            
            $table->foreign('journal_entry_id')->references('id')->on('journal_entries')->onDelete('cascade');
            $table->foreign('account_ledger_id')->references('id')->on('account_ledgers')->onDelete('restrict');
            
            $table->index(['journal_entry_id']);
            $table->index(['account_ledger_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_entry_lines');
    }
};