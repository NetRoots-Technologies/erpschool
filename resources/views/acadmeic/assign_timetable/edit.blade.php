@extends('admin.layouts.main')

@section('title')
    Assign Time Table| Edit
@stop

@section('content')
    {{--    @dd($assignTable)--}}
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4">Edit Assign Timetable</h3>
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
                        <form action="{!! route('academic.assign_timetable.update',$assignTable->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">
                                    <div class="row mt-3">

                                        <div class="row mt-3">

                                            <div class="col-md-4">
                                                <label for="branches"><b>Company:</b></label>
                                                <select name="company_id"
                                                        class="form-select select2 basic-single mt-3 select_company"
                                                        aria-label=".form-select-lg example" disabled>
                                                    <option value="">Select Company</option>
                                                    @foreach($companies as $item)
                                                        <option
                                                            value="{{$item->id}}" {!! $assignTable->classTimeTable->company_id == $item->id ? 'selected' : ''  !!}>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="Company-name"> <b> Academic Session </b></label>
                                                <select name="session_id"
                                                        class="form-select select2 basic-single mt-3 session_select"
                                                        aria-label=".form-select-lg example" disabled>
                                                    <option value="" disabled>Select Session</option>
                                                    @foreach($sessions as $key => $item)
                                                        <option value="{{ $key }}"
                                                            {{ $assignTable->classTimeTable->session_id == $key ? 'selected' : '' }}>
                                                            {{ $item }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            <div class="col-md-4">
                                                <label for="branches"><b>Branch: </b></label>
                                                <select name="branch_id"
                                                        class="form-select select2 basic-single mt-3 branch_select"
                                                        aria-label=".form-select-lg example" disabled>

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
                                                        aria-label=".form-select-lg example">

                                                </select>
                                            </div>


                                            <div class="col-md-6">
                                                <label for="branches"><b>Teacher:</b></label>
                                                <select name="teacher_id"
                                                        class="form-select select2 basic-single mt-3"
                                                        aria-label=".form-select-lg example">
                                                    <option value="">Select Teachers</option>
                                                    @foreach($teachers as $key => $item)
                                                        <option
                                                            value="{{$key}}" {!! $assignTable->teacher_id == $key ? 'selected' : '' !!}>{{$item}}</option>
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
            $('.select_company').on('change', function () {
                var selectedCompanyId = $('.select_company').val();

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
                            console.log("Branch",branch);
                            console.log("Fetch Branch ID",'{{ $assignTable->classTimeTable->branch_id }}');
                            var selectedbranches = branch.id == '{{ $assignTable->classTimeTable->branch_id  }}' ? 'selected' : '';
                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectedbranches + '>' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            });
        });
    </script>

    <script>
        var branch_id;

        $(document).ready(function () {
            $('.branch_select').on('change', function () {
                branch_id = $(this).val();
                 console.log("Branch ID Before ",branch_id);
                if (branch_id == null) {
                    branch_id = {!! $assignTable->classTimeTable->branch_id !!}
                }
                console.log("Branch ID",branch_id);
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var classDropdown = $('.select_class').empty();
                        data.forEach(function (academic_class) {

                            var selectedclass = academic_class.id == '{{ $assignTable->class_id }}' ? 'selected' : '';
                            classDropdown.append('<option value="' + academic_class.id + '" ' + selectedclass + '>' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        })
    </script>


    <script>
        var class_id;
        $(document).ready(function () {
            $('.select_class').on('change', function () {
                class_id = $(this).val();
                if (class_id == null) {
                    class_id =  {!! $assignTable->class_id !!}
                }

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSections') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_section').empty();

                        sectionDropdown.append('<option value="">Select section</option>');

                        data.forEach(function (section) {
                            var selectedsection = section.id == '{{ $assignTable->section_id }}' ? 'selected' : '';
                            sectionDropdown.append('<option value="' + section.id + '" ' + selectedsection + '>' + section.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        })
    </script>

    <script>
        var course_id;
        $(document).ready(function () {
            $('.select_course').on('change', function () {
                course_id = $(this).val();

                // If no course is selected, use the default one from backend
                if (course_id == null || course_id === '') {
                    course_id = {!! $assignTable->course_id !!};
                }

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchCourse.Timetable') }}',
                    data: {
                        course_id: course_id
                    },
                    success: function (data) {
                        var TimetableDropdown = $('.timetable_select').empty();
                        TimetableDropdown.append('<option value="">Select timetable</option>');

                        data.forEach(function (timetable) {
                            var selected = timetable.id == '{{ $assignTable->timetable_id }}' ? 'selected' : '';
                            TimetableDropdown.append('<option value="' + timetable.id + '" ' + selected + '>' + timetable.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching timetables:', error);
                    }
                });
            }).change();
        });
    </script>



    <script>
        var class_id;
        $(document).ready(function () {
            $('.select_class').on('change', function () {

                class_id = $(this).val();
                if (class_id == null) {
                    class_id =  {!! $assignTable->class_id !!}
                }
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
                            var selectedTimetable = course.id == '{{ $assignTable->course_id }}' ? 'selected' : '';
                            courseDropdown.append('<option value="' + course.id + '" ' + selectedTimetable + '>' + course.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.select_company').on('change', function () {
                var selectedCompanyId = $('.select_company').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetch.sessions') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {
                        //var sessionDropdown = $('.session_select').empty();

                        sessionDropdown.append('<option value="">Select Session</option>');

                        data.forEach(function (session) {
                            var selectedsession = session.id == '{{ $assignTable->classTimeTable->session_id }}' ? 'selected' : '';
                            sessionDropdown.append('<option value="' + session.id + '" ' + selectedsession + '>' + session.name + '</option>');
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

