<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('account_group_id');
            $table->decimal('opening_balance', 20, 2)->default(0);
            $table->enum('opening_balance_type', ['debit', 'credit'])->default('debit');
            $table->decimal('current_balance', 20, 2)->default(0);
            $table->enum('current_balance_type', ['debit', 'credit'])->default('debit');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false); // System accounts (can't be deleted)
            $table->string('linked_module')->nullable(); // hr, inventory, academic, fleet
            $table->unsignedBigInteger('linked_id')->nullable(); // ID of linked entity
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('account_group_id')->references('id')->on('account_groups')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            
            $table->index(['account_group_id', 'is_active']);
            $table->index(['linked_module', 'linked_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('account_ledgers');
    }
};