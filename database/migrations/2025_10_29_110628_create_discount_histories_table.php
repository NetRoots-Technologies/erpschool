<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('fee_discount_histories', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('fee_discount_id');
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->json('old_data')->nullable();
        $table->json('new_data')->nullable();
        $table->timestamps();

        $table->foreign('fee_discount_id')->references('id')->on('fee_discounts')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discount_histories');
    }
}
