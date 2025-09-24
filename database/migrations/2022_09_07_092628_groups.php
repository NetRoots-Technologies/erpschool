<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Groups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
    $table->id();
    $table->string('name', 255);
    $table->string('code', 255)->nullable();
    $table->unsignedInteger('level')->default(1);
    $table->unsignedInteger('parent_id')->default(0);
    $table->unsignedInteger('account_type_id')->nullable();
    $table->unsignedTinyInteger('status')->default(1);
    $table->unsignedInteger('created_by')->nullable();
    $table->unsignedInteger('updated_by')->nullable();
    $table->unsignedInteger('deleted_by')->nullable();
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
        //
    }
}
