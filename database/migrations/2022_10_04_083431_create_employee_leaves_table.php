<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('leave_title');
            $table->string('employee_id');
            $table->string('leave_type');
            $table->string('leave_reason');
            $table->date('leave_date')->nullable();
            $table->string('hod_approval')->nullable();
            $table->string('admin_approval')->nullable();
            $table->string('hr_approval')->nullable();
            $table->string('team_lead_approval')->nullable();
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
        Schema::dropIfExists('employee_leaves');
    }
}
