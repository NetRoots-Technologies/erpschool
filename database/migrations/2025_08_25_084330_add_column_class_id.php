<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnClassId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('exam_details', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('exam_details', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable();
            }

            // Add foreign key constraint safely
            if (!Schema::hasColumn('exam_details', 'class_id')) {
                $table->foreign('class_id')
                    ->references('id')
                    ->on('classes')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exam_details', function (Blueprint $table) {
            // Drop foreign key only if it exists
            if (Schema::hasColumn('exam_details', 'class_id')) {
                $table->dropForeign(['class_id']);
                $table->dropColumn('class_id');
            }
        });
    }
}
