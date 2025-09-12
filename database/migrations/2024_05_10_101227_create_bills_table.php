<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bills')) {

            Schema::create('bills', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('session_id');
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('student_id');

                $table->date('year_month');
                $table->integer('voucher_number')->default(1);
                $table->date('bill_date')->nullable();
                $table->date('charge_from')->nullable();
                $table->date('charge_to')->nullable();
                $table->date('ledger_date')->nullable();
                $table->integer('fee_factor');
                $table->date('due_date')->nullable();
                $table->date('valid_date')->nullable();
                $table->string('fees');
                $table->integer('status')->default(0);
                $table->text('message')->nullable();
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
        Schema::dropIfExists('bills');
    }
}
