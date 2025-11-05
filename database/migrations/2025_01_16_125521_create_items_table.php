<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('items')) {

            Schema::create('items', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->boolean('status')->default(1);
                $table->string('measuring_unit')->nullable();
                $table->enum('type', ['F', 'S', 'U'])->default('F');
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
        Schema::dropIfExists('items');
    }
}
