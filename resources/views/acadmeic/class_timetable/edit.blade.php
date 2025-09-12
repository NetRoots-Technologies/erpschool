@extends('admin.layouts.main')

@section('title')
    ClassTimetable | Edit
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit ClassTimetable</h3>
                        <div class="row mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.class_timetable.index') !!}" class="btn btn-primary btn-md">
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
                        <form action="{!! route('academic.class_timetable.update',$classTime->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">
                                    <div class="row mt-3">

                                        <div class="row mt-3">

                                            <div class="col-md-4">
                                                <label for="branches"><b>Company : *</b></label>
                                                <select name="company_id"
                                                        class="form-select select2 basic-single mt-3" id="companySelect"
                                                        aria-label=".form-select-lg example" frequired>
                                                    <option value="">Select Company</option>
                                                    @foreach($companies as $item)
                                                        <option value="{{$item->id}}" {!! $classTime->company_id == $item->id ? 'selected' : '' !!}>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="Company-name"> <b> Academic Session : * </b></label>
                                                <select  name="session_id"
                                                        class="form-select select2 basic-single mt-3 "
                                                        aria-label=".form-select-lg example" required>
                                                    <option value="">Select Session</option>
                                                    @foreach($sessions as $key => $item)
                                                        <option value="{{$key}}" {!! $classTime->session_id == $key ? 'selected' : ''   !!}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="branches"><b>Branch: *</b></label>
                                                <select  name="branch_id"
                                                        class="form-select select2 basic-single mt-3 branch_select"
                                                        aria-label=".form-select-lg example" required>

                                                </select>
                                            </div>

                                        </div>

                                        <div class="row mt-4">

                                            <div class="col-md-4">
                                                <label for="Company-name"> <b> Timetable Name *</b></label>
                                                <select id="timeTable"  name="time_table_id"
                                                        class="form-select select2 basic-single mt-3 timetable_select"
                                                        aria-label=".form-select-lg example" required>

                                                </select>
                                            </div>


                                            <div class="col-md-4">
                                                <label for="branches"><b>Class: *</b></label>
                                                <select  name="class_id"
                                                        class="form-select select2 basic-single mt-3 select_class"
                                                        aria-label=".form-select-lg example" required>

                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="branches"><b>Section: *</b></label>
                                                <select  name="section_id"
                                                        class="form-select select2 basic-single mt-3 select_section"
                                                        aria-label=".form-select-lg example" required>
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-3">


                                            <div class="col-md-6">
                                                <label for="branches"><b>Subject: *</b></label>
                                                <select  name="course_id"
                                                        class="form-select select2 basic-single mt-3 select_course"
                                                        aria-label=".form-select-lg example" required>
                                                    <option value="">Select Subject</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="days"><b>Days: *</b></label>
                                                <select  name="days"
                                                        class="form-select select2 basic-single mt-3 select_days"
                                                        aria-label=".form-select-lg example" required>
                                                    <option value="">Select Days</option>
                                                    @for($i = 1; $i <= 6; $i++)
                                                        <option
                                                            value="{{ $i }}" {{$classTime->days == $i ? 'selected' : ''}}>{{ \App\Helpers\UserHelper::getDayName($i) }}</option>
                                                    @endfor
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
        var branch_id;
        $(document).ready(function () {

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
                            var selectBranch = branch.id == '{{ $classTime->Timetable->branch_id }}' ? 'selected' : '';

                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectBranch + '>' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


            $('.branch_select').on('change', function () {

                branch_id = $(this).val();
                if(branch_id == null){
                    branch_id = {!! $classTime->branch_id !!}
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_class').empty();

                        data.forEach(function (academic_class) {
                            var selectsection = academic_class.id == '{{ $classTime->class_id }}' ? 'selected' : '';

                            sectionDropdown.append('<option value="' + academic_class.id + '" ' + selectsection + '>' + academic_class.name + '</option>');
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
                if(class_id == null){
                    class_id = {!! $classTime->class_id !!}
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSections') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_section').empty();
                        sectionDropdown.append('<option value="" selected disbaled>Select Section</option>');

                        data.forEach(function (section) {
                            var selectsection = section.id == '{{ $classTime->section_id }}' ? 'selected' : '';

                            sectionDropdown.append('<option value="' + section.id + '" ' + selectsection + '>' + section.name + '</option>');

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
        var branch_id;
        $('.branch_select').on('change', function () {
            branch_id = $(this).val();
            if(branch_id == null){
                branch_id = {!! $classTime->branch_id !!}
            }
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetchTimetable') }}',
                data: {
                    branch_id: branch_id
                },
                success: function (data) {
                    var TimetableDropdown = $('.timetable_select').empty();
                    TimetableDropdown.append('<option selected disabled value="">Select Timetable</option>');

                    data.forEach(function (timetable) {
                        var selectTimetable = timetable.id == '{{ $classTime->time_table_id }}' ? 'selected' : '';

                        TimetableDropdown.append('<option value="' + timetable.id + '" ' + selectTimetable + '>' + timetable.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                }
            });

        }).change();
    </script>


    <script>
        var class_id;
        $(document).ready(function () {
            $('.select_class').on('change', function () {

                class_id  = $(this).val();
                if (class_id == null){
                    class_id = {!! $classTime->class_id !!}
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchCourse') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var courseDropdown = $('.select_course').empty();
                        courseDropdown.append('<option value="" disabled selected>Select course</option>');

                        data.forEach(function (course) {
                            var selectcourse = course.id == '{{ $classTime->course_id }}' ? 'selected' : '';

                            courseDropdown.append('<option value="' + course.id + '" ' + selectcourse + '>' + course.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();

            {{--$('#companySelect').on('change', function () {--}}
            {{--    var selectedCompanyId = $('#companySelect').val();--}}
            {{--    $.ajax({--}}
            {{--        type: 'GET',--}}
            {{--        url: '{{ route('academic.fetch.sessions') }}',--}}
            {{--        data: {--}}
            {{--            companyid: selectedCompanyId--}}
            {{--        },--}}
            {{--        success: function (data) {--}}
            {{--            var sessionDropdown = $('.session_select').empty();--}}

            {{--            sessionDropdown.append('<option value="">Select Session</option>');--}}

            {{--            data.forEach(function (session) {--}}

            {{--                var selectedSession = session.id == '{{ $classTime->Timetable->session_id }}' ? 'selected' : '';--}}

            {{--                sessionDropdown.append('<option value="' + session.id + '" ' + selectedSession + '>' + session.name + '</option>');--}}
            {{--            });--}}
            {{--        },--}}
            {{--        error: function (error) {--}}
            {{--            console.error('Error fetching branches:', error);--}}
            {{--        }--}}
            {{--    });--}}
            {{--}).change();--}}
        })
    </script>



@endsection

