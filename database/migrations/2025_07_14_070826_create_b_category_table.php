<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('b_category')) {

            Schema::create('b_category', function (Blueprint $table) {
                $table->id(); // Auto-incrementing primary key
                $table->string('title'); // Category title
                $table->timestamps(); // created_at and updated_at
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
        Schema::dropIfExists('b_category');
    }
}
