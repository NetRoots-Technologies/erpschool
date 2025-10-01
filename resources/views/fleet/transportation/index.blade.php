@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Transportation</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.transportation.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Assign Transportation
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Vehicle</th>
                                    <th>Route</th>
                                    <th>Charges</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transportations as $transportation)
                                <tr>
                                    <td>{{ $transportation->id }}</td>
                                    <td>
                                        <strong>{{ $transportation->student->first_name }} {{ $transportation->student->last_name }}</strong><br>
                                        <small class="text-muted">{{ $transportation->student->student_id }}</small>
                                    </td>
                                    <td>{{ $transportation->student->AcademicClass ? $transportation->student->AcademicClass->name : 'N/A' }}</td>
                                    <td>
                                        {{ $transportation->vehicle->vehicle_number }}<br>
                                        <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $transportation->vehicle->vehicle_type)) }}</small>
                                    </td>
                                    <td>
                                        {{ $transportation->route->route_name }}<br>
                                        <small class="text-muted">{{ $transportation->pickup_point }} → {{ $transportation->dropoff_point }}</small>
                                    </td>
                                    <td>
                                        <strong>Rs. {{ number_format($transportation->monthly_charges, 2) }}</strong><br>
                                        <small class="text-muted">per month</small>
                                    </td>
                                    <td>
                                        @if($transportation->status == 'active')
                                            <span class="badge badge-success" style="color: #000 !important;">Active</span>
                                        @else
                                            <span class="badge badge-secondary" style="color: #000 !important;">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('fleet.transportation.show', $transportation->id) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('fleet.transportation.edit', $transportation->id) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('fleet.transportation.destroy', $transportation->id) }}" 
                                                  method="POST" style="display: inline-block;" 
                                                  onsubmit="return confirm('Are you sure you want to delete this transportation assignment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No transportation assignments found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
