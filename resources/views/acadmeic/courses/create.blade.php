@extends('admin.layouts.main')

@section('title')
    Subject | Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Subjects</h3>
                        <div class="row mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.subjects.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('academic.subjects.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label for="branches"><b>Company: *</b></label>
                                            <select required name="company_id"
                                                    class="form-select select2 basic-single mt-3" id="companySelect"
                                                    aria-label=".form-select-lg example">
                                                @foreach($companies as $item)
                                                    <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Academic Session *</b></label>
                                            <select required name="session_id"
                                                    class="form-select select2 basic-single mt-3 session_select"
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select Session</option>
                                                @foreach($formattedSessions as $key => $item)
                                                    <option value="{{$key}}" >{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Branch: *</b></label>
                                            <select required name="branch_id"
                                                    class="form-select select2 basic-single mt-3 branch_select"
                                                    aria-label=".form-select-lg example">

                                            </select>
                                        </div>

                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label for="branches"><b>Class: *</b></label>
                                            <select required name="class_id"
                                                    class="form-select select2 basic-single mt-3 class_select"
                                                    aria-label=".form-select-lg example">

                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="branches"><b>Active Section: *</b></label>
                                            <select required name="active_session_id"
                                                    class="form-select select2 basic-single mt-3 select_active_session"
                                                    aria-label=".form-select-lg example">

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">

                                        <div class="col-md-4">
                                            <label for="branches"><b>Subject Type: *</b></label>
                                            <select required name="course_type_id"
                                                    class="form-select select2 basic-single mt-3 school_select"
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select Subject</option>
                                                @foreach($course_types as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-4">
                                            <label for="name"><b>Subject Name *</b></label>
                                            <input type="text" name="name" placeholder="name" class="form-control"
                                                   required>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="name"><b>Subject Code</b></label>
                                            <input type="text" name="subject_code" class="form-control"
                                                   >
                                        </div>
                                    </div>
                                    <div style="margin-top: 20px">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
            {{--                sessionDropdown.append('<option value="' + session.id + '">' + session.name + '</option>');--}}
            {{--            });--}}
            {{--        },--}}
            {{--        error: function (error) {--}}
            {{--            console.error('Error fetching branches:', error);--}}
            {{--        }--}}
            {{--    });--}}
            {{--}).change();--}}

            $('.branch_select').on('change', function () {

                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var classDropdown = $('.class_select').empty();
                        classDropdown.append('<option value="">Select class</option>');

                        data.forEach(function (academic_class) {
                            classDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                    toastr.error('Error fetching branches:', error)
                    }
                });

            });
        })
    </script>

    <script>
        $(document).ready(function () {
            $('.class_select').on('change', function () {
                var Class_id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetch.active_sessions') }}',
                    data: {
                        class_id: Class_id
                    },

                    success: function (data) {
                        console.log(data)
                        let ActiveSessionDropdown = $('.select_active_session').empty();
                        ActiveSessionDropdown.append('<option value="">Select Active Session</option>');

                        var startDate = new Date(data.start_date);
                        var endDate = new Date(data.end_date);

                        var startYear = startDate.getFullYear().toString().slice(-2);

                        var endYear = endDate.getFullYear().toString().slice(-2);

                        var sessionNameWithDate = data.name + ' ' + startYear + '-' + endYear;

                        ActiveSessionDropdown.append('<option value="' + data.id + '">' + sessionNameWithDate + '</option>');

                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            });
        })
    </script>


@endsection

