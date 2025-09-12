<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiveSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('active_sessions')) {

            Schema::create('active_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('session_id');
                $table->unsignedInteger('company_id');
                $table->unsignedInteger('branch_id');
                $table->unsignedInteger('class_id');
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
        Schema::dropIfExists('active_sessions');
    }
}
