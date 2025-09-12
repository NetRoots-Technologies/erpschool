@extends('admin.layouts.main')

@section('title')
    Timetable | Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Timetable</h3>
                        <div class="row mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.timetables.index') !!}" class="btn btn-primary btn-md">
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
                        <form action="{!! route('academic.timetables.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">


                                    <div class="row mt-3">

                                        <div class="col-md-4">
                                            <label for="Company-name"> <b> Company Name *</b></label>
                                            <select id="companySelect" required name="company_id"
                                                    class="form-select select2 basic-single mt-3"
                                                    aria-label=".form-select-lg example">
                                                @foreach($companies as $company)
                                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"> <b>Branch Name *</b></label>
                                            <select id="branchSelect" required name="branch_id"
                                                    class="form-select select2 basic-single mt-3 branch_select"
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select Branch</option>
                                            </select>
                                        </div>


                                        <div class="col-md-4">
                                            <label for="branches"><b>Academic Session *</b></label>
                                            <select required name="session_id"
                                                    class="form-select select2 basic-single mt-3 session_select"
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select Session</option>
                                                @foreach($sessions as $key => $item)
                                                    <option value="{{$key}}">{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row mt-3">

                                        <div class="col-md-6">
                                            <label for="branches"> <b>School Type *</b></label>
                                            <select required name="school_id"
                                                    class="form-select select2 basic-single mt-3 school_types"
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select School</option>

                                            </select>
                                        </div>


                                        <div class="col-md-6">
                                            <label for="name"><b>Name *</b></label>
                                            <input type="text" name="name" placeholder="name" class="form-control"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">

                                        <div class="col-lg-6">
                                            <label for="start_time"><b>Start Time *</b></label>
                                            <div class="input-group">
                                                <input type="time" name="start_time" id="start_time"
                                                       class="form-control " required>
                                            </div>
                                        </div>


                                        <div class="col-lg-6">
                                            <label for="start_time"><b>End Time *</b></label>
                                            <input type="time" name="end_time" id="end_time"
                                                   class="form-control " required>
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
                        var branchesDropdown = $('#branchSelect').empty();
                        // var otherBranchesDropdown = $('#otherBranchSelect').empty();

                        branchesDropdown.append('<option value="">Select Branch</option>');

                        data.forEach(function (branch) {
                            branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                            // otherBranchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
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
        });
    </script>

    <script>
        $('.branch_select').on('change', function () {
            var branch_id = $('.branch_select').val();

            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetch.schools') }}',
                data: {
                    branch_id: branch_id
                },
                success: function (data) {
                    var schoolTypeDropdown = $('.school_types').empty();
                    schoolTypeDropdown.append('<option value="">Select School</option>');

                    data.forEach(function (schoolType) {
                        schoolTypeDropdown.append('<option value="' + schoolType.id + '">' + schoolType.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching schoolType:', error);
                }
            });
        });
    </script>






@endsection

