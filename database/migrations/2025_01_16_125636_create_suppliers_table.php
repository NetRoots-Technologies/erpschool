<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('suppliers')) {

            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255)->nullable();
                $table->string('number', 255)->nullable();
                $table->string('company_name', 255)->nullable();
                $table->string('contact', 255)->nullable();
                $table->string('address', 255)->nullable();
                $table->string('email', 255)->nullable();
                $table->integer('rating')->default(0);
                $table->boolean('status')->default(1);
                $table->enum('type', ['F', 'S'])->default('F');
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
        Schema::dropIfExists('suppliers');
    }
}
