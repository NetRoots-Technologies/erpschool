@extends('admin.layouts.main')

@section('title')
Student Class Promotion
@stop

@section('css')
    <style>
        .student-checkboxes td {
            vertical-align: top !important;
            text-align: center;
            font-size: 14px;
        }

        .student-checkboxes th {
            background-color: #f8f9fa;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <form method="POST" action="{{ route('academic.student-class-adjustment.store') }}">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card basic-form">
                        <div class="card-body">
                            <h3 class="text-22 text-midnight text-bold mb-4">Student Class Promotion</h3>

                            {{-- FROM and TO columns --}}
                            <div class="row">
                                <!-- From Column -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <h4 class="text-primary mb-3">From</h4>
                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <label>Company Name <b>*</b></label>
                                                <select class="form-select select2 company-select" data-group="from"
                                                    required>
                                                    <option value="">Select Company</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>Branch Name <b>*</b></label>
                                                <select class="form-select select2 branch-select" data-group="from"
                                                    required>
                                                    <option value="">Select Branch</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>Class Name <b>*</b></label>
                                                <select class="form-select select2 class-select" data-group="from" required>
                                                    <option value="">Select Class</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>Section Name <b>*</b></label>
                                                <select class="form-select select2 section-select" data-group="from"
                                                    required>
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- To Column -->
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <h4 class="text-success mb-3">To</h4>
                                        <div class="row">
                                            <div class="col-md-6 mt-3">
                                                <label>Company Name <b>*</b></label>
                                                <select class="form-select select2 company-select" name="to_company_id"
                                                    data-group="to" required>
                                                    <option value="">Select Company</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>Branch Name <b>*</b></label>
                                                <select class="form-select select2 branch-select" name="to_branch_id"
                                                    data-group="to" required>
                                                    <option value="">Select Branch</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>Class Name <b>*</b></label>
                                                <select class="form-select select2 class-select" name="to_class_id"
                                                    data-group="to" required>
                                                    <option value="">Select Class</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <label>Section Name <b>*</b></label>
                                                <select class="form-select select2 section-select" name="to_section_id"
                                                    data-group="to" required>
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- STUDENT LIST in full row --}}
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <h4 class="text-info mb-3">Student List</h4>
                                        <div class="student-checkboxes" data-group="from">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success btn-md">Submit</button>
                                </div>
                            </div>

                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
    </form>

@endsection

@section('css')
    <style>
        .student-checkboxes {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            max-height: 220px;
            overflow-y: auto;
        }
    </style>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.select2').select2({ width: '100%' });

            function resetSelect(selector, group) {
                $(`${selector}[data-group="${group}"]`).html('<option value="">Select</option>');
            }

            function loadBranches(companyId, group) {
                const $branch = $(`.branch-select[data-group="${group}"]`);
                resetSelect('.branch-select', group);
                resetSelect('.class-select', group);
                resetSelect('.section-select', group);
                if (group === 'from') $(`.student-checkboxes[data-group="from"]`).html('');

                if (!companyId) return;

                $.get('{{ route('hr.fetch.branches') }}', { companyid: companyId }, function (branches) {
                    branches.forEach(branch => {
                        $branch.append(`<option value="${branch.id}">${branch.name}</option>`);
                    });
                });
            }

            function loadClasses(branchId, group) {
                const $class = $(`.class-select[data-group="${group}"]`);
                resetSelect('.class-select', group);
                resetSelect('.section-select', group);
                if (group === 'from') $(`.student-checkboxes[data-group="from"]`).html('');

                if (!branchId) return;

                $.get('{{ route('academic.fetchClass') }}', { branch_id: branchId }, function (classes) {
                    classes.forEach(cls => {
                        $class.append(`<option value="${cls.id}">${cls.name}</option>`);
                    });
                });
            }

            function loadSections(classId, group) {
                const $section = $(`.section-select[data-group="${group}"]`);
                resetSelect('.section-select', group);
                if (group === 'from') $(`.student-checkboxes[data-group="from"]`).html('');

                if (!classId) return;

                $.get('{{ route('fetch-section') }}', { class_id: classId }, function (sections) {
                    sections.forEach(section => {
                        $section.append(`<option value="${section.id}">${section.name}</option>`);
                    });
                });
            }

            function loadStudents(group) {
                const branchId = $(`.branch-select[data-group="${group}"]`).val();
                const classId = $(`.class-select[data-group="${group}"]`).val();
                const sectionId = $(`.section-select[data-group="${group}"]`).val();
                const $studentBox = $(`.student-checkboxes[data-group="${group}"]`);

                $studentBox.html('<span class="text-muted">Loading students...</span>');

                if (!branchId || !classId || !sectionId) {
                    $studentBox.html('<span class="text-danger">Please select all required fields</span>');
                    return;
                }

                $.get('{{ route('fetch-students') }}', {
                    branch_id: branchId,
                    class_id: classId,
                    section_id: sectionId
                }, function (students) {
                    if (!students.length) {
                        $studentBox.html('<span class="text-danger">No students found</span>');
                        return;
                    }

                    let html = `
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="heading_style">Select</th>
                                    <th class="heading_style">Student Name</th>
                                    <th class="heading_style">Company</th>
                                    <th class="heading_style">Branch</th>
                                    <th class="heading_style">Class</th>
                                    <th class="heading_style">Section</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    students.forEach(student => {
                        html += `
                            <tr>
                                <td><input class="form-check-input student-checkbox" type="checkbox" name="student_id[]" value="${student.id}" id="student_from_${student.id}"></td>
                                <td><label for="student_from_${student.id}">${student.full_name}</label></td>
                                <td>${student.company_name || '-'}</td>
                                <td>${student.branch_name || '-'}</td>
                                <td>${student.class_name || '-'}</td>
                                <td>${student.section_name || '-'}</td>
                            </tr>`;
                    });

                    html += `</tbody></table>`;
                    $studentBox.html(html);
                });
            }

            // Event bindings
            $('.company-select').on('change', function () {
                loader('show');
                const group = $(this).data('group');
                loadBranches($(this).val(), group);
                loader('hide');
            });

            $('.branch-select').on('change', function () {
                loader('show');
                const group = $(this).data('group');
                loadClasses($(this).val(), group);
                loader('hide');
            });

            $('.class-select').on('change', function () {
                loader('show');
                const group = $(this).data('group');
                loadSections($(this).val(), group);
                loader('hide');
            });

            $('.section-select').on('change', function () {
                loader('show');
                const group = $(this).data('group');
                if (group === 'from') loadStudents(group);
                loader('hide');
            });

            // Form validation
            $('form').on('submit', function (e) {
                const selected = $('.student-checkbox:checked').length;
                if (selected === 0) {
                    e.preventDefault();
                    alert('Please select at least one student before submitting.');
                }
            });
        });
    </script>
@endsection