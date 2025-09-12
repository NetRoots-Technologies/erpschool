<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bills_data')) {

            Schema::create('bills_data', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('fee_head_id');
                $table->unsignedBigInteger('bills_id');
                $table->string('bills_amount');
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
        Schema::dropIfExists('bills_data');
    }
}
