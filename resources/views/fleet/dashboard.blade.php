@extends('admin.layouts.main')

@section('title', 'Fleet Management Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Fleet Management Dashboard</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Fleet Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Total Vehicles</p>
                            <h4 class="mb-0">{{ $stats['total_vehicles'] ?? 0 }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary bg-soft">
                                <span class="avatar-title rounded-circle bg-primary font-size-18">
                                    <i class="fa fa-bus"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Active Drivers</p>
                            <h4 class="mb-0">{{ $stats['active_drivers'] ?? 0 }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success bg-soft">
                                <span class="avatar-title rounded-circle bg-success font-size-18">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Active Routes</p>
                            <h4 class="mb-0">{{ $stats['active_routes'] ?? 0 }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info bg-soft">
                                <span class="avatar-title rounded-circle bg-info font-size-18">
                                    <i class="fa fa-route"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1 overflow-hidden">
                            <p class="text-truncate font-size-14 mb-2">Students Transported</p>
                            <h4 class="mb-0">{{ $stats['students_transported'] ?? 0 }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning bg-soft">
                                <span class="avatar-title rounded-circle bg-warning font-size-18">
                                    <i class="fa fa-users"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('fleet.vehicles.create') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fa fa-plus me-2"></i>Add Vehicle
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('fleet.drivers.create') }}" class="btn btn-success btn-lg w-100">
                                <i class="fa fa-user-plus me-2"></i>Add Driver
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('fleet.routes.create') }}" class="btn btn-info btn-lg w-100">
                                <i class="fa fa-route me-2"></i>Create Route
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('fleet.maintenance.create') }}" class="btn btn-warning btn-lg w-100">
                                <i class="fa fa-wrench me-2"></i>Schedule Maintenance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Maintenance</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Cost</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_maintenance ?? [] as $maintenance)
                                <tr>
                                    <td>{{ $maintenance->vehicle->vehicle_number ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($maintenance->maintenance_type) }}</td>
                                    <td>{{ $maintenance->maintenance_date->format('d M Y') }}</td>
                                    <td>Rs. {{ number_format($maintenance->cost, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $maintenance->status == 'completed' ? 'success' : ($maintenance->status == 'in_progress' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($maintenance->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No recent maintenance records</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Vehicle Status</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-success">{{ $stats['active_vehicles'] ?? 0 }}</h3>
                                <p class="text-muted">Active</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h3 class="text-warning">{{ $stats['maintenance_vehicles'] ?? 0 }}</h3>
                                <p class="text-muted">In Maintenance</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Expenses Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Monthly Expenses</h4>
                </div>
                <div class="card-body">
                    <canvas id="expensesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Expenses Chart
    const ctx = document.getElementById('expensesChart').getContext('2d');
    const expensesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthly_expenses['labels'] ?? []) !!},
            datasets: [{
                label: 'Fuel Expenses',
                data: {!! json_encode($monthly_expenses['fuel'] ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Maintenance Expenses',
                data: {!! json_encode($monthly_expenses['maintenance'] ?? []) !!},
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
