<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateFeeTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('class_id');
            $table->string('term');
            $table->integer('installment');
            $table->date('voucher_date');
            $table->date('starting_date');
            $table->date('ending_date');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('acadmeic_sessions')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_terms');
    }
}
