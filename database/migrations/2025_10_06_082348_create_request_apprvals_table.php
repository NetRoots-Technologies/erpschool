<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestApprvalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_apprvals', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id')->nullable();
            $table->integer('approver_id')->nullable();
            $table->string('approval_status')->nullable();
            $table->string('done_status')->nullable();
            $table->text('remarks')->nullable();
            $table->string('approval_date')->nullable();
            $table->string('approval_level')->nullable();
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
        Schema::dropIfExists('request_apprvals');
    }
}
