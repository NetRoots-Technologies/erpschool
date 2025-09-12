<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('components_data')) {

            Schema::create('components_data', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('component_id');
                $table->unsignedBigInteger('type_id');
                $table->integer('weightage');
                $table->timestamps();
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
        Schema::dropIfExists('components_data');
    }
}
