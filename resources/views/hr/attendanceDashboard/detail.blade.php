@extends('admin.layouts.main')

@section('title')
    Attendance | Detail
@stop
@section('css')

@endsection

@section('content')

    {{--    @dd($branch)--}}


    <div class="card">
        <div class="card-body">
            <form method="get" action="{{ route('hr.attendance.detail') }}">
                <div class="row mb-3">

                    <div class="col-md-4">
                        <label for="employee_id"><b>Branch:*</b></label>
                        <select class="form-select-lg select2 select2-selection--single"
                                name="branch_id" required id="selectBranch" aria-label="Default select example">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $selectedBranch == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="employee_id"><b>Department:*</b></label>
                        <select class="form-select select2" required name="department_id" id="selectDepartment"
                                aria-label="Default select example">

                            @if(isset($department))
                                <option value="{{ $department['id'] }}">{{ $department['name'] }}</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="employee_id"><b>Employee:*</b></label>
                        <select required class="form-select-lg select2 select2-selection--single" name="employee_id"
                                id="selectEmployee"
                                aria-label="Default select example">
                            @if(isset($employeeData))
                                <option value="{{ $employeeData['id'] }}">{{ $employeeData['name'] }}</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="row mb-3">

                    <div class="col-md-4">
                        <label for="start_date"><b>Start Date:*</b></label>
                        <input type="date" id="start_date" required name="start_date" value="{{ $start_date }}"
                               class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label for="end_date"><b>End Date:*</b></label>
                        <input type="date" id="end_date" required name="end_date" value="{{ $end_date }}" class="form-control">
                    </div>

                    <div class="col" style="margin-top: 25px">
                        <button id="filter_btn" class="btn btn-primary">Apply Filter</button>
                    </div>
                </div>
            </form>

        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="btn-group mb-3">
                <a href="{{ route('hr.export.pdf', ['branch_id' => $selectedBranch ?? '', 'department_id' => $department->id ?? '', 'employee_id' => $employeeData->id ?? '', 'start_date' => $start_date ?? '', 'end_date' => $end_date ?? '']) }}" class="btn btn-primary" target="_blank">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export to PDF
                </a>

            </div>


            <table class="table table-bordered">
                <thead>
                <tr style="background-color: black;">

                    <th style="color: white;text-align: center;">Dates</th>
                    <th style="color: white;text-align: center;">Day</th>
                    <th style="color: white;text-align: center;"> Status</th>
                    <th style="color: white;text-align: center;">CheckIn Time</th>
                    <th style="color: white;text-align: center;">CheckOut Time</th>
{{--                    <th>Time Late</th>--}}
                    <th style="color: white;text-align: center;">Working Hours</th>
                </tr>
                </thead>

                <tbody style="text-align: center;">
                @foreach($employees_data as $employee)

                    @foreach($employee['attendance'] as $key => $attendance)
                        @php
                            $attendanceData = $attendance ?? null;
                           $day = Carbon\Carbon::parse($key)->format('l');

                          $date = Carbon\Carbon::parse($key);
                         $formattedDate = $date->format('d F Y');

                        @endphp
                        @if($attendanceData)
                            <tr>

                                <td>{{ $formattedDate }}</td>
                                <td>{{ $day }}</td>

                                @if($attendanceData['present'] == true )
                                    <td><p style="color: darkgreen;">Present</p></td>
                                    <td style="color: darkgreen;">{!! $attendanceData['checkin_time'] ?? 'N/A' !!}</td>
                                    <td style="color: darkgreen;">{!! $attendanceData['checkout_time'] ?? 'N/A' !!}</td>
                                    <td style="color: darkgreen;">{!! $attendanceData['total_hours_worked'] ?? 'N/A' !!}</td>

                                @elseif($attendanceData['offDay'] == true)
                                    <td><p style="color: blue;">Day off</p></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @elseif($attendanceData['absent'] == true)
                                    <td><p style="color : red;">Absent</p></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @else
                                    <td colspan="6">Unknown status</td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection

@section('js')


    <script>
        @if(isset($selectedBranch))
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

                        @if(isset($department["id"]))

                        if (department.id == '{{ $department["id"] }}') {
                            option.prop('selected', true);
                        }

                        @endif
                        departmentDropdown.append(option);
                    });

                }
            });

        }

            @if(isset($department))
                $(document).ready(function () {
                    departmentChange();
                });
             @endif

        $('#selectDepartment').on('change', function () {
            departmentChange();
        });


        function departmentChange() {

            var department_id = $('#selectDepartment').val();
{{--            @if(isset($department))--}}
{{--                department_id = {{$department["id"]}};--}}
{{--            @endif--}}

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

                        @if(isset($employeeData))

                        if (employee.id == '{{ $employeeData['id'] }}') {
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

