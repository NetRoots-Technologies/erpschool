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
                                    <th>Session</th>
                                    <th>Transport Fee</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td>{{ $student->id }}</td>
                                    <td>
                                        <strong>{{ $student->first_name }} {{ $student->last_name }}</strong><br>
                                        <small class="text-muted">{{ $student->student_id }}</small>
                                    </td>
                                    <td>{{ $student->AcademicClass ? $student->AcademicClass->name : 'N/A' }}</td>
                                    <td>{{ $student->academicSession ? $student->academicSession->name : 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-info" style="color: #000 !important;">
                                            Rs. {{ $student->transport_fee ?? '0' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('fleet.transportation.show', $student->id) }}" 
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('fleet.transportation.edit', $student->id) }}" 
                                               class="btn btn-warning btn-sm" title="Assign Transport">
                                                <i class="fa fa-bus"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No students requiring transport found</td>
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
