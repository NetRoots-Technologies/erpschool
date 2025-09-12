<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeStructureValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fee_structure_value')) {

            Schema::create('fee_structure_value', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('fee_head_id');
                $table->unsignedBigInteger('fee_structure_id');

                $table->string('discount_select');
                $table->string('discount');
                $table->string('discount_amount');

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
        Schema::dropIfExists('fee_structure_value');
    }
}
