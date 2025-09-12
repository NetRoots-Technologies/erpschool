<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrmEmployeesWorkExperinceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrm_employees_work_experince', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hrm_employee_id')->nullable()->constrained('hrm_employees')->onDelete('cascade');
            $table->string('s_no')->nullable();
            $table->string('name_of_institution')->nullable();
            $table->string('designation')->nullable();
            $table->string('duration')->nullable();
            $table->date('from')->nullable();
            $table->date('till')->nullable();
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
        Schema::dropIfExists('hrm_employees_work_experince');
    }
}
