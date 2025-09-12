<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmEmployeesEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employees_education', function (Blueprint $table) {
            $table->id();
            $table->string('institution')->nullable();
            $table->string('year')->nullable();
            $table->string('certification')->nullable();
            $table->string('cgpa')->nullable();
            $table->string('Specialization')->nullable();
            $table->string('education_images')->nullable();

            $table->unsignedBigInteger('hrm_employee_id')->nullable();
            $table->foreign('hrm_employee_id')
                ->references('id')
                ->on('hrm_employees')
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
        Schema::dropIfExists('hrm_employees_education');
    }
}
