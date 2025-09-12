@extends('admin.layouts.main')
@section('title')
    Effort Levels
@endsection
@section('content')
    <style>
        #modal_name {
            margin-right: 500px;
        }
        .form-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: 600;
            color: #333;
        }
        .form-select {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 10px;
            transition: border-color 0.3s ease;
        }
        .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }
        .danger {
            color: #dc3545;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .effort-achievement-group {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .effort-achievement-group label {
            font-size: 1.1rem;
            color: #2c3e50;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100 mt-4">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Effort Levels</h3>
        </div>
        
        <div class="row mt-4 mb-4">
            @if (Gate::allows('students'))
                <div class="form-card">
                    <form action="{{ route('exam.effort_levels.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label><b>Company:</b><span class="danger">*</span></label>
                                <select name="company_id" id="companySelect" class="form-select select2" required>
                                    <option disabled selected>Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label><b>Branch:</b><span class="danger">*</span></label>
                                <select name="branch_id" class="form-select select2 branch_select" required>
                                    <option disabled selected>Select Branch</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label><b>Class:</b><span class="danger">*</span></label>
                                <select name="class_id" class="form-select select2 select_class" required>
                                    <option disabled selected>Select Class</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label><b>Section:</b><span class="danger">*</span></label>
                                <select name="section_id" class="form-select select2 select_section" required>
                                    <option disabled selected>Select Section</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label><b>Student:</b><span class="danger">*</span></label>
                                <select name="student_id" class="form-select select2 select_student" required>
                                    <option disabled selected>Select Student</option>
                                </select>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label><b>Subject:</b><span class="danger">*</span></label>
                                <select name="subject_id" class="form-select select2 select_subject" required>
                                    <option disabled selected>Select Subject</option>
                                </select>
                            </div>
                            <div class="col-md-12 effort-achievement-group">
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <label><b>Effort Level:</b><span class="danger">*</span></label>
                                        <select name="effort_level" class="form-select select2" required>
                                            <option disabled selected>Select Effort Level</option>
                                            <option value="Very Good">Very Good</option>
                                            <option value="Good">Good</option>
                                            <option value="Satisfactory">Satisfactory</option>
                                            <option value="Needs Improvement">Needs Improvement</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label><b>Level of Achievement:</b><span class="danger">*</span></label>
                                        <select name="achievement_level" class="form-select select2" required>
                                            <option disabled selected>Select Achievement Level</option>
                                            <option value="3">3 - Fully Meets Expectations</option>
                                            <option value="2">2 - Meets Expectations</option>
                                            <option value="1">1 - Minimally Meets Expectations</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="js_all_subjects_with_skill_group_and_skill_evaluation" class="mt-4"></div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        var tableData = null;
    </script>
    <script>
        $(document).ready(function () {
            $('.dropify').dropify();

            $('#companySelect').change(function () {
                loader('show');
                $.get('{{ route("hr.fetch.branches") }}', { companyid: this.value })
                    .done(data => {
                        let opts = '<option disabled selected>Select Branch</option>';
                        data.forEach(b => opts += `<option value="${b.id}">${b.name}</option>`);
                        $('.branch_select').html(opts);
                    });
                loader('hide');
            }).trigger('change');

            $('.branch_select').change(function () {
                loader('show');
                $.get('{{ route("academic.fetchClass") }}', { branch_id: this.value })
                    .done(data => {
                        let opts = '<option disabled selected>Select Class</option>';
                        data.forEach(c => opts += `<option value="${c.id}">${c.name}</option>`);
                        $('.select_class').html(opts);
                    });
                loader('hide');
            }).trigger('change');

            $('.select_class').change(function () {
                loader('show');
                const classId = this.value;

                $.get('{{ route("academic.fetchSections") }}', { class_id: classId })
                    .done(sec => {
                        
                        let o = '<option disabled selected>Select Section</option>';
                        sec.forEach(s => o += `<option value="${s.id}">${s.name}</option>`);
                        $('.select_section').html(o);
                    });
                loader('hide');
            }).trigger('change');

            $('.select_section').change(function () {
                loader('show');
                let branch = $('.branch_select').val(),
                    cls = $('.select_class').val(),
                    sect = this.value;
                $.get('{{ route("fetch-students") }}', {
                    branch_id: branch,
                    class_id: cls,
                    section_id: sect
                }).done(comps => {
                    let o = '<option disabled selected>Select Student</option>';
                    comps.forEach(c => o += `<option value="${c.id}">${c.full_name}</option>`);
                    $('.select_student').html(o);
                });
                loader('hide');
            }).trigger('change');

            $('.select_student').change(function () {
                loader('show');
                let branch = $('.branch_select').val(),
                    cls = $('.select_class').val(),
                    sect = $('.select_section').val(),
                    student_id = this.value;

                $.get('{{ route("exam.fetchSubjects") }}', {
                    branch_id: branch,
                    class_id: cls,
                    section_id: sect,
                    student_id: student_id
                }).done(subjects => {
                    let opts = '<option disabled selected>Select Subject</option>';
                    subjects.forEach(subject => opts += `<option value="${subject.id}">${subject.name}</option>`);
                    $('.select_subject').html(opts);
                });
              
                loader('hide');
            }).trigger('change');
        });
    </script>
@endsection