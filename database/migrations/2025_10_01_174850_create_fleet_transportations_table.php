<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetTransportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fleet_transportations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('route_point_id')->nullable();
            $table->string('pickup_point');
            $table->string('dropoff_point');
            $table->decimal('monthly_charges', 10, 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('fleet_vehicles')->onDelete('cascade');
            $table->foreign('route_id')->references('id')->on('fleet_routes')->onDelete('cascade');
            $table->foreign('route_point_id')->references('id')->on('fleet_route_points')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            // Indexes
            $table->index(['student_id', 'status']);
            $table->index(['vehicle_id', 'status']);
            $table->index(['route_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fleet_transportations');
    }
}
