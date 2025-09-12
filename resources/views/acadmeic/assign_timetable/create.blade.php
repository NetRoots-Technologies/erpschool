@extends('admin.layouts.main')

@section('title')
    Assign Time Table| Create
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Assign Timetable</h3>
                        <div class="row mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.assign_timetable.index') !!}"
                                   class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{!! route('academic.assign_timetable.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">
                                    <div class="row mt-3">

                                        <div class="row mt-3">

                                            <div class="col-md-4">
                                                <label for="branches"><b>Company:</b></label>
                                                <select  name="company_id"
                                                         class="form-select select2 basic-single mt-3" id="companySelect"
                                                         aria-label=".form-select-lg example" required>
                                                    <option value="" disabled>Select Company</option>
                                                    @foreach($companies as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="Company-name"> <b> Academic Session </b></label>
                                                <select  name="session_id"
                                                         class="form-select select2 basic-single mt-3 "
                                                         aria-label=".form-select-lg example" required>
                                                    <option value="" disabled>Select Session</option>
                                                @foreach($sessions as $key => $item)
                                                        <option value="{{$key}}">{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="branches"><b>Branch: </b></label>
                                                <select  name="branch_id"
                                                         class="form-select select2 basic-single mt-3 branch_select"
                                                         aria-label=".form-select-lg example" required>

                                                </select>
                                            </div>

                                        </div>

                                        <div class="row mt-4">

                                            <div class="col-md-4">
                                                <label for="branches"><b>Class:</b></label>
                                                <select required name="class_id"
                                                        class="form-select select2 basic-single mt-3 select_class"
                                                        aria-label=".form-select-lg example">

                                                </select>
                                            </div>


                                            <div class="col-md-4">
                                                <label for="branches"><b>Section:</b></label>
                                                <select required name="section_id"
                                                        class="form-select select2 basic-single mt-3 select_section"
                                                        aria-label=".form-select-lg example">
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="branches"><b>Course:</b></label>
                                                <select required name="course_id"
                                                        class="form-select select2 basic-single mt-3 select_course"
                                                        aria-label=".form-select-lg example">
                                                    <option value="">Select Course</option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="row mt-3">

                                            <div class="col-md-6">
                                                <label for="Company-name"> <b> Timetable *</b></label>
                                                <select id="timeTable" required name="time_table_id"
                                                        class="form-select select2 basic-single mt-3 timetable_select"
                                                        aria-label=".form-select-lg example" required>

                                                </select>
                                            </div>


                                            <div class="col-md-6">
                                                <label for="branches"><b>Teacher:</b></label>
                                                <select  name="teacher_id"
                                                         class="form-select select2 basic-single mt-3" id="companySelect"
                                                         aria-label=".form-select-lg example" required>
                                                    <option value="" selected disabled>Select Teachers</option>
                                                    @foreach($teachers as $key => $item)
                                                        <option value="{{$key}}">{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>


                                        <div style="margin-top: 20px">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
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

@endsection
@section('js')

    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>



    <script>
        $(document).ready(function () {


            $("#form_validation").validate({
                errorElement: "span",
                errorPlacement: function (error, element) {
                        error.appendTo(element.parent());
                }
            });


            $('#companySelect').on('change', function () {
                var selectedCompanyId = $('#companySelect').val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {
                        var branchesDropdown = $('.branch_select').empty();

                        branchesDropdown.append('<option value="">Select Branch</option>');

                        data.forEach(function (branch) {
                            branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


            $('.branch_select').on('change', function () {

                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_class').empty();

                        sectionDropdown.append('<option value="">Select Class</option>');

                        data.forEach(function (academic_class) {
                            sectionDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            });

        })
    </script>


    <script>
        $(document).ready(function () {
            $('.select_class').on('change', function () {

                var class_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSections') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_section').empty();

                        sectionDropdown.append('<option value="">Select Section</option>');

                        data.forEach(function (section) {
                            sectionDropdown.append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            });
        })
    </script>

    <script>
        $('.select_course').on('change', function () {
            var course_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetchCourse.Timetable') }}',
                data: {
                    course_id: course_id
                },
                success: function (data) {
                    var TimetableDropdown = $('.timetable_select').empty();
                    TimetableDropdown.append('<option value="">Select Timetable</option>');
                        data.forEach(function (timetable) {
                        TimetableDropdown.append('<option value="' + timetable.id + '">' + timetable.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                }
            });

        });
    </script>


    <script>
        $(document).ready(function () {
            $('.select_class').on('change', function () {

                var class_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchCourse') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var courseDropdown = $('.select_course').empty();
                        courseDropdown.append('<option value="">Select Course</option>');

                        data.forEach(function (course) {
                            courseDropdown.append('<option value="' + course.id + '">' + course.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();

        })
    </script>



@endsection

