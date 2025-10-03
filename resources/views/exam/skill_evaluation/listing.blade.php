@extends('admin.layouts.main')

@section('title')
    Skill Evaluation Details
@endsection

@section('content')

<div class="row mt-4 mb-4">
    <div class="col-12">
        <a href="{{ route('exam.skill_evaluation.index') }}" class="btn btn-secondary btn-md shadow-sm">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

@if($skillEvaluations->count())
    @php
        $student = $skillEvaluations->first()->student ?? null;
        $subject = $skillEvaluations->first()->subject ?? null;
    @endphp

    <div class="row mb-3">
        <div class="col-md-6">
            <h5><strong>Student Name:</strong> {{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</h5>
            <p><strong>Class:</strong> {{ $student->AcademicClass->name ?? 'N/A' }}</p>
            <p><strong>Section:</strong> {{ $student->section->name ?? 'N/A' }}</p>
            <p><strong>Subject:</strong> {{ $subject->name ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Skill Evaluation Records</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Skill</th>
                        <th>Skill Group</th>
                        <th>Skill Evaluation Key</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($skillEvaluations as $index => $eval)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $eval->skill->name ?? 'N/A' }}</td>
                            <td>{{ $eval->groupskill->skill_group ?? 'N/A' }}</td>
                            <td>{{ $eval->skillEvaluationKeys->abbr ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="row mt-5">
        <div class="col-12 text-center">
            <div class="alert alert-info">
                <strong>No evaluations found for this student.</strong>
            </div>
        </div>
    </div>
@endif

@endsection
