<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EntryItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('entry_items', function (Blueprint $table) {


            $table->increments('id');
            // Entries Data
            $table->unsignedInteger('entry_type_id')->nullable();
            $table->unsignedInteger('entry_id');
            $table->unsignedInteger('ledger_id')->nullable();
            $table->unsignedInteger('job_id')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('parent_type', 50)->nullable();
            // Debit Credit Entries
            $table->date('voucher_date');
            $table->decimal('amount', 11, 4)->nullable();
            $table->decimal('other_amount', 11, 4)->nullable();
            $table->decimal('rate', 11, 4)->nullable();
            $table->enum('dc', ['d', 'c']);
            $table->unsignedInteger('currence_type')->nullable();
            $table->unsignedInteger('other_currency_type')->nullable();
            $table->unsignedInteger('grnID')->nullable();
            $table->unsignedInteger('invID')->nullable();
            $table->text('narration')->nullable();
            $table->unsignedSmallInteger('status')->default(1);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();


        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
