<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBehavioursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('behaviours')) {

            Schema::create('behaviours', function (Blueprint $table) {
                $table->id();
                $table->string("abbrev")->nullable();
                $table->string("key")->nullable();
                $table->boolean("status")->nullable();
                $table->longText("logs")->nullable();
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
        Schema::dropIfExists('behaviours');
    }
}
