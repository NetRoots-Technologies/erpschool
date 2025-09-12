<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('classes')) {

            Schema::create('classes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('school_type_id');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('session_id');
                $table->unsignedBigInteger('company_id');
                $table->integer('status')->default(1);
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
        Schema::dropIfExists('classes');
    }
}
