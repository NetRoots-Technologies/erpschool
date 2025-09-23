<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget_id'); 
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->date('expense_date');
            $table->decimal('expense_amount', 15, 2);
            $table->text('description')->nullable();
            $table->foreign('budget_id')->references('id')->on('sub_budgets')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('b_category')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('b_category')->onDelete('cascade');
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
        Schema::dropIfExists('budget_expenses');
    }
}
