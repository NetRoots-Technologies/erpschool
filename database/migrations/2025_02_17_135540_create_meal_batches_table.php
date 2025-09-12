<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('meal_batches')) {

            Schema::create('meal_batches', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('creator_id');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('parent_id');
                $table->string('parent_type');
                $table->unsignedBigInteger('section_id')->nullable();
                $table->date('date');
                $table->unsignedBigInteger('product_id');
                $table->string('batch_type');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('meal_batches');
    }
}
