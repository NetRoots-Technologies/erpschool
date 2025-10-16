@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Driver Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.drivers.edit', $driver->id) }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('fleet.drivers.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">Driver Name</th>
                                        <td>{{ $driver->driver_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number</th>
                                        <td>{{ $driver->driver_phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>CNIC</th>
                                        <td>{{ $driver->driver_cnic }}</td>
                                    </tr>
                                    <tr>
                                        <th>License Number</th>
                                        <td>{{ $driver->license_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>License Expiry</th>
                                        <td>
                                            {{ \Carbon\Carbon::parse($driver->license_expiry)->format('d M Y') }}
                                            @if(\Carbon\Carbon::parse($driver->license_expiry)->isPast())
                                                <span class="badge badge-danger">Expired</span>
                                            @elseif(\Carbon\Carbon::parse($driver->license_expiry)->diffInDays(now()) <= 30)
                                                <span class="badge badge-warning">Expiring Soon</span>
                                            @else
                                                <span class="badge badge-success">Valid</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="40%">Salary</th>
                                        <td>Rs. {{ number_format($driver->salary, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($driver->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $driver->address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $driver->created_at->format('d M Y H:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $driver->updated_at->format('d M Y H:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($driver->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Notes:</strong><br>
                                {{ $driver->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <form action="{{ route('fleet.drivers.destroy', $driver->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this driver?');" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-trash"></i> Delete Driver
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

