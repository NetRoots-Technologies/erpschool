<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropOldFeeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop fee-related tables in correct order (considering foreign key constraints)
        Schema::dropIfExists('students_fee_data');
        Schema::dropIfExists('students_fee');
        Schema::dropIfExists('paid_student_fee');
        Schema::dropIfExists('fee_structure_value');
        Schema::dropIfExists('fee_structures');
        Schema::dropIfExists('fee_terms_voucher');
        Schema::dropIfExists('fee_terms');
        Schema::dropIfExists('fee_heads');
        Schema::dropIfExists('fee_sections');
        Schema::dropIfExists('fee_categories');
        Schema::dropIfExists('fee_factors');
        Schema::dropIfExists('bills_data');
        Schema::dropIfExists('bills');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Note: This migration is irreversible as we're dropping old fee tables
        // The new fee structure will be created with separate migrations
        throw new Exception('This migration cannot be reversed. Use backup to restore data if needed.');
    }
}
