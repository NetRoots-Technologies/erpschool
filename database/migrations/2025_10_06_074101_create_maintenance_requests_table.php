<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('building_id')->default(0); //building
            $table->integer('unit_id')->default(0); // unit model
            $table->integer('issue_type')->default(0); // type Model
            $table->integer('maintainer_id')->default(0);
            $table->string('status')->nullable();
            $table->float('amount')->default(0);
            $table->string('issue_attachment')->nullable();
            $table->string('invoice')->nullable();
            $table->date('request_date')->nullable();
            $table->date('fixed_date')->nullable();
            $table->text('notes')->nullable();
            $table->integer('user_id')->default(0); //User Model
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
        Schema::dropIfExists('maintenance_requests');
    }
}
