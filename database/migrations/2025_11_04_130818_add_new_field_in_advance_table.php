<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldInAdvanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advances', function (Blueprint $table) {
            $table->integer('status')->default(0)->after('image');
            $table->string('amount_status')->nullable()->after('status');
            $table->integer('remaining_amount')->default(0)->after('amount_to_pay');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advances', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('amount_status');
            $table->dropColumn('remaining_amount');
            
        });
    }
}
