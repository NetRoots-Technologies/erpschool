<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('purchase_orders')) {

            Schema::create('purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->integer('supplier_id')->nullable();
                $table->string('number', 255)->nullable();
                $table->integer('branch_id')->nullable();
                $table->double('total_amount')->default(0.00);
                $table->date('order_date')->nullable();
                $table->date('delivery_date')->nullable();
                $table->enum('delivery_status', ['PENDING', 'SHIPPED', 'CANCELLED', 'COMPLETED'])->default('PENDING');
                $table->enum('payment_status', ['PAID', 'PENDING', 'OVERDUE'])->default('PENDING');
                $table->enum('payment_method', ['BANK', 'CASH', 'CHEQUE'])->default('BANK');
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
        Schema::dropIfExists('purchase_orders');
    }
}
