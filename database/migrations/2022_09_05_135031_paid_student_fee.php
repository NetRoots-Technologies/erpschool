<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaidStudentFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_student_fee', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_fee_id')->nullable();
            $table->integer('student_id')->nullable();
            //            $table->integer('installement_no')->nullable();
            $table->integer('installement_amount')->nullable();
            $table->date('paid_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('paid_status')->default('pending');
            $table->string('source')->nullable();
            $table->string('type')->nullable();
            $table->unsignedSmallInteger('status')->default(1);
            $table->softDeletes();
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
        //
    }
}
