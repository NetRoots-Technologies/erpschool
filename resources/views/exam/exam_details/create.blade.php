@extends('admin.layouts.main')

@section('title')
Exam Detail Create
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">
<style>
    /* keep your original behavior: hide term/exam selects initially */
    .col-md-6:has(#testTypeId),
    .col-md-6:has(#examTypeId) {
        display: none;
    }

    .col-12:has(#testType) {
        transition: 0.5s ease
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create Exam Detail</h3>

                    <form action="{!! route('exam.exam_details.store') !!}" enctype="multipart/form-data"
                        id="form_validation" autocomplete="off" method="post">
                        @csrf
                        <div class="row gy-4">
                            <!-- Branch -->
                            <div class="col-md-6">
                                <label><b>Branch*</b></label>
                                <select name="branchId" id="branchId" class="form-control select2 basic-single" required>
                                    <option value="" disabled selected>Select Branch</option>
                                    @foreach ($branches as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Class -->
                            <div class="col-md-6">
                                <label><b>Class*</b></label>
                                <select name="classId" id="classId" class="form-control select2 basic-single" required>
                                    <option value="" disabled selected>Select Class</option>
                                </select>
                            </div>

                            <!-- Test Type -->
                            <div class="col-md-6">
                                <label><b>Test Type*</b></label>
                                <select name="testType" id="testType" class="form-control select2 basic-single" required>
                                    <option value="" disabled selected>Select Test Type</option>
                                    <option value="E">Exam</option>
                                    <option value="T">Test</option>
                                </select>
                            </div>

                            <!-- Term -->
                            <div class="col-md-6">
                                <label><b>Term Type*</b></label>
                                <select name="testTypeId" id="testTypeId" class="form-control select2 basic-single">
                                    <option value="" disabled selected>Select Term</option>
                                    @foreach($testTypes as $item)
                                        <option value="{!! $item->id !!}">{!! $item->name !!}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Exam -->
                            <div class="col-md-6">
                                <label><b>Exam Type*</b></label>
                                <select name="examTypeId" id="examTypeId" class="form-control select2 basic-single">
                                    <option value="" disabled selected>Select Exam Type</option>
                                    @foreach($examTypes as $item)
                                        <option value="{!! $item->id !!}">{!! $item->progress_heading !!}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Test Name -->
                            <div class="col-md-6">
                                <label><b>Test Name*</b></label>
                                <input type="text" name="test_name" class="form-control test_name" required>
                            </div>

                            <!-- Initial -->
                            <div class="col-md-6">
                                <label><b>Initial</b></label>
                                <input type="text" name="initial" readonly class="form-control initial">
                            </div>

                            <!-- ================= Subject Rows ================= -->
                            <div class="col-12">
                                <label><b>Subjects & Marks</b></label>
                            </div>

                            <div id="exam-rows" class="w-100">
                                <div class="exam-row row mb-3">
                                    <!-- Subject -->
                                    <div class="col-md-3">
                                        <select name="rows[0][subject_id]" class="form-control subject-input select2" required>
                                            <option value="" disabled selected>Select Subject</option>
                                        </select>
                                    </div>

                                    <!-- Total Marks -->
                                    <div class="col-md-2">
                                        <input type="number" name="rows[0][total_marks]" class="form-control" placeholder="Total Marks" required>
                                    </div>

                                    <!-- Passing % -->
                                    <div class="col-md-2">
                                        <input type="number" name="rows[0][passing_percentage]" class="form-control" placeholder="Passing %" required>
                                    </div>

                                    <!-- Checkboxes -->
                                    <div class="col-md-5 d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input type="checkbox" name="rows[0][show_grade]" value="1" class="form-check-input">
                                            <label class="form-check-label">Show Grade</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input type="checkbox" name="rows[0][show_percentage]" value="1" class="form-check-input">
                                            <label class="form-check-label">Show %</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input type="checkbox" name="rows[0][show_pass_fail]" value="1" class="form-check-input">
                                            <label class="form-check-label">Show Pass/Fail</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add More -->
                            <div class="col-12 mb-3">
                                <button type="button" class="btn btn-success" id="addRow">+ Add More</button>
                            </div>

                            <!-- Submit -->
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
<script>
$(document).ready(function () {

    // Initialize Select2 for all existing selects
    function initSelect2(scope) {
        (scope || $(document)).find('.select2').each(function () {
            // destroy if already initialized (safe)
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
            $(this).select2({
                placeholder: 'Select an option',
                width: '100%'
            });
        });
    }
    initSelect2();

    // Auto-generate initials
    $('.test_name').on('input', function () {
        $('.initial').val($(this).val().substring(0, 3));
    });

    // Toggle term/exam dropdowns based on Test Type
    $('#testType').on('change', function () {
        let val = $(this).val();
        // if Exam -> hide Term (testTypeId) and show Exam (examTypeId)
        if (val === 'E' || val === 'e') {
            $('#testTypeId').prop('disabled', true).closest('.col-md-6').slideUp(function () {
                $('#examTypeId').prop('disabled', false).closest('.col-md-6').slideDown();
                // init select2 in case it was hidden
                initSelect2($('#examTypeId').closest('.col-md-6'));
            });
        } else if (val === 'T' || val === 't') {
            $('#examTypeId').prop('disabled', true).closest('.col-md-6').slideUp(function () {
                $('#testTypeId').prop('disabled', false).closest('.col-md-6').slideDown();
                initSelect2($('#testTypeId').closest('.col-md-6'));
            });
        } else {
            // show both if unknown
            $('#examTypeId, #testTypeId').prop('disabled', false).closest('.col-md-6').slideDown();
        }
    });

    // Fetch classes when branch changes
    $('#branchId').on('change', function () {
        const branchId = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route("academic.fetchClass") }}',
            data: { branch_id: branchId },
            success: function (data) {
                let $class = $('#classId').empty();
                $class.append('<option selected disabled value="">Select Class</option>');
                data.forEach(function (cls) {
                    $class.append('<option value="' + cls.id + '">' + cls.name + '</option>');
                });
                // re-init select2 (or update)
                initSelect2($('#classId').closest('form'));
                // clear subject selects because class changed
                $('.subject-input').each(function () {
                    $(this).empty().append('<option selected disabled value="">Select Subject</option>').val(null).trigger('change');
                });
            },
            error: function (error) {
                console.error('Error fetching classes:', error);
            }
        });
    });

    // Fetch subjects when class changes and populate ALL subject-input selects
    $('#classId').on('change', function () {
        const classId = $(this).val();

        $.ajax({
            type: 'GET',
            url: '{{ route("academic.fetchSubject") }}',
            data: { id: classId },
            success: function (data) {
                // build options HTML once
                let options = '<option selected disabled value="">Select Subject</option>';
                data.forEach(function (s) {
                    options += '<option value="' + s.id + '">' + s.name + '</option>';
                });

                // set options for every subject-select (so new rows will clone filled select)
                $('.subject-input').each(function () {
                    // keep current selection if still present
                    const current = $(this).val();
                    $(this).html(options);
                    if (current) {
                        $(this).val(current);
                    }
                    $(this).trigger('change');
                });

            },
            error: function (error) {
                console.error('Error fetching subjects:', error);
            }
        });
    });

    // Add row button - clone first row, update names and re-init select2
    let rowIndex = $('#exam-rows .exam-row').length; // start from existing count (first row is index 0)
    $('#addRow').on('click', function () {
        const $wrapper = $('#exam-rows');
        const $firstRow = $wrapper.find('.exam-row').first();
        const $clone = $firstRow.clone();

        // Remove old select2 containers to avoid duplicates
        $clone.find('.select2-container').remove();
        $clone.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id');

        // Update names with new index & clear values
        $clone.find('select, input').each(function () {
            const name = $(this).attr('name');
            if (name) {
                // replace the first numeric index occurrence, e.g. rows[0] -> rows[1]
                const newName = name.replace(/\[\d+\]/, '[' + rowIndex + ']');
                $(this).attr('name', newName);
            }

            // Clear values: checkboxes unchecked, other inputs emptied
            if ($(this).is(':checkbox')) {
                $(this).prop('checked', false);
            } else {
                $(this).val('');
            }
        });

        // Append cloned row
        $wrapper.append($clone);

        // Reinitialize select2 only for selects inside the appended clone
        initSelect2($clone);

        // If class is already selected, clone should contain subject options (we cloned first row which was pre-filled),
        // otherwise subject-select will have only default option until class is selected.
        rowIndex++;
    });

});
</script>
@endsection
