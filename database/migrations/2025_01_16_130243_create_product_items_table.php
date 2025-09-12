<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('product_items')) {

            Schema::create('product_items', function (Blueprint $table) {
                $table->id();
                $table->integer('product_id')->nullable();
                $table->integer('item_id')->nullable();
                $table->integer('inventory_id')->nullable();
                $table->double('quantity')->default(0.00);
                $table->string('measuring_unit', 255)->nullable();
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
        Schema::dropIfExists('product_items');
    }
}
