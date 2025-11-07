<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('inventories')) {

            Schema::create('inventories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->nullable();
                $table->integer('item_id')->nullable();
                $table->integer('product_id')->nullable();
                $table->integer('branch_id')->nullable();
                $table->integer('quantity')->nullable();
                $table->decimal('unit_price', 8, 2)->default(0.00);
                $table->decimal('cost_price', 8, 2)->default(0.00);
                $table->decimal('sale_price', 8, 2)->default(0.00);
                $table->string('measuring_unit')->nullable();
                $table->enum('type', ['F', 'S', 'P', 'SP', 'U'])->default('F');
                $table->date('expiry_date')->nullable();
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
        Schema::dropIfExists('inventries');
    }
}
