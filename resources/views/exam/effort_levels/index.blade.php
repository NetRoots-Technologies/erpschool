@extends('admin.layouts.main')

@section('title')
    Effort Levels
@endsection

@section('content')
<div class="container-fluid">

    <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                {{-- @if (Gate::allows('ExamTerms-create')) --}}

                <a href="{!! route('exam.effort_levels.create') !!}" class="btn btn-primary btn-md"><b>Add effort levels</b></a>
                {{-- @endif --}}
            </div>
        </div>


    <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Effort Levels List</h3>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
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
                    {{-- <th>Actions</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse($effortLevels as $index => $effort)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ optional($effort->student->branch->company)->name }}</td>
                        <td>{{ optional($effort->student->branch)->name }}</td>
                        <td>{{ optional($effort->student->class)->name }}</td>
                        <td>{{ optional($effort->student->section)->name }}</td>
                        <td>{{ optional($effort->student)->full_name }}</td>
                        <td>{{ optional($effort->course)->name }}</td>
                        <td>{{ $effort->effort }}</td>
                        <td>
                            @switch($effort->level)
                                @case(3) 3 - Fully Meets Expectations @break
                                @case(2) 2 - Meets Expectations @break
                                @case(1) 1 - Minimally Meets Expectations @break
                                @default Unknown
                            @endswitch
                        </td>
                        <td>{{ $effort->created_at->format('Y-m-d H:i') }}</td>
                        {{-- <td>
                            <a href="{{ route('exam.effort_levels.edit', $effort->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('exam.effort_levels.destroy', $effort->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
