<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hrm_employee_id')->nullable();
            $table->unsignedBigInteger('hr_quota_setting_id')->nullable();
            $table->unsignedBigInteger('work_shift_id')->nullable();

            $table->unsignedBigInteger('responsible_employee')->nullable();
            $table->date('start_date')->nullable();
            $table->integer('days')->nullable();
            $table->date('end_date')->nullable();
            //            $table->integer('paid_leaves')->nullable();
//            $table->integer('unpaid_leaves')->nullable();
            $table->string('duration')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('evidence')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            //            $table->foreign('hrm_employee_id')->references('id')->on('hrm_employees')->onDelete('cascade');
//            $table->foreign('hr_quota_setting_id')->references('id')->on('hr_quota_settings')->onDelete('cascade');
//            $table->foreign('work_shift_id')->references('id')->on('work_shifts')->onDelete('cascade');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
}
