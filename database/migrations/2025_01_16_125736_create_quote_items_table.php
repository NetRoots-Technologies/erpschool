<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('quote_items')) {

            Schema::create('quote_items', function (Blueprint $table) {
                $table->id();
                $table->integer('quote_id')->nullable();
                $table->integer('item_id')->nullable();
                $table->integer('quantity')->nullable();
                $table->double('unit_price')->default(0.00);
                $table->double('total_price')->default(0.00);
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
        Schema::dropIfExists('quote_items');
    }
}
