<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmEmployeesFamilyInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employees_family_information', function (Blueprint $table) {
            $table->id();
            $table->string('sr_no')->nullable();
            $table->string('name')->nullable();
            $table->string('relation')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('cnic')->nullable();
            $table->string('workstation')->nullable();

            $table->foreignId('hrm_employee_id')->nullable()
            ->constrained('hrm_employees')
            ->onDelete('cascade');

            $table->timestamps();
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
        Schema::dropIfExists('hrm_employees_family_information');
    }
}
