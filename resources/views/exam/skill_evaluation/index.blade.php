@extends('admin.layouts.main')
@section('title')
    Skill Evaluation
@endsection
@section('content')
    <style>
        #modal_name {
            margin-right: 500px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Skill Evaluation</h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            @if (Gate::allows('students'))
                <form action="{{ route('exam.skill_evaluation.store') }}" method="post">
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
                    </div>
                    <div id="js_all_subjects_with_skill_group_and_skill_evaluation" class="mt-4"></div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            @endif
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <!-- The Modal for Edit -->
                    <div class="modal modal1" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Evaluation Key</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>
                                <!-- Modal body  -->
                                <div class="modal-body">
                                    <form id="editform" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label for="name" id="modal_name">Abbr</label>
                                                    </div>
                                                    <input type="text" class="form-control" id="name_edit" value="" name="abbr">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label for="name" id="modal_name">Key</label>
                                                    </div>
                                                    <input type="text" class="form-control" id="key_edit" value="" name="key">
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" id="edit_id" class="form-control">
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm" value="Update">
                                            <button type="button" class="btn btn-danger btn btn-sm modalclose" data-dismiss="modal1">Close
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

    <script type="text/javascript">
        var tableData = null;

        //Create Form Submit
        // $('#create-form-submit').on('click', function (e) {
        //     e.preventDefault();
        //     var formData = new FormData($('#createform')[0]);
        //     formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        //     var url = "{{ route('exam.skill_evaluations_key.store') }}";
        //     var loader = $('<div class="loader"></div>').appendTo('body');

        //     $.ajax({
        //         type: "POST",
        //         url: url,
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function (response) {
        //             loader.remove();

        //             $('#createform')[0].reset();
        //             $('#close').trigger('click');
        //             tableData.ajax.reload();
        //             toastr.success('Skill Evaluation added successfully.')
        //         },
        //         error: function () {
        //             loader.remove();

        //             toastr.error('Please fill all the required fields');
        //         }
        //     });
        //     return false;
        // });


        // $(".modalclose").click(function () {
        //     $('#myModal').modal('hide');
        // });

        // $('#tag-form-submit').on('click', function (e) {
        //     e.preventDefault();
        //     var id = $('#edit_id').val();
        //     var url = "{{ route('exam.skill_evaluations_key.index') }}";
        //     var loader = $('<div class="loader"></div>').appendTo('body');

        //     if (!$("#editform").valid()) {
        //         return false;
        //     }

        //     var formData = new FormData($('#editform')[0]);
        //     formData.append('_method', 'PUT');
        //     formData.append('_token', $('input[name="_token"]').val());

        //     $.ajax({
        //         type: "POST",
        //         url: url + '/' + id,
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function (response) {
        //             loader.remove();

        //             $('#myModal').modal('hide');

        //             $('#name_edit').val('');

        //             tableData.ajax.reload();
        //             toastr.success('skill Evaluation Updated successfully.');
        //         },
        //         error: function () {
        //             loader.remove();

        //             toastr.error('Error while updating Skill Evaluation',);
        //         }
        //     });
        //     return false;
        // });
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

                    
                // $.get('{{ route("academic.fetchSubject") }}', { class_id: classId })
                //     .done(sub => {
                //         let o = '<option disabled selected>Select Subject</option>';
                //         sub.forEach(s => o += `<option value="${s.id}">${s.name}</option>`);
                //         $('.select_course').html(o);
                //     });
                loader('hide');
            }).trigger('change');

            // $('.select_course').change(function () {
            //     loader('show');
            //     $.get('{{ route("academic.fetchComponentSubject") }}', { course_id: this.value })
            //         .done(comps => {
            //             let o = '<option disabled selected>Select Component</option>';
            //             comps.forEach(c => o += `<option value="${c.id}">${c.name}</option>`);
            //             $('.component_id').html(o);
            //         });
            //     loader('hide');
            // }).trigger('change');

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

                $.get('{{ route("exam.studentSubjectsWithEvaluation") }}', {
                    branch_id: branch,
                    class_id: cls,
                    section_id: sect
                }).done(response => {
                    console.log(response);
                    if (!response.skill_type.length) {
                        $('#js_all_subjects_with_skill_group_and_skill_evaluation').html('<p class="text-danger">No response found.</p>');
                        return;
                    }
                    let html = `<table class="table table-bordered">`;
                    html += `<thead><tr>`;
                    html += `<th>#</th>`;
                    html += `<th>Subject</th>`;
                    html += `<th>Skill Group</th>`;
                    html += `<th>Skill</th>`;
                    html += `<th>Skill Grade</th>`;
                    html += `</tr></thead><tbody>`;
                    response.skill_type.forEach((s, i) => {
                        html += `<tr data-student-id="${s.id}">`;
                        html += `<td>${i + 1}</td>`;
                        html += `<td>${s.subject.name}</td>`;
                        html += `<td>${s.group.skill_group}</td>`;
                        html += `<td>${s.skill.name}</td>`;
                        html += `<td>`;
                        html += `   <input type="hidden" name="subject_id[]" value="${s.subject.id}">`; // Add subject_id
                        html += `   <input type="hidden" name="skill_group_id[]" value="${s.group.id}">`; // Add skill_group_id
                        html += `   <input type="hidden" name="skill_id[]" value="${s.skill.id}">`;
                        html += `   <select class="form-control allocated-marks" name="skill_evaluation_key_id[]">`;
                        response.skill_evaluation_key.forEach((skl_evl_key, j) => {
                            console.log(skl_evl_key);
                            html += `<option value="${skl_evl_key.id}}">${skl_evl_key.abbr}</option>`;
                        });
                        html += `   </select>`;
                        html += `</td>`;
                        html += `</tr>`;
                    });
                    html += `</tbody></table>`;
                    $('#js_all_subjects_with_skill_group_and_skill_evaluation').html(html);
                });
                loader('hide');
            }).trigger('change');

        });
    </script>

@endsection
