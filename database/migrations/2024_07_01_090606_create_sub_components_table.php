<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('sub_components')) {

            Schema::create('sub_components', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('component_id');
                $table->unsignedBigInteger('test_type_id');
                $table->unsignedBigInteger('user_id');
                $table->string('comp_name');
                $table->string('comp_number');
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
        Schema::dropIfExists('sub_components');
    }
}
