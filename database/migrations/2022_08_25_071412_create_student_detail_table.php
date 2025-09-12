<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('student_dob')->nullable();
            $table->string('classes_type')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_cnic')->nullable();
            $table->string('pass_cnic_expiry')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_mobile_no')->nullable();
            $table->string('guardian_relation_with_student')->nullable();
            $table->string('id_card')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('bio')->nullable();
            $table->string('passport')->nullable();
            $table->string('document')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('student_detail');
    }
}
