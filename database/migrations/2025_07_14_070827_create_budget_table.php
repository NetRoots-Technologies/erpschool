<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('budgets')) {

            Schema::create('budgets', function (Blueprint $table) {
                $table->id();
                $table->string('title', 100);
                $table->string('timeFrame', 100);
                $table->date('startDate');
                $table->date('endDate')->nullable();
                $table->unsignedBigInteger('b_category_id');
                $table->unsignedBigInteger('amount');
                $table->unsignedInteger('department_id');
                $table->foreign('department_id')
                    ->references('id')->on('departments')->onDelete('cascade');
                $table->foreign('b_category_id')
                    ->references('id')->on('b_category')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget');
    }
}
