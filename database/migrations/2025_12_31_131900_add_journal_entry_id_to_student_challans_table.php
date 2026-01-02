<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('student_challans', function (Blueprint $table) {
            $table->unsignedBigInteger('journal_entry_id')->nullable()->after('status');

            $table->foreign('journal_entry_id')
                  ->references('id')
                  ->on('journal_entries')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('student_challans', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
        });
    }
};
