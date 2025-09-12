@extends('admin.layouts.main')

@section('title')
    Attendance | Dashboard
@stop
@section('css')

@endsection

@section('content')

    <style>
        .red-tooltip + .tooltip > .tooltip-inner {
            background-color: #f00;
        }
    </style>


    <div class="card">
        <div class="card-body">

            <form action="{{route('hr.attendanceDashboard.dashboard')}}" method="get">
                <div class="row mb-3">
                    <div class="form-group col-md-3" id="from_date_div">
                        <label for="selectMonth"><b>Select Month/Year</b></label>

                        <input type="month" id="month_year"
                               @if(isset($data['month_year'])) value="{!! $data['month_year'] !!}"
                               @endif  name="month_year" class="form-control" required>

                    </div>

                    <div class="col-lg-3">
                        <label for="branch"><b>Branch Name *</b></label>
                        <select class="form-select-lg select2 select2-selection--single"
                                name="branch_id" id="selectBranch" aria-label="Default select example" required>
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                        @if(isset($data['branch_id']) && $data['branch_id'] == $branch->id)
                                        selected
                                    @endif>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="employee_leave_applying"><b>Department</b></label>
                            <select class="form-select select2" name="department_id" id="selectDepartment"
                                    aria-label="Default select example" required>


                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <label for="employee"><b>Employee Name </b></label>
                        <select class="form-select-lg select2 select2-selection--single" name="employee_id"
                                id="selectEmployee"
                                aria-label="Default select example" required>
                        </select>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col" style="margin-top: 10px">
                        <button id="filter_btn" class="btn btn-primary">Apply Filter</button>
                    </div>
                </div>

{{--                <div class="btn-group mb-3">--}}
{{--                    <a href="{{ route('hr.export.pdf1', ['branch_id' => $data['branch_id'] ?? '', 'department_id' => $data['department_id'] ?? '', 'employee_id' => $data["employee_id"] ?? '', 'month_year' => $data['month_year'] ?? '']) }}" class="btn btn-primary" target="_blank">--}}
{{--                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export to PDF--}}
{{--                    </a>--}}

{{--                </div>--}}


            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="sticky float-right" style="margin-left: 90px !important;">
                    <span><i class="fa fa-check" aria-hidden="true" style="color: green"
                             id="present"></i> Present</span>&nbsp;&nbsp;
                <span><i class="fa fa-times" aria-hidden="true" style="color: red" id="absent"></i> Absent</span>&nbsp;&nbsp;
                <span><i class="fa fa-exclamation-triangle" style="color:  darkcyan" aria-hidden="true" id="dayoff"></i> Day Off</span>&nbsp;&nbsp;
                <span><i class="fa fa-envelope-open-o" style="color: yellowgreen" aria-hidden="true"
                         id="onleave"></i> On Leave</span>&nbsp;&nbsp;
                <span> <i class="fa fa-clock-o" aria-hidden="true" id="late"></i> Late</span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row" style="margin-left: 10px">
            <div class="card basic" style="margin-top: 30px">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Employees</th>
                            @foreach($dates_array as $date)
                                <th>
                                    <div class="row" style="display: inline-flex">
                                        <div style="display: inline-flex">
                                            {{date('d M', strtotime($date))}}
                                        </div>
                                        <div>
                                            {!! date('D', strtotime($date)) !!}
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees_data as $employee_data)
                            <tr>
                                <td>
                                    <a href="{{ route('hr.attendance.detail', ['employeeId' => $employee_data['id'], 'start_date' => $start_date, 'end_date' => $end_date]) }}"
                                       style="text-decoration: none">{!! $employee_data['name']  !!}</a>
                                </td>
{{--                                . $employee_data['id']--}}
                                @foreach($employee_data['attendance'] as $key => $attendance)
                                    @php
                                        $attendanceData = $employee_data['attendance'][$key] ?? null;
                                    @endphp

                                    <td>
                                        @if($attendanceData)
                                            @if($attendanceData['offDay'] == true)
                                                <i class="fa fa-exclamation-triangle" style="color: darkcyan" data-toggle="tooltip" data-placement="top"
                                                   aria-hidden="true" id="dayoff" title="Holiday Name = {{ isset($attendanceData['holiday_name']) ? $attendanceData['holiday_name'] : 'OffDay' }}"></i>

                                            @elseif($attendanceData['late'] == true)
                                                <a href="{{ route('hr.attendance.detail', ['employeeId' => $employee_data['id']]) }}">
                                                    <i class="fa fa-clock-o" aria-hidden="true" id="late"
                                                       title="Total Late time = {!! $attendanceData['lateTime'] !!}"></i>
                                                </a>

                                            @elseif($attendanceData['present'] == true)

                                                <i class="fa fa-check red-tooltip detail-icon" aria-hidden="true"
                                                   style="color: green" data-toggle="tooltip" data-placement="top"
                                                   title="Total Working hours = {!! $attendanceData['total_hours_worked'] !!}, CheckinTime = {!! $attendanceData['checkin_time'] !!} checkOutTime = {!! $attendanceData['checkout_time'] !!}"></i>

                                            @elseif($attendanceData['leave'] == true)
                                                <i class="fa fa-envelope-open-o" style="color: yellowgreen"
                                                   aria-hidden="true" id="on_leave" ></i>
                                            @elseif($attendanceData['absent'] == true)
                                                <i class="fa fa-times" aria-hidden="true" style="color: red"
                                                   id="absent"></i>
                                            @else

                                            @endif
                                        @else
                                            No data
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>





@endsection
@section('js')

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <script>

        @if(isset($data['branch_id']))
        $(document).ready(function () {

            branchChange();
        });
        @endif
        $('#selectBranch').on('change', function () {
            branchChange();
        });

        function branchChange() {
            var branch_id = $('#selectBranch').val();

            $.ajax({
                type: 'get',
                url: '{{ route('hr.fetchDepartment') }}',
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
                        var option = $('<option>').attr('value', department.id).text(department.name);

                        @if(isset($data["department_id"]))

                        if (department.id == '{{ $data["department_id"] }}') {
                            option.prop('selected', true);
                        }

                        @endif
                        departmentDropdown.append(option);
                    });

                    // data.forEach(function(department) {
                    //     var option = $('<option>').attr('value', department.id).text(department.name);
                    //     departmentDropdown.append(option);
                    // });
                    //
                }
            });

        }

        @if(isset($data['department_id']))
        $(document).ready(function () {
            departmentChange();
        });
        @endif

        $('#selectDepartment').on('change', function () {
            departmentChange();
        });
        var department_id;

        function departmentChange() {

            department_id = $('#selectDepartment').val();
            @if(isset($data['department_id']))
                department_id = {{$data['department_id']}};
            @endif

            $.ajax({
                type: 'get',
                url: '{{ route('hr.fetchEmployee') }}',
                data: {
                    department_id: department_id
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    var employeesDropdown = $('#selectEmployee').empty();
                    employeesDropdown.append('<option value="">Select Employee</option>');

                    data.forEach(function (employee) {
                        var option = $('<option>').attr('value', employee.id).text(employee.name);

                        @if(isset($data["employee_id"]))

                        if (employee.id == '{{ $data["employee_id"] }}') {
                            option.prop('selected', true);
                        }
                        @endif


                        employeesDropdown.append(option);
                    });
                },
            });
        }

    </script>

@endsection
