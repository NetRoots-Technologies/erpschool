@extends('admin.layouts.main')

@section('title')
    Leave Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Leave Request</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.leave_requests.index') !!}" class="btn btn-primary btn-md">
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
                        <div class="container">
                            <form action="{{ route('hr.leave_requests.store') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <table class="table table-responsive-md">
                                    <tr>
                                        <td width="70%">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="employee_leave_applying"><b>Employee Applying
                                                                Leave*</b></label>
                                                        <select class="form-select select2 employee_id"
                                                                name="employee_id" id="employee_id"
                                                                aria-label="Default select example">
                                                            <option value="" selected>Open this select menu</option>
                                                            @foreach($employees as $employee)
                                                                <option
                                                                    value="{!! $employee->id !!}">{{$employee->name}}</option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="employee_leave_applying"><b>Leave Type*</b></label>
                                                        <select class="form-select select2" id="leave_type"
                                                                name="leave_type_id"
                                                                aria-label="Default select example">
                                                            <option selected value="">Open this select menu</option>

                                                        </select>
                                                    </div>
                                                </div>


                                                {{--                                            <div class="col-md-4">--}}
                                                {{--                                                <div class="form-group">--}}
                                                {{--                                                    <label for="Work Shift"><b>Work Shifts*</b></label>--}}
                                                {{--                                                    <select class="form-select select2" name="work_id"--}}
                                                {{--                                                            aria-label="Default select example">--}}
                                                {{--                                                        <option selected value="">Select WorkShift</option>--}}
                                                {{--                                                        @foreach($workshifts as $workshift)--}}
                                                {{--                                                            <option--}}
                                                {{--                                                                value="{!! $workshift->id !!}">{{$workshift->name}}</option>--}}
                                                {{--                                                        @endforeach--}}

                                                {{--                                                    </select>--}}
                                                {{--                                                </div>--}}
                                                {{--                                            </div>--}}
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="start-date"><b>Start Date*</b></label>
                                                    <div class="input-group">
                                                        <div class="input-group-text ">
                                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                        </div>
                                                        <input required name="start_date"
                                                               class="form-control datepicker-date"
                                                               id="start-date" placeholder="MM/DD/YYYY"
                                                               type="text">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="dob"><b>End Date*</b></label>
                                                    <div class="input-group">
                                                        <div class="input-group-text ">
                                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                        </div>
                                                        <input required name="end_date"
                                                               class="form-control datepicker-date"
                                                               id="end-date" placeholder="MM/DD/YYYY"
                                                               type="text">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="days"><b>Number of Days</b></label>
                                                    <div class="input-group">
                                                        <input readonly name="days_number" class="form-control"
                                                               id="num-days"
                                                               type="text">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row mt-3">
                                                {{--                                            <div class="col-md-4">--}}
                                                {{--                                                <label for="paid_leaves"><b>Paid Leaves*</b></label>--}}
                                                {{--                                                <div class="input-group">--}}
                                                {{--                                                    <input name="paid_leaves" class="form-control" id="paid-leaves"--}}
                                                {{--                                                           type="number">--}}
                                                {{--                                                </div>--}}
                                                {{--                                            </div>--}}

                                                {{--                                            <div class="col-md-4">--}}
                                                {{--                                                <label for="paid_leaves"><b>Unpaid Leaves*</b></label>--}}
                                                {{--                                                <div class="input-group">--}}
                                                {{--                                                    <input name="unpaid_leaves" class="form-control" id="unpaid-leaves"--}}
                                                {{--                                                           type="number">--}}
                                                {{--                                                </div>--}}
                                                {{--                                            </div>--}}

                                                <div class="col-md-6" id="select-duration" style="display: none">
                                                    <label for="paid_leaves"><b>Duration</b></label>
                                                    <div class="input-group">
                                                        <select class="form-select select2" name="duration"
                                                                aria-label="Default select example" id="days_select">
                                                            <option value="null">Open this select menu</option>
                                                            <option value="full_day">Full Day</option>
                                                            <option value="half_day">Half Day</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3" id="time-duration" style="display: none">
                                                <div class="col-md-6">
                                                    <label for="duration"><b>Start*</b></label>
                                                    <input type="time" class="form-control" id="start-time"
                                                           name="start_time">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="duration"><b>End*</b></label>
                                                    <input type="time" class="form-control" id="end-time"
                                                           name="end_time">
                                                </div>

                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="employee_leave_applying"><b>Responsible Employee in
                                                                absense</b></label>
                                                        <select
                                                            class="form-select-lg select2 select2-selection select2-selection --single"
                                                            name="responsible_employee"
                                                            aria-label="Default select example">
                                                            <option selected>Select an Employee</option>
                                                            @foreach($employees as $employee)
                                                                <option
                                                                    value="{!! $employee->id !!}">{{$employee->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="file"><b>Evidence/Attachment</b></label>
                                                    <input type="file" class="form-control" name="employee_image">
                                                </div>

                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label for="comments"><b>Comments</b></label>
                                                    <textarea rows="2" cols="2" name="comment"
                                                              class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                            {{--                            </form>--}}
                                        </td>
                                        <td><p align="center"><b>Leave Balance Details</b></p><span
                                                id="loadEmployeeEntitlements"></span></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')


@endsection
@section('js')

    <script>
        $(document).ready(function () {
            $('#employee_id').on('change', function () {
                var employee_id = $(this).val();
                $.ajax({
                    type: 'get',
                    url: '{{route('hr.leave_type')}}',
                    data: {
                        employee_id: employee_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        var leaveTypes = data;


                        $('#leave_type').empty();

                        $('#leave_type').append($('<option>', {
                            value: '',
                            text: 'Select Leave'
                        }));

                        $.each(leaveTypes, function (index, leaveType) {
                            $('#leave_type').append($('<option>', {
                                value: leaveType.id,
                                text: leaveType.leave_type
                            }));
                        });

                    },

                });
            });
        });

    </script>

    @include('hr.leave_requests.js')
@endsection

