<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMcbPaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mcb_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->string('consumernumber', 60);
            $table->string('institutioncode', 5);
            $table->decimal('amount', 15, 2);
            $table->string('transactiondate', 20);
            $table->string('responsecode', 2);
            $table->string('message')->nullable();
            $table->text('request_data')->nullable();
            $table->text('error_message')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('consumernumber');
            $table->index('transactiondate');
            $table->index('responsecode');
            $table->index(['consumernumber', 'amount', 'transactiondate', 'responsecode']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mcb_payment_logs');
    }
}

