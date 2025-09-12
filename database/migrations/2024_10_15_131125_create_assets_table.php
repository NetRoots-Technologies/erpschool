<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('assets')) {

            Schema::create('assets', function (Blueprint $table) {
                $table->id();
                $table->integer('credit_type');
                $table->integer('credit_ledger');
                $table->integer('asset_type_id');
                $table->string('name', 255)->nullable();
                $table->string('code', 255)->nullable();
                $table->tinyInteger('working')->default(1);
                $table->integer('company_id');
                $table->integer('branch_id');
                $table->string('depreciation_type')->nullable();
                $table->date('purchase_date')->nullable();
                $table->string('invoice_number', 255)->nullable();
                $table->string('manufacturer', 255)->nullable();
                $table->string('serial_number', 255)->nullable();
                $table->date('end_date')->nullable();
                $table->string('image', 255)->nullable();
                $table->double('amount');
                $table->double('depreciation')->nullable();
                $table->double('sale_tax')->nullable();
                $table->double('income_tax')->nullable();
                $table->string('narration', 255)->nullable();
                $table->text('note')->nullable();
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
        Schema::dropIfExists('assets');
    }
}
