<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_budgets', function (Blueprint $table) {
            // $table->id();
             $table->increments('id');

            // Budget relation
            $table->unsignedBigInteger('budget_id');
            $table->foreign('budget_id')
                  ->references('id')
                  ->on('sub_budgets')
                  ->onDelete('cascade');

                //   $table->unsignedBigInteger('budget_detail_id');
                //   $table->foreign('budget_detail_id')
                //   ->references('id')
                //   ->on('budget_details')
                //   ->onDelete('cascade');

                // Department relation
                // $table->unsignedBigInteger('department_id');
                // $table->foreign('department_id')
                //     ->references('id')
                //     ->on('departments')
                //     ->onDelete('cascade');

            // Category relation
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')
                  ->references('id')
                  ->on('b_category')
                  ->onDelete('cascade');


            $table->string('month');
            $table->decimal('amount', 15, 2);
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
        Schema::dropIfExists('department_budgets');
    }
}
