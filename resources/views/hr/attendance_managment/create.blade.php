@extends('admin.layouts.main')

@section('title')
    Attendance Create
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Attendance</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.attendance.index') !!}" class="btn btn-primary btn-md">
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
                        <form action="{!! route('hr.attendance.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:10px;">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label for="branch"><b>Branch Name *</b></label>
                                            <select class="form-select-lg select2  select2-selection --single"
                                                    name = "branch_id" id="selectBranch"  aria-label="Default select example" required>
                                                <option value="" selected disabled>Select Branch</option>
                                                @foreach($branches as $branch)
                                                    <option
                                                        value="{!! $branch->id !!}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="employee_leave_applying"><b>Department</b></label>
                                                <select class="form-select select2" id="selectDepartment"
                                                        aria-label="Default select example" required>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3">
                                            <label for="employee"><b>Employee Name </b></label>
                                            <select class="form-select-lg select2 select2-selection--single" id="selectEmployee"
                                                     aria-label="Default select example" required>
                                            </select>
                                        </div>

                                        <div class="col-lg-3">
                                                <label for="start-date"><b> Date*</b></label>
                                                <div class="input-group">
                                                    <div class="input-group-text ">
                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                    </div>
                                                    <input required name="start_date"
                                                           class="form-control datepicker-date"
                                                           id="start-date" placeholder="MM/DD/YYYY"
                                                           type="text" value="{{ now()->format('Y-m-d') }}">
                                                </div>
                                            </div>
                                        </div>

{{--                                    <div class="row mt-3">--}}
{{--                                        <div class="col-lg-3" style="float: right">--}}
                                            <button type="button" id="Load" class='btn btn-primary pull-right'>Load Employees</button>
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="row mt-5">
                                        <div id="loadData"></div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;margin-left: 10px;">Save</button>
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
        $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

    </script>
    <script>
        $(document).ready(function () {
            $('.basic-multiple').select2();
        });
    </script>

    <script>
        $(document).ready(function () {

            $('#selectBranch').on('change', function () {
                var branch_id = $(this).val();
                $.ajax({
                    type: 'get',
                    url: '{{route('hr.fetchDepartment')}}',
                    data: {
                        branch_id: branch_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        var departmentDropdown = $('#selectDepartment').empty();

                        departmentDropdown.append('<option value="">Select Department</option>');

                        data.forEach(function (department) {
                            departmentDropdown.append('<option value="' + department.id + '">' + department.name + '</option>');
                        });

                    },

                });
            });
            $('#selectDepartment').on('change', function () {
                var department_id = $(this).val();
                var employeesDropdown = $('#selectEmployee').empty();

                $('#selectEmployee').prop('required', false);

                if (department_id) {
                    $.ajax({
                        type: 'get',
                        url: '{{route('hr.fetchEmployee')}}',
                        data: {
                            department_id: department_id
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            if (data.length > 0) {
                                $('#selectEmployee').prop('required', true);
                                employeesDropdown.append('<option value="">Select Employee</option>');
                                data.forEach(function (employee) {
                                    employeesDropdown.append('<option value="' + employee.id + '">' + employee.name + '</option>');
                                });
                            } else {
                                employeesDropdown.append('<option value="">No employees available</option>');
                            }
                        },
                        error: function () {
                            toastr.error('Failed to load employee data.');
                        }
                    });
                } else {
                $('#selectEmployee').prop('required', false);
                employeesDropdown.append('<option value="">Select Department First</option>');
    }
});

});
    </script>

{{--    for load employees on button click--}}
    <script>
        $("#Load").click(function () {
            var date = $('#start-date').val();
            var branch_id = $('#selectBranch').val();
            var department_id = $('#selectDepartment').val();
            var employee_id = $('#selectEmployee').val();
            var loader = $('<div class="loader"></div>').appendTo('body');

            $.ajax({
                url: "{{route('hr.attendance.EmployeesAttendance')}}",
                type: 'POST',
                data: {
                    'branch_id': branch_id,
                    'department_id': department_id,
                    'date': date,
                    'employee_id': employee_id,
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    console.log(data);
                    loader.remove();

                    if(data.success === false)
                    {
                        toastr.error(data.message);
                        return;
                    }

                    $('#loadData').html(data);

                },
                error: function (request, error) {
                    loader.remove();

                    console.log("Request: " + JSON.stringify(request));
                }
            });
        });
    </script>


@endsection

