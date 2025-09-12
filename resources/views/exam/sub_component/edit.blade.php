@extends('admin.layouts.main')

@section('title')
    Sub Component Edit
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4">Update Sub Component</h3>

                        <div class="row mt-4 mb-4">
                            <div class="col-12 text-right">
                                <a href="{{ route('exam.sub_components.index') }}" class="btn btn-primary btn-md">Back</a>
                            </div>
                        </div>

                        <form action="{{ route('exam.sub_components.update', $subComponent->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            @method('PUT')

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label><b>Company:</b></label>
                                    <select name="company_id" class="form-select select2 disable_select" id="companySelect" required>
                                        @foreach($companies as $item)
                                            <option value="{{ $item->id }}" {{ $subComponent->component->company_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label><b>Academic Session:</b></label>
                                    <select name="session_id" class="form-select select2 disable_select session_select" required>
                                        <option value="" disabled>Select Session</option>
                                        @foreach($sessions as $key => $item)
                                            <option value="{{ $key }}" {{ $subComponent->component->session_id == $key ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label><b>Branch:</b></label>
                                    <select name="branch_id" class="form-select select2 disable_select branch_select" required></select>
                                </div>

                                <div class="col-md-3">
                                    <label><b>Class:</b></label>
                                    <select name="class_id" class="form-select select2 disable_select select_class" required></select>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label><b>Section:</b></label>
                                    <select name="section_id" class="form-select select2 disable_select select_section" required></select>
                                </div>

                                <div class="col-md-4">
                                    <label><b>Subject:</b></label>
                                    <select name="subject_id" class="form-select select2 disable_select select_course" required></select>
                                </div>

                                <div class="col-md-4">
                                    <label><b>Component:</b></label>
                                    <select name="component_id" class="form-select select2 disable_select component_id" id="component_id" required></select>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <table id="users-table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Component</th>
                                            <th>Name</th>
                                            <th>Marks</th>
                                            <th>Action</th> <!-- New column for delete button -->
                                        </tr>
                                    </thead>
                                <tbody id="subComponentTableBody">
                                    <!-- Existing row for editing -->
                                    <tr>
                                        <input type="hidden" name="sub_component_id[]" value="{{ $subComponent->id }}">
                                        <td>
                                            <select name="test_type_id[]" class="form-select select2 comp" required>
                                                @foreach($component->componentData ?? [] as $compData)
                                                    <option value="{{ $compData->test_type->id }}"
                                                        data-marks="{{ $compData->total_marks }}"
                                                        {{ $subComponent->test_type_id == $compData->test_type->id ? 'selected' : '' }}>
                                                        {{ $compData->test_type->name . ' | ' . $compData->weightage . '%' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="comp_name[]" class="form-control" value="{{ $subComponent->comp_name }}" required>
                                        </td>
                                        <td>
                                            <input type="number" name="comp_number[]" class="form-control comp-number" value="{{ $subComponent->comp_number }}" required>
                                            <span class="text-danger mark-error-msg" style="display: none;">Entered marks exceed maximum allowed.</span>
                                        </td>
                                        <td class="text-center">
                                            <!-- First row, no remove button -->
                                        </td>
                                    </tr>

                                <!-- 🔧 Hidden template row for cloning -->
                                <tr class="sub-row-template" style="display: none;">
                                    <td>
                                        <select class="form-select select2 comp"> <!-- removed name and required -->
                                            @foreach($component->componentData ?? [] as $compData)
                                                <option value="{{ $compData->test_type->id }}" data-marks="{{ $compData->total_marks }}">
                                                    {{ $compData->test_type->name . ' | ' . $compData->weightage . '%' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" /> <!-- removed name and required -->
                                    </td>
                                    <td>
                                        <input type="number" class="form-control comp-number" />
                                        <span class="text-danger mark-error-msg" style="display: none;">Entered marks exceed maximum allowed.</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row-btn" title="Remove row">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>

                                </tbody>

                                </table>

                                <div class="mt-3">
                                    <button type="button" class="btn btn-success btn-sm" id="addRowBtn">+ Add More</button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">
    <!-- FontAwesome for trash icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
@endsection
@section('js')
<script>
    $(document).ready(function () {
        $('.disable_select').select2({ disabled: 'readonly' });

        const selectedFields = @json($selectedFields);
        const componentId = '{{ $subComponent->component_id }}';

        // Chain of dropdowns
        $('#companySelect').on('change', function () {
            $.get('{{ route("hr.fetch.branches") }}', { companyid: $(this).val() }, function (data) {
                let branchDropdown = $('.branch_select').empty().append('<option value="">Select Branch</option>');
                data.forEach(branch => {
                    let selected = branch.id == selectedFields.branch_id ? 'selected' : '';
                    branchDropdown.append(`<option value="${branch.id}" ${selected}>${branch.name}</option>`);
                });
            });
        }).change();

        $('.branch_select').on('change', function () {
            let branchId = $(this).val() || selectedFields.branch_id;
            $.get('{{ route("academic.fetchClass") }}', { branch_id: branchId }, function (data) {
                let classDropdown = $('.select_class').empty();
                data.forEach(cls => {
                    let selected = cls.id == selectedFields.class_id ? 'selected' : '';
                    classDropdown.append(`<option value="${cls.id}" ${selected}>${cls.name}</option>`);
                });
            });
        }).change();

        $('.select_class').on('change', function () {
            let classId = $(this).val() || selectedFields.class_id;

            $.get('{{ route("academic.fetchSections") }}', { class_id: classId }, function (data) {
                let sectionDropdown = $('.select_section').empty().append('<option value="">Select Section</option>');
                data.forEach(section => {
                    let selected = section.id == selectedFields.section_id ? 'selected' : '';
                    sectionDropdown.append(`<option value="${section.id}" ${selected}>${section.name}</option>`);
                });
            });

            $.get('{{ route("academic.fetchSubjects") }}', { class_id: classId }, function (data) {
                let subjectDropdown = $('.select_course').empty().append('<option value="">Select Subject</option>');
                data.forEach(subject => {
                    let selected = subject.id == selectedFields.subject_id ? 'selected' : '';
                    subjectDropdown.append(`<option value="${subject.id}" ${selected}>${subject.name}</option>`);
                });
            });
        }).change();

        $('.select_course').on('change', function () {
            let subjectId = $(this).val() || selectedFields.subject_id;

            $.get('{{ route("academic.fetchComponentSubject") }}', { course_id: subjectId }, function (data) {
                let compDropdown = $('.component_id').empty().append('<option value="">Select Component</option>');
                if (data) {
                    let selected = data.id == componentId ? 'selected' : '';
                    compDropdown.append(`<option value="${data.id}" ${selected}>${data.name}</option>`);
                }
            });
        }).change();

        // Autofill marks when test type is selected
            $(document).on('change', '.comp', function () {
            let selected = $(this).find('option:selected');
            let marks = selected.data('marks') ?? 0;
            let input = $(this).closest('tr').find('.comp-number');

            // Only fill if input is empty or zero
            if (!input.val() || input.val() == '' || input.val() == '0') {
                input.val(marks).trigger('input');
            }
        });


        // Validate marks
        $(document).on('input', '.comp-number', function () {
            const input = $(this);
            const row = input.closest('tr');
            const max = parseFloat(row.find('.comp option:selected').data('marks')) || 0;
            const entered = parseFloat(input.val()) || 0;

            if (entered > max) {
                row.find('.mark-error-msg').show();
                input.val('');
            } else {
                row.find('.mark-error-msg').hide();
            }
        });

        // Trigger autofill for existing row
        $('.comp').trigger('change');

      $('#addRowBtn').on('click', function () {
    let newRow = $('.sub-row-template').first().clone().removeClass('sub-row-template').show();

    // Clear and add required attributes
    newRow.find('select.comp')
        .attr('name', 'test_type_id[]')
        .attr('required', true)
        .val('');

    newRow.find('input[type="text"]')
        .attr('name', 'comp_name[]')
        .attr('required', true)
        .val('');

    newRow.find('input[type="number"]')
        .attr('name', 'comp_number[]')
        .attr('required', true)
        .val('');

    newRow.find('.mark-error-msg').hide();
    newRow.find('input[name="sub_component_id[]"]').remove();

    // Remove Select2 container if exists
    newRow.find('select').each(function () {
        $(this).next('.select2-container').remove();
    });

    $('#subComponentTableBody').append(newRow);

        // Reinitialize select2
        newRow.find('select').select2();

        // Trigger change to autofill marks
        newRow.find('.comp').trigger('change');
    });


        // ✅ Remove Row
        $(document).on('click', '.remove-row-btn', function () {
            const rowCount = $('#subComponentTableBody tr:visible').length;

            if (rowCount > 1) {
                $(this).closest('tr').remove();
            } else {
                alert('At least one row is required.');
            }
        });
    });
</script>
@endsection
