<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentNewSaleIncentivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_new_sale_incentives', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id')->nullable();
            $table->string('count')->nullable();
            $table->string('student_fee')->nullable();
            $table->string('percentage')->nullable();
            $table->string('commission')->nullable();
            $table->integer('student_ids')->nullable();
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
        Schema::dropIfExists('agent_new_sale_incentives');
    }
}
