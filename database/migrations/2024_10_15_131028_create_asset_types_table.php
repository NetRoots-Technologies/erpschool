<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('asset_types')) {

            Schema::create('asset_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->double('depreciation')->nullable()->default(10);
                $table->string('abbreviation')->nullable();
                $table->softDeletes();
                $table->timestamps();
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
        Schema::dropIfExists('asset_types');
    }
}
