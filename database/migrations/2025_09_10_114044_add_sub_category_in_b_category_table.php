<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubCategoryInBCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b_category', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->integer('parent_id')->nullable()->after('title');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b_category', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('user_id');
        });
    }
}
