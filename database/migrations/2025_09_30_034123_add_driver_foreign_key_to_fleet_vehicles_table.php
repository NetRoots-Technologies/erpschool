<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDriverForeignKeyToFleetVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fleet_vehicles', function (Blueprint $table) {
            $table->foreign('driver_id')->references('id')->on('fleet_drivers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fleet_vehicles', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
        });
    }
}
