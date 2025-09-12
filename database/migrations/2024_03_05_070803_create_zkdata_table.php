<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZkdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zkdata', function (Blueprint $table) {
            $table->id();
            $table->integer('uid')->nullable();
            $table->integer('userid')->nullable();
            $table->string('name')->nullable();
            $table->integer('role')->nullable();
            $table->string('password')->nullable();
            $table->string('cardno')->nullable();
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
        Schema::dropIfExists('zkdata');
    }
}
