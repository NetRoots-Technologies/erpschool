<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToLedgersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ledgers', function (Blueprint $table) {
           // $table->unsignedBigInteger('parent_id')->nullable()->after('parent_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('ledgers', function (Blueprint $table) {
            // $table->dropColumn('parent_id');
        });
    }
}
