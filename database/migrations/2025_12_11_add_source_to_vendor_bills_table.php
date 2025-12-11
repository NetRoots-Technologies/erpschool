<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendor_bills', function (Blueprint $table) {
            $table->string('source_module')->nullable()->comment('Module that created this bill (e.g., purchase_order)');
            $table->unsignedBigInteger('source_id')->nullable()->comment('ID of the source record (e.g., purchase_order.id)');
            
            $table->index(['source_module', 'source_id']);
        });
    }

    public function down()
    {
        Schema::table('vendor_bills', function (Blueprint $table) {
            $table->dropIndex(['source_module', 'source_id']);
            $table->dropColumn(['source_module', 'source_id']);
        });
    }
};
