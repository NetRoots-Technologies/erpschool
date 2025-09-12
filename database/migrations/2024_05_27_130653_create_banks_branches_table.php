<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('banks_branches')) {

            Schema::create('banks_branches', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bank_id');
                $table->string('branch_code');
                $table->string('branch_name');
                $table->integer('status')->default(1);

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
        Schema::dropIfExists('banks_branches');
    }
}
