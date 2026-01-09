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

            /*
             |--------------------------------------------------------------------------
             | Indexes (explicit short names to avoid MySQL 64-char limit)
             |--------------------------------------------------------------------------
             */
            $table->index('consumernumber', 'idx_mcb_consumer');
            $table->index('transactiondate', 'idx_mcb_tx_date');
            $table->index('responsecode', 'idx_mcb_response');

            // Composite index (MOST IMPORTANT FIX)
            $table->index(
                ['consumernumber', 'amount', 'transactiondate', 'responsecode'],
                'idx_mcb_cons_amt_dt_resp'
            );
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
