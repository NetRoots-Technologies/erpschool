@extends('admin.layouts.main')

@section('title')
Skill Type | create
@stop

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create skill Type</h3>
                    <div class="row mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{!! route('exam.skill_types.index') !!}" class="btn btn-primary btn-md">
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
                    <form action="{!! route('exam.skill_types.store') !!}" enctype="multipart/form-data" id="form_validation" autocomplete="off" method="post">
                        @csrf
                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:30px;">
                                <div class="row mt-3">
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label for="branches"><b>Company:</b></label>
                                            <select name="company_id" class="form-select select2 basic-single mt-3" id="companySelect" aria-label=".form-select-lg example" required>
                                                {{-- <option value="">Select Company</option>--}}
                                                @foreach($companies as $item)
                                                    <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Company-name"> <b> Academic Session </b></label>
                                            <select name="session_id" class="form-select select2 basic-single mt-3 session_select" aria-label=".form-select-lg example" required>
                                                <option value="" selected disabled>Select Session</option>
                                                @foreach($sessions as $key => $item)
                                                    <option value="{{$key}}">{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="branches"><b>Branch: </b></label>
                                            <select name="branch_id" class="form-select select2 basic-single mt-3 branch_select" aria-label=".form-select-lg example" required>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <label for="branches"><b>Class: *</b></label>
                                            <select required name="class_id" class="form-select select2 basic-single mt-3 select_class" aria-label=".form-select-lg example">
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="branches"><b>Subject: *</b></label>
                                            <select name="course_id" class="form-select select2 basic-single mt-3 select_course" aria-label=".form-select-lg example" required>
                                                <option value="" selected disabled>Select Subject</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <label for="branches"><b>Group: *</b></label>
                                            <select name="group_id" class="form-select select2 basic-single mt-3" aria-label=".form-select-lg example" required>
                                                <option value="" selected disabled>Select Group</option>
                                                @foreach($groups as $item)
                                                    <option value="{{$item->id}}">{{$item->skill_group}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Skill: *</label>
                                            <select name="skill_id" class="form-select select2 basic-single mt-3" aria-label=".form-select-lg example" required>
                                                <option value="" selected disabled>Select Group</option>
                                                @foreach($skills as $skill)
                                                    <option value="{{$skill->id}}">{{$skill->name}}</option>
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
                        sectionDropdown.append('<option value="" selected disabled>Select Class</option>');
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
                    url: '{{ route('academic.fetchSubject') }}',
                    data: {
                        id: class_id
                    },
                    success: function (data) {
                        var courseDropdown = $('.select_course').empty();
                        courseDropdown.append('<option value="" selected disabled>Select Subject</option>');

                        data.forEach(function (subject) {
                            courseDropdown.append('<option value="' + subject.id + '">' + subject.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching subjects:', error);
                    }
                });
            });
        });
    </script>
    <script>
        $('.branch_select').on('change', function () {
            var branch_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetchTimetable') }}',
                data: {
                    branch_id: branch_id
                },
                success: function (data) {
                    console.log(data);
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
            // $('.select_class').on('change', function () {
            //     var class_id = $(this).val();
            //     $.ajax({
            //         type: 'GET',
            //         url: '{{ route('academic.fetchCourse') }}',
            //         data: {
            //             class_id: class_id
            //         },
            //         success: function (data) {
            //             var courseDropdown = $('.select_course').empty();
            //             courseDropdown.append('<option value="" selected disabled>Select course</option>');
            //             data.forEach(function (course) {
            //                 courseDropdown.append('<option value="' + course.id + '">' + course.name + '</option>');
            //             });
            //         },
            //         error: function (error) {
            //             console.error('Error fetching branches:', error);
            //         }
            //     });
            // }).change();

            // $('.select_class').on('change', function () {
            //     var selectedCompanyId = $('.select_class').val();
            //     $.ajax({
            //         type: 'GET',
            //         url: '{{ route("academic.fetch.active_sessions") }}',
            //         data: {
            //             class_id: selectedCompanyId
            //         },
            //         success: function (data) {
            //             var sessionDropdown = $('.session_select').empty();
            //             alert('hello');
            //             sessionDropdown.append('<option value="">Select Session</option>');
            //             data.forEach(function (session) {
            //                 sessionDropdown.append('<option value="' + session.id + '">' + session.name + '</option>');
            //             });
            //         },
            //         error: function (error) {
            //             console.error('Error fetching branches:', error);
            //         }
            //     });
            // });
        })
    </script>



@endsection