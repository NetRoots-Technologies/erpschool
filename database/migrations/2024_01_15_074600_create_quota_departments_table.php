<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotaDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quota_departments', function (Blueprint $table) {
            $table->id();
            $table->integer('departments');
            $table->unsignedBigInteger('hr_quota_settings_id')->nullable();
            $table->foreign('hr_quota_settings_id')->references('id')->on('hr_quota_settings')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quota_departments');
    }
}
