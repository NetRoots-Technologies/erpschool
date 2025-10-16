@extends('admin.layouts.main')

@section('title')
    Skill Evaluation
@endsection

@section('content')
    @if (Gate::allows('SkillEvaluation-create'))
        {
        <div class="row mt-4 mb-4">
            <div class="col-12 text-end">
                <a href="{{ route('exam.skill_evaluation.create') }}" class="btn btn-primary btn-md shadow-sm">
                    <i class="fas fa-plus"></i> <strong>Add Skill Evaluation</strong>
                </a>
            </div>
        </div>
        }
    @endif


    @if ($skillEvaluations->count())
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Skill Evaluations</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Subject</th>
                                    <th>Action</th> <!-- ✅ New Column -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($skillEvaluations as $index => $eval)
                                    @php
                                        $logs = json_decode($eval->logs);
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        {{-- Student Name --}}
                                        <td>{{ $eval->student->first_name }} {{ $eval->student->last_name }}</td>

                                        {{-- Class --}}
                                        <td>{{ $eval->student->AcademicClass->name ?? 'Class ' . ($logs->class_id ?? 'N/A') }}
                                        </td>

                                        {{-- Section --}}
                                        <td>{{ $eval->student->section->name ?? 'N/A' }}</td>

                                        {{-- Subject --}}
                                        <td>{{ $eval->subject->name ?? 'N/A' }}</td>

                                        {{-- ✅ Action Buttons --}}
                                        <td>
                                            <a href="{{ route('exam.listing', $eval->student->id) }}"
                                                class="btn btn-sm btn-warning" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <!-- You can add Edit/Delete icons here if needed -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <strong>No skill evaluations found.</strong>
                </div>
            </div>
        </div>
    @endif

@endsection
