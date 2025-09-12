<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHrmEmployeesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('father_name')->nullable();
            $table->string('cnic_card')->nullable();
            $table->string('tell_no')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_address')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('working_hour')->nullable();
            $table->string('hour_salary')->nullable();
            $table->string('visitingLecturer')->nullable();
            $table->string('employeeWelfare')->nullable();
            $table->string('deductedAmount')->nullable();
            $table->string('dob')->nullable();
            $table->unsignedBigInteger('work_shift_id')->nullable();

            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('company')->onDelete('set null');

            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');

            $table->unsignedBigInteger('department_id')->nullable();

            $table->unsignedBigInteger('designation_id')->nullable();

            $table->unsignedBigInteger('employee_id')->nullable();

            //            /$table->unsignedBigInteger('other_branch')->nullable();
//            $table->foreign('other_branch')->references('id')->on('branches')->onDelete('set null');
            $table->string('other_branch')->nullable();
            $table->string('job_seeking')->nullable();
            $table->date('start_date')->nullable();
            $table->string('salary')->nullable();
            $table->string('applied')->nullable();
            $table->string('applied_yes')->nullable();
            $table->string('employed')->nullable();
            $table->string('when_employed_yes')->nullable();
            $table->string('engaged_business')->nullable();
            $table->string('when_business_yes')->nullable();
            $table->string('skills')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('marital_status')->nullable();
            $table->integer('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

    }

    //
//$table->string('gender')->nullable();
//$table->string('marital_status')->nullable();
//$table->string('dob')->nullable();
//
//$table->string('address')->nullable();
//$table->string('mobile')->nullable();
//$table->string('net_salary')->nullable();
//$table->string('gross_salary')->nullable();
//$table->string('allowances')->nullable();
//$table->string('type');
//$table->string('department_id');
//$table->string('allowed_leaves')->default(1);
//$table->string('remaining_leaves')->default(1);
//$table->string('image')->nullable();
//$table->string('account_no')->nullable();
//$table->string('status')->default(1);
//$table->string('is_terminate')->default(0);
//$table->text('documents')->nullable();


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hrm_employees');
    }

}
