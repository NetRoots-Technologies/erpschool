@extends('admin.layouts.main')

@section('title')
    View Exam Detail
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> View Exam Detail</h3>
                    <div class="row mt-4 mb-4">
                        <div class="col-12 text-right">
                            <a href="{!! route('exam.exam_details.index') !!}" class="btn btn-primary btn-md">
                                Back </a>
                        </div>
                    </div>

                    <form action="{!! route('exam.exam_details.update', $examDetail->id) !!}" 
                          method="post" id="form_validation" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:20px;">
                                <div class="row mt-4 gy-4">
                                    
                                    <!-- Branch (Disabled) -->
                                    <div class="col-md-6">
                                        <label><b>Branch*</b></label>
                                        <input type="text" name="test_name" class="form-control test_name" 
                                               value="{{ $branch }}" disabled>
                                    </div>

                                    <!-- Class (Disabled) -->
                                    <div class="col-md-6">
                                        <label><b>Class*</b></label>
                                        <input type="text" name="test_name" class="form-control test_name" 
                                               value="{{ $class }}" disabled>
                                    </div>

                                    <!-- Test Type (Disabled) -->
                                    <div class="col-md-6">
                                        <label><b>Test Type*</b></label>
                                        <select class="form-control" disabled>
                                            <option value="E" {{ $examDetail->exam_term_id ? 'selected' : '' }}>Exam</option>
                                            <option value="T" {{ $examDetail->test_type_id ? 'selected' : '' }}>Test</option>
                                        </select>
                                    </div>

                                    <!-- Term / Exam Type (Disabled) -->
                                    <div class="col-md-6">
                                        <label><b>Term/Exam Type*</b></label>
                                        <select class="form-control">
                                            @if (isset($examDetail->exam_term_id ))
                                            @foreach ($examTypes as $item)
                                                <option value="{{ $item->id }}" 
                                                    {{ $examDetail->exam_term_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->progress_heading }}
                                                </option>
                                            @endforeach
                                            @endif
                                             @if (isset($examDetail->test_type_id  ))
                                            @foreach ($testTypes as $item)
                                                <option value="{{ $item->id }}" 
                                                    {{ $examDetail->test_type_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                            @endif
                                            
                                        </select>
                                    </div>

                                    <!-- Editable Test Name -->
                                    <div class="col-md-6">
                                        <label><b>Test Name*</b></label>
                                        <input type="text" name="test_name" class="form-control test_name" 
                                               value="{{ $examDetail->test_name }}" required>
                                    </div>

                                    <!-- Editable Initial -->
                                    <div class="col-md-6">
                                        <label><b>Initial</b></label>
                                        <input type="text" name="initial" class="form-control initial" 
                                               value="{{ $examDetail->initial }}" readonly>
                                    </div>

                                    <!-- Subject Rows -->
                                    <div class="col-12 mt-3">
                                        <label><b>Subjects & Marks</b></label>
                                        <div id="exam-rows" class="w-100">
                                                @foreach($marks as $mark)
                                                    <div class="exam-row row mb-3">
                                                        <!-- Subject -->
                                                        <div class="col-md-3">
                                                            <select name="subject_id[]" class="form-control select2 basic-single" required>
                                                                <option value="" disabled>Select Subject</option>
                                                                @foreach($subject as $subj)
                                                                    <option value="{{ $subj->id }}" 
                                                                        {{ $mark->course_id == $subj->id ? 'selected' : '' }}>
                                                                        {{ $subj->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Total Marks -->
                                                        <div class="col-md-2">
                                                            <input type="number" name="total_marks[]" class="form-control"
                                                                value="{{ $mark->totalMarks }}" required>
                                                        </div>

                                                        <!-- Passing % -->
                                                        <div class="col-md-2">
                                                            <input type="number" name="passing_percentage[]" class="form-control"
                                                                value="{{ $mark->passingPercentage }}" required>
                                                        </div>

                                                        <!-- Checkboxes -->
                                                        <div class="col-md-5 d-flex align-items-center">
                                                            <div class="form-check me-3">
                                                                <input type="checkbox" name="show_grade[]" value="1" 
                                                                    class="form-check-input" {{ $mark->showGrade ? 'checked' : '' }}>
                                                                <label class="form-check-label">Show Grade</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input type="checkbox" name="show_percentage[]" value="1" 
                                                                    class="form-check-input" {{ $mark->showPercentage ? 'checked' : '' }}>
                                                                <label class="form-check-label">Show %</label>
                                                            </div>
                                                            <div class="form-check me-3">
                                                                <input type="checkbox" name="show_pass_fail[]" value="1" 
                                                                    class="form-check-input" {{ $mark->passOrFail ? 'checked' : '' }}>
                                                                <label class="form-check-label">Show Pass/Fail</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                    </div>
                                   

                                    <!-- Submit -->
                                    {{-- <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div> --}}

                                </div> <!-- row -->
                            </div> <!-- box-body -->
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function () {
        // Auto-generate initials from test name
        $('.test_name').on('input', function () {
            let test_name = $(this).val();
            $('.initial').val(test_name.substring(0, 3));
        });
    });
</script>
@endsection
