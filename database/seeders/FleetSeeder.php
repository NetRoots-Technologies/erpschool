<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fleet\Driver;
use App\Models\Fleet\Vehicle;
use App\Models\Fleet\Route;
use App\Models\Fleet\RoutePoint;
use App\Models\Fleet\Maintenance;
use App\Models\Fleet\Fuel;
use App\Models\Fleet\Expense;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create or get company and branch
        $company = Company::first();
        if (!$company) {
            $company = Company::create([
                'name' => 'School Management System',
                'status' => 'active',
            ]);
            $this->command->info('Created company: ' . $company->name);
        }

        $branch = Branch::first();
        if (!$branch) {
            $branch = Branch::create([
                'name' => 'Main Campus',
                'address' => 'Karachi, Pakistan',
                'company_id' => $company->id,
                'status' => 1,
            ]);
            $this->command->info('Created branch: ' . $branch->name);
        }

        // Create Drivers
        $drivers = [
            [
                'driver_name' => 'Ahmed Ali',
                'driver_phone' => '0300-1234567',
                'driver_cnic' => '12345-1234567-1',
                'license_number' => 'LIC001',
                'license_expiry' => now()->addYear(),
                'address' => 'House 123, Block A, Karachi',
                'salary' => 25000,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
            [
                'driver_name' => 'Muhammad Hassan',
                'driver_phone' => '0300-2345678',
                'driver_cnic' => '23456-2345678-2',
                'license_number' => 'LIC002',
                'license_expiry' => now()->addMonths(8),
                'address' => 'House 456, Block B, Karachi',
                'salary' => 28000,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
            [
                'driver_name' => 'Ali Raza',
                'driver_phone' => '0300-3456789',
                'driver_cnic' => '34567-3456789-3',
                'license_number' => 'LIC003',
                'license_expiry' => now()->addMonths(6),
                'address' => 'House 789, Block C, Karachi',
                'salary' => 26000,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
        ];

        foreach ($drivers as $driverData) {
            Driver::create($driverData);
        }

        // Create Vehicles
        $vehicles = [
            [
                'vehicle_number' => 'KHI-2024-001',
                'vehicle_type' => 'bus',
                'driver_id' => Driver::first()->id,
                'capacity' => 50,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'notes' => 'Main school bus for route 1',
            ],
            [
                'vehicle_number' => 'KHI-2024-002',
                'vehicle_type' => 'bus',
                'driver_id' => Driver::skip(1)->first()->id,
                'capacity' => 45,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'notes' => 'Secondary bus for route 2',
            ],
            [
                'vehicle_number' => 'KHI-2024-003',
                'vehicle_type' => 'van',
                'driver_id' => Driver::skip(2)->first()->id,
                'capacity' => 15,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'notes' => 'Small van for nearby areas',
            ],
        ];

        foreach ($vehicles as $vehicleData) {
            Vehicle::create($vehicleData);
        }

        // Create Routes
        $routes = [
            [
                'route_name' => 'Route 1 - North Karachi',
                'vehicle_id' => Vehicle::first()->id,
                'start_point' => 'School',
                'end_point' => 'North Karachi',
                'total_distance' => 25.5,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'notes' => 'Main route covering North Karachi areas',
            ],
            [
                'route_name' => 'Route 2 - Gulshan-e-Iqbal',
                'vehicle_id' => Vehicle::skip(1)->first()->id,
                'start_point' => 'School',
                'end_point' => 'Gulshan-e-Iqbal',
                'total_distance' => 18.2,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'notes' => 'Route covering Gulshan-e-Iqbal and surrounding areas',
            ],
            [
                'route_name' => 'Route 3 - Clifton',
                'vehicle_id' => Vehicle::skip(2)->first()->id,
                'start_point' => 'School',
                'end_point' => 'Clifton',
                'total_distance' => 12.8,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'notes' => 'Short route for Clifton area',
            ],
        ];

        foreach ($routes as $routeData) {
            Route::create($routeData);
        }

        // Create Route Points with different charges (aapke requirement ke according)
        $routePoints = [
            // Route 1 Points
            [
                'route_id' => Route::first()->id,
                'point_name' => 'North Nazimabad',
                'point_address' => 'Block A, North Nazimabad, Karachi',
                'latitude' => 24.9500,
                'longitude' => 67.0333,
                'sequence_order' => 1,
                'distance_from_previous' => 0,
                'charges' => 0,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
            [
                'route_id' => Route::first()->id,
                'point_name' => 'Federal B Area',
                'point_address' => 'Block 6, Federal B Area, Karachi',
                'latitude' => 24.9200,
                'longitude' => 67.0500,
                'sequence_order' => 2,
                'distance_from_previous' => 3.2,
                'charges' => 1500, // Different charge for this point
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
            [
                'route_id' => Route::first()->id,
                'point_name' => 'Gulberg',
                'point_address' => 'Block 2, Gulberg, Karachi',
                'latitude' => 24.9000,
                'longitude' => 67.0700,
                'sequence_order' => 3,
                'distance_from_previous' => 2.8,
                'charges' => 2000, // Different charge for this point
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
            // Route 2 Points
            [
                'route_id' => Route::skip(1)->first()->id,
                'point_name' => 'Gulshan-e-Iqbal Block 6',
                'point_address' => 'Block 6, Gulshan-e-Iqbal, Karachi',
                'latitude' => 24.8800,
                'longitude' => 67.0900,
                'sequence_order' => 1,
                'distance_from_previous' => 0,
                'charges' => 0,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
            [
                'route_id' => Route::skip(1)->first()->id,
                'point_name' => 'Gulshan-e-Iqbal Block 2',
                'point_address' => 'Block 2, Gulshan-e-Iqbal, Karachi',
                'latitude' => 24.8600,
                'longitude' => 67.1100,
                'sequence_order' => 2,
                'distance_from_previous' => 2.5,
                'charges' => 1200, // Different charge for this point
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
            // Route 3 Points
            [
                'route_id' => Route::skip(2)->first()->id,
                'point_name' => 'Clifton Block 2',
                'point_address' => 'Block 2, Clifton, Karachi',
                'latitude' => 24.8400,
                'longitude' => 67.1300,
                'sequence_order' => 1,
                'distance_from_previous' => 0,
                'charges' => 0,
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
            [
                'route_id' => Route::skip(2)->first()->id,
                'point_name' => 'Defence Phase 2',
                'point_address' => 'Phase 2, Defence, Karachi',
                'latitude' => 24.8200,
                'longitude' => 67.1500,
                'sequence_order' => 2,
                'distance_from_previous' => 1.8,
                'charges' => 1800, // Different charge for this point
                'status' => 'active',
                'company_id' => $company->id,
                'branch_id' => $branch->id,
            ],
        ];

        foreach ($routePoints as $pointData) {
            RoutePoint::create($pointData);
        }

        $this->command->info('Fleet management data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- 3 Drivers');
        $this->command->info('- 3 Vehicles');
        $this->command->info('- 3 Routes');
        $this->command->info('- 7 Route Points with different charges');
    }
}
