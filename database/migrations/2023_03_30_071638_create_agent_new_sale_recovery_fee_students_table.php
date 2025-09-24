<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentNewSaleRecoveryFeeStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_new_sale_recovery_fee_students', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_recovery_incentive_id');
            $table->integer('agent_id');
            $table->integer('student_id')->nullable();
            $table->integer('student_fee_id')->nullable();
            $table->integer('paid_fee_id');
            $table->date('start_date');
            $table->date('end_date');

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
        Schema::dropIfExists('agent_new_sale_recovery_fee_students');
    }
}
