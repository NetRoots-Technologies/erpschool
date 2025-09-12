<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmEmployeeAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employee_attendances', function (Blueprint $table) {

            $table->id();
            $table->integer('user_id');
            $table->String('date');
            $table->String('checkin_time')->nullable();
            $table->String('checkout_time')->nullable();
            $table->String('overtime_in')->nullable();
            $table->String('overtime_out')->nullable();
            $table->String('type')->nullable();
            $table->String('status');
            $table->String('is_machine')->nullable();
            $table->String('manual_attendance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrm_employee_attendances');
    }
}
