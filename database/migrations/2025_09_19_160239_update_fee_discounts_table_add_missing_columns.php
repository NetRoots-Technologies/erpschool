<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateFeeDiscountsTableAddMissingColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_discounts', function (Blueprint $table) {
            // Add new column
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
        });
        
        // Copy data from old column to new column
        DB::statement('UPDATE fee_discounts SET category_id = fee_category_id');
        
        Schema::table('fee_discounts', function (Blueprint $table) {
            // Drop old column
            $table->dropColumn('fee_category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_discounts', function (Blueprint $table) {
            // Add back the old column
            $table->unsignedBigInteger('fee_category_id')->nullable()->after('id');
        });
        
        // Copy data from category_id back to fee_category_id
        DB::statement('UPDATE fee_discounts SET fee_category_id = category_id');
        
        Schema::table('fee_discounts', function (Blueprint $table) {
            // Drop the new column
            $table->dropColumn('category_id');
        });
    }
}
