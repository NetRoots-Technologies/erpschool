<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalMarksToComponentsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('components_data', function (Blueprint $table) {
            if (!Schema::hasColumn('components_data', 'total_marks')) {
                $table->integer('total_marks')->nullable()->after('weightage');
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
        Schema::table('components_data', function (Blueprint $table) {
            $table->dropColumn('total_marks');
        });
    }
}
