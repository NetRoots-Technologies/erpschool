<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('products')) {

            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->integer('branch_id')->nullable();
                $table->string('name')->nullable();
                $table->string('number', 255)->nullable();
                $table->double('cost_amount')->default(0.00);
                $table->double('sale_price')->default(0.00);
                $table->boolean('status')->default(false);
                // $table->text('description')->nullable();
                $table->enum('type', ['F', 'S', 'U', 'G'])->default('F');
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
        Schema::dropIfExists('products');
    }
}
