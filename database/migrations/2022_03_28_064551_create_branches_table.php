<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id')->nullable();
            $table->string('name', 255);
            $table->string('branch_code', 255);
            $table->string('address', 255);
            $table->unsignedSmallInteger('status')->default(1);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->string('ip_config')->nullable();
            $table->string('port')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // Foreign Key relationships

            //            $table->foreign('company_id')->references('id')->on('companies');
//            $table->foreign('created_by')->references('id')->on('users');
//            $table->foreign('updated_by')->references('id')->on('users');
//            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
