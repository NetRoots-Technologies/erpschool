<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentEmergencyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('student_emergency_contacts')) {

            Schema::create('student_emergency_contacts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->string('name')->nullable();
                $table->string('relation')->nullable();
                $table->string('parent_responsibility')->nullable();
                $table->string('home_address')->nullable();
                $table->string('city')->nullable();
                $table->string('landline')->nullable();
                $table->string('cell_no')->nullable();
                $table->string('email_address')->nullable();
                $table->text('work_address')->nullable();
                $table->string('work_landline')->nullable();
                $table->string('work_cell_no')->nullable();
                $table->string('work_email')->nullable();
                $table->timestamps();
                $table->softDeletes();

            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_emergency_contacts');
    }
}
