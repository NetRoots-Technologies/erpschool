<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetFuelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_fuel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->date('fuel_date');
            $table->enum('fuel_type', ['petrol', 'diesel', 'cng', 'electric'])->default('petrol');
            $table->decimal('quantity', 8, 2);
            $table->decimal('rate_per_liter', 8, 2);
            $table->decimal('total_cost', 10, 2);
            $table->string('fuel_station')->nullable();
            $table->integer('odometer_reading')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vehicle_id')->references('id')->on('fleet_vehicles')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('fleet_drivers')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fleet_fuel');
    }
}
