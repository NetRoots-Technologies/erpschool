@extends('admin.layouts.main')

@section('title')
    Attendance Result
@stop

@section('content')
<div class="container">
    <h3 class="mb-4">
        Attendance for {{ $attendance->AcademicClass->name ?? 'N/A' }} - {{ $attendance->section->name ?? 'N/A' }} on {{ $attendance->attendance_date }}
    </h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sr.#</th>
                <th>Class</th>
                <th>Section</th>
                <th>Student Name</th>
                <th>Attendance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceData as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $attendance->AcademicClass->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->section->name ?? 'N/A' }}</td>
                    <td>{{ ($record->student->first_name ?? '') . ' ' . ($record->student->last_name ?? '') }}</td>
                    <td>   Present
                        {{-- @if($record->attendance == 'P')
                            <span class="badge badge-success">Present</span>
                        @elseif($record->attendance == 'A')
                            <span class="badge badge-danger">Absent</span>
                        @elseif($record->attendance == 'L')
                            <span class="badge badge-warning">Leave</span>
                        @else
                            <span class="badge badge-secondary">-</span>
                        @endif --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
