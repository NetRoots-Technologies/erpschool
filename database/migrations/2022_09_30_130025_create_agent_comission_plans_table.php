<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentComissionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_comission_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slab_name')->nullable();
            $table->integer('agent_type_id')->nullable();
            $table->string('min')->nullable();
            $table->string('max')->nullable();
            $table->string('comission')->nullable();
            $table->string('slab_type')->nullable();
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
        Schema::dropIfExists('agent_comission_plans');
    }
}
