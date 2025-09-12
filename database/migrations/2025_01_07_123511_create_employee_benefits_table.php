<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('employee_benefits')) {

            Schema::create('employee_benefits', function (Blueprint $table) {
                $table->id();
                $table->integer('employee_id')->nullable();
                $table->decimal('company_amount', 15, 2)->nullable();
                $table->decimal('employee_amount', 15, 2)->nullable();

                $table->enum('type', ['EOBI', 'PF', 'SS'])->default('EOBI');

                $table->integer('year')->nullable();
                $table->integer('month')->nullable();
                $table->timestamps();

                $table->index('type');
                $table->index('employee_id');
                $table->index(['year', 'month']);

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
        Schema::dropIfExists('employee_benefits');
    }
}
