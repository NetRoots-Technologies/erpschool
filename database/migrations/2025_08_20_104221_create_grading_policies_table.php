<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingPoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grading_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acadmeic_session_id')->nullable();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->string('grade')->nullable();
            $table->string('marks_range')->nullable();
            $table->integer('marks_from')->default(0);
            $table->integer('marks_to')->default(0);
            $table->text('description')->nullable();
            $table->boolean('status')->default(false);
            $table->longText('logs')->nullable();
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
        Schema::dropIfExists('grading_policies');
    }
}
