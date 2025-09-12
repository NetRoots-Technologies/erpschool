<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcademicSessionIdInMarkInputs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mark_inputs', function (Blueprint $table) {
            $table->unsignedBigInteger('acadmeic_sessions_id')->nullable();
                // $table->foreign('session_id')->references('id')->on('acadmeic_sessions');

        //  $table->foreignId('acadmeic_session_id')->constrained('acadmeic_sessions')->cascadeOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mark_inputs', function (Blueprint $table) {
            // $table->dropColumn('acadmeic_session_id');
        });
    }
}
