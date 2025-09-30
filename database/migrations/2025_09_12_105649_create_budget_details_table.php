<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('budget_id');
            $table->string('month')->nullable();
            $table->decimal('allocated_amount' , 22,2)->nullable();
            $table->decimal('allowed_spend' , 22 ,2)->nullable();
            $table->foreign('budget_id')->references('id')->on('sub_budgets')->onDelete('cascade');
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
        Schema::dropIfExists('budget_details');
    }
}
