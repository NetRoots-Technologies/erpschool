<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('sections')) {

            Schema::create('sections', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('class_id');
                $table->unsignedBigInteger('session_id');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('active_session_id');
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
        Schema::dropIfExists('sections');
    }
}
