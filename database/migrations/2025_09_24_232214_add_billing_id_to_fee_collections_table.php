<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fee_collections', function (Blueprint $table) {
            $table->unsignedBigInteger('billing_id')->nullable()->after('academic_session_id');
            $table->foreign('billing_id')->references('id')->on('fee_billing')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_collections', function (Blueprint $table) {
            $table->dropForeign(['billing_id']);
            $table->dropColumn('billing_id');
        });
    }
};