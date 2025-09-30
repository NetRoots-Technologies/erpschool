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
                                    <td>{{ $student->academicClass ? $student->academicClass->class_name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Session:</th>
                                    <td>{{ $student->academicSession ? $student->academicSession->session_name : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Transport Required:</th>
                                    <td>
                                        @if($student->transport_required)
                                            <span class="badge badge-success" style="color: #000 !important;">Yes</span>
                                        @else
                                            <span class="badge badge-secondary" style="color: #000 !important;">No</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Phone:</th>
                                    <td>{{ $student->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $student->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $student->address ?? 'N/A' }}</td>
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
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                Transportation assignment details will be managed here once the student_transportations table is created.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
