<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('quotes')) {

            Schema::create('quotes', function (Blueprint $table) {
                $table->id();
                $table->integer('supplier_id')->nullable();
                $table->string('number', 255)->nullable();
                $table->integer('branch_id')->nullable();
                $table->date('quote_date')->nullable();
                $table->date('due_date')->nullable();
                $table->text('comments')->nullable();
                $table->enum('type', ['F', 'S', 'U'])->default('F');
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
        Schema::dropIfExists('quotes');
    }
}
