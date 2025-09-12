<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentNewSaleIncentiveStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_new_sale_incentive_students', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_new_sale_incentive_id');
            $table->integer('agent_id');
            $table->integer('student_id');

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
        Schema::dropIfExists('agent_new_sale_incentive_students');
    }
}
