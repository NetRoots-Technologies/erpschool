<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsFeeDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('students_fee_data')) {

            Schema::create('students_fee_data', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('students_fee_id');

                $table->unsignedBigInteger('fee_head_id');
                $table->unsignedBigInteger('fee_factor_id');
                $table->string('monthly_amount');
                $table->string('discount_percent');
                $table->string('discount_rupees');
                $table->string('claim1');
                $table->string('claim2');
                $table->string('total_amount_after_discount');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('students_fee_data');
    }
}
