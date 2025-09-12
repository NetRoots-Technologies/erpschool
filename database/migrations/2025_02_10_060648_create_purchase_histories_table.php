<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('purchase_histories')) {

            Schema::create('purchase_histories', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name')->nullable();
                $table->string('number', 255)->nullable();
                $table->unsignedBigInteger('voucher_id')->nullable();
                $table->string('card_number', 20)->nullable();
                $table->dateTime('purchase_date')->default(now());
                $table->decimal('total_sum', 10, 2);
                $table->decimal('total_price', 10, 2);
                $table->json('item_lists');
                $table->string('transaction_id')->nullable();
                $table->enum('payment_method', ['cash', 'credit_card', 'debit_card'])->default('cash');
                $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
                $table->decimal('discount_applied', 10, 2)->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->text('notes')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->index('voucher_id');
                $table->index('transaction_id');
                $table->index('purchase_date');

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
        Schema::dropIfExists('purchase_histories');
    }
}
