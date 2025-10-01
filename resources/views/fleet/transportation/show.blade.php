@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transportation Assignment Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.transportation.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Transportation
                        </a>
                        <a href="{{ route('fleet.transportation.edit', $transportation->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Student Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fa fa-user"></i> Student Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Student Name:</th>
                                    <td>
                                        <strong>{{ $transportation->student->first_name }} {{ $transportation->student->last_name }}</strong><br>
                                        <small class="text-muted">{{ $transportation->student->student_id }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Class:</th>
                                    <td>{{ $transportation->student->AcademicClass ? $transportation->student->AcademicClass->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Session:</th>
                                    <td>{{ $transportation->student->academicSession ? $transportation->student->academicSession->name : 'Not Assigned' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $transportation->student->cell_no ?? 'Not Available' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fa fa-bus"></i> Transportation Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Vehicle:</th>
                                    <td>
                                        <strong>{{ $transportation->vehicle->vehicle_number }}</strong><br>
                                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $transportation->vehicle->vehicle_type)) }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Route:</th>
                                    <td>
                                        <strong>{{ $transportation->route->route_name }}</strong><br>
                                        <small class="text-muted">{{ $transportation->route->start_point }} → {{ $transportation->route->end_point }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pickup Point:</th>
                                    <td>{{ $transportation->pickup_point }}</td>
                                </tr>
                                <tr>
                                    <th>Drop-off Point:</th>
                                    <td>{{ $transportation->dropoff_point }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Financial Information -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5><i class="fa fa-money"></i> Financial Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Monthly Charges:</th>
                                    <td>
                                        <span class="badge badge-success" style="font-size: 14px; color: #000 !important;">
                                            Rs. {{ number_format($transportation->monthly_charges, 2) }} / Month
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($transportation->status == 'active')
                                            <span class="badge badge-success" style="color: #000 !important;">Active</span>
                                        @else
                                            <span class="badge badge-secondary" style="color: #000 !important;">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Start Date:</th>
                                    <td>{{ $transportation->start_date ? $transportation->start_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>End Date:</th>
                                    <td>{{ $transportation->end_date ? $transportation->end_date->format('d M Y') : 'Ongoing' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5><i class="fa fa-info-circle"></i> Additional Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Notes:</th>
                                    <td>{{ $transportation->notes ?? 'No notes available' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $transportation->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $transportation->updated_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
