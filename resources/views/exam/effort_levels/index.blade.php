@extends('admin.layouts.main')

@section('title')
    Effort Levels
@endsection

@section('content')
<div class="container-fluid mt-4">

    <!-- 🔹 Header Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h3 class="mb-0 text-primary fw-bold">Effort Levels List</h3>
            <a href="{{ route('exam.effort_levels.create') }}" class="btn btn-primary btn-md">
                <i class="fa fa-plus-circle me-2"></i><b>Add Effort Levels</b>
            </a>
        </div>
    </div>

    <!-- 🔹 Data Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Effort Level</th>
                            <th>Achievement Level</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($effortLevels as $index => $effort)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ optional($effort->student->branch->company)->name }}</td>
                                <td>{{ optional($effort->student->branch)->name }}</td>
                                <td>{{ optional($effort->student->class)->name }}</td>
                                <td>{{ optional($effort->student->section)->name }}</td>
                                <td>{{ optional($effort->student)->full_name }}</td>
                                <td>{{ optional($effort->course)->name }}</td>
                                <td>
                                    <span class="badge bg-info text-dark px-3 py-2">
                                        {{ $effort->effort }}
                                    </span>
                                </td>
                                <td>
                                    @switch($effort->level)
                                        @case(3)
                                            <span class="badge bg-success px-3 py-2">3 - Fully Meets Expectations</span>
                                            @break
                                        @case(2)
                                            <span class="badge bg-warning text-dark px-3 py-2">2 - Meets Expectations</span>
                                            @break
                                        @case(1)
                                            <span class="badge bg-danger px-3 py-2">1 - Minimally Meets Expectations</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary px-3 py-2">Unknown</span>
                                    @endswitch
                                </td>
                                <td>{{ $effort->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    .table th, .table td {
        vertical-align: middle !important;
    }
    .table thead th {
        background-color: #007bff !important;
        color: #fff;
    }
</style>
@endsection
