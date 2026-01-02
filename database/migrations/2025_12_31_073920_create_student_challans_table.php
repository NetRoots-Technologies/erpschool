<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_challans', function (Blueprint $table) {
            $table->id();

            // ✅ Correct parent table name
            $table->foreignId('student_databank_id')
                ->constrained('student_databank')
                ->cascadeOnDelete();

            // Challan Info
            $table->string('challan_no')->unique();
            $table->string('reference_no');
            $table->decimal('amount', 10, 2)->default(5000);

            // Status
            $table->enum('status', ['unpaid', 'paid', 'cancelled'])
                ->default('unpaid');

            // Dates
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_challans');
    }
};
