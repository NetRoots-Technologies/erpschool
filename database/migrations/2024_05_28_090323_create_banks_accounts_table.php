<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('banks_accounts')) {

            Schema::create('banks_accounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bank_id');
                $table->unsignedBigInteger('bank_branch_id');
                $table->text('account_no');
                $table->enum('type', ['MCA', 'MOA'])->default('MOA');
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
        Schema::dropIfExists('banks_accounts');
    }
}
