<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentNewSaleRecoveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_new_sale_recoveries', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id')->nullable();
            $table->string('recovered_percentage')->nullable();
            $table->string('total_paid_installment')->nullable();
            $table->string('total_student_fee')->nullable();
            $table->string('incentive_percentage')->nullable();
            $table->string('commission')->nullable();
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
        Schema::dropIfExists('agent_new_sale_recoveries');
    }
}
