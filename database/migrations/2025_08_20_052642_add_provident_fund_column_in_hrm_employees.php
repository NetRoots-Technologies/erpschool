<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProvidentFundColumnInHrmEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrm_employees', function (Blueprint $table) {
            if(!Schema::hasColumn('hrm_employees','provident_fund')){
                $table->boolean('provident_fund')->default(false);
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
        Schema::table('hrm_employees', function (Blueprint $table) {
            if(Schema::hasColumn('hrm_employees','provident_fund')){
                $table->dropColumn('provident_fund');
            }
        });
    }
}
