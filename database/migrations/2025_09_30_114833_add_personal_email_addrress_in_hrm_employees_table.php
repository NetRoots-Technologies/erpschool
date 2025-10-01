<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPersonalEmailAddrressInHrmEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hrm_employees', function (Blueprint $table) {
            $table->string('personal_email_address')->nullable()->after('email');
            $table->string('gendar')->nullable()->after('personal_email_address');
            $table->string('specialization_subject')->nullable()->after('gendar');
            $table->string('machine_status')->default(0)->after('specialization_subject');
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
            $table->dropColumn('personal_email_address');
            $table->dropColumn('gender');
            $table->dropColumn('specialization_subject');
            $table->dropColumn('machine_status');
        });
    }
}
