<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vendors')) {

            Schema::create('vendors', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_category_id');
                $table->foreign('vendor_category_id')->references('id')->on('vendor_categorys')->onDelete('Cascade');
                $table->string('name');
                $table->unsignedBigInteger('b_category_id');
                $table->foreign('b_category_id')->references('id')->on('b_category')->onDelete('Cascade');
                $table->string('description')->nullable();
                $table->unsignedInteger('ledger_id')->default(config('account_constants.PURCHASE_CONTROL_ACCOUNT'))->onDelete('Cascade');
                $table->foreign('ledger_id')->references('id')->on('ledgers');
                $table->string('company_name')->nullable();
                $table->string('cnic')->nullable();
                $table->string('ntn')->nullable();
                $table->string('strn')->nullable();
                $table->string('folio_no')->nullable();
                $table->unsignedInteger('state_id')->nullable();
                $table->foreign('state_id')->references('id')->on('states');
                $table->unsignedInteger('city_id')->nullable();
                $table->foreign('city_id')->references('id')->on('cities');
                $table->string('mobileNo')->nullable();
                $table->string('phoneNo')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('zip_code')->nullable();
                $table->string('postal_address')->nullable();
                $table->string('shipping_address')->nullable();
                $table->boolean('status')->default(true);
                $table->string('code')->nullable()->unique();
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
        Schema::dropIfExists('vendors');
    }
}
