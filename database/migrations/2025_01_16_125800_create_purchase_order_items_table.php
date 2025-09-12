<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('purchase_order_items')) {

            Schema::create('purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->integer('item_id')->nullable();
                $table->integer('purchase_order_id')->nullable();
                $table->integer('quantity')->nullable();
                $table->integer('received_quantity')->nullable();
                $table->double('unit_price')->default(0.00);
                $table->double('total_price')->default(0.00);
                $table->double('quote_item_price')->nullable();
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
        Schema::dropIfExists('purchase_order_items');
    }
}
