@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Student Transportation Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('fleet.transportation.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left"></i> Back to Transportation
                        </a>
                        <a href="{{ route('fleet.transportation.edit', $student->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Student Name:</th>
                                    <td>
                                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong><br>
                                        <small class="text-muted">{{ $student->student_id }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Class:</th>
                                    <td>{{ $student->AcademicClass ? $student->AcademicClass->name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Session:</th>
                                    <td>{{ $student->academicSession ? $student->academicSession->name : 'Not Assigned' }}</td>
                                </tr>
                                <tr>
                                    <th>Transportation Fee:</th>
                                    <td>
                                        <span class="badge badge-info" style="color: #000 !important;">
                                            Rs. {{ $student->transport_fee ?? '0' }} / Month
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Phone:</th>
                                    <td>{{ $student->cell_no ?? 'Not Available' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $student->student_email ?? 'Not Available' }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $student->student_current_address ?? 'Not Available' }}</td>
                                </tr>
                                <tr>
                                    <th>Created:</th>
                                    <td>{{ $student->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Transportation Assignment Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Transportation Assignment:</h5>
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i>
                                No transportation assignment found for this student. Click "Edit" to assign transportation.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
