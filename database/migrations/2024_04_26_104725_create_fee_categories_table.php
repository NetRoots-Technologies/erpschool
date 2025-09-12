<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fee_categories')) {

            Schema::create('fee_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('session_id');
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('branch_id');
                //            $table->unsignedBigInteger('class_id');
                $table->string('category');
                //            $table->string('abbre')->nullable();
                $table->string('fa_percent');
                $table->integer('active')->default(1);
                $table->string('full_fee');
                $table->string('FA')->nullable();
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
        Schema::dropIfExists('fee_categories');
    }
}
