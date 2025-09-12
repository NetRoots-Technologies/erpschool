<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_days', function (Blueprint $table) {
            $table->id();
            $table->string('Mon')->nullable();
            $table->string('Tue')->nullable();
            $table->string('Wed')->nullable();
            $table->string('Thu')->nullable();
            $table->string('Fri')->nullable();
            $table->string('Sat')->nullable();
            $table->string('Sun')->nullable();
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('work_shift_id');
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
        Schema::dropIfExists('shift_days');
    }
}
