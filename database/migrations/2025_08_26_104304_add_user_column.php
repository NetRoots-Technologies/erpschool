<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_details', function (Blueprint $table) {
            // $table->dropColumn('user_id');
            if (!Schema::hasColumn('exam_details', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_details', function (Blueprint $table) {
            if (Schema::hasColumn('exam_details', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
}
