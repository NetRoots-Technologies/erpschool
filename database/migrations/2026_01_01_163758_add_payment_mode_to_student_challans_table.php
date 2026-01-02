<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentModeToStudentChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('student_challans', function (Blueprint $table) {
        $table->string('payment_mode')
              ->nullable()
              ->after('status'); // optional placement
    });
}

public function down()
{
    Schema::table('student_challans', function (Blueprint $table) {
        $table->dropColumn('payment_mode');
    });
}

}
