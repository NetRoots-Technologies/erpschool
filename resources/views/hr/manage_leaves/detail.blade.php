@extends('admin.layouts.main')

@section('title')
    Department Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4">Leave Details</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.manage_leaves.index') !!}" class="btn btn-primary btn-sm ">
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
                        <div class="container mt-5">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                <tr>
                                    <th> Name</th>
                                    <td colspan="3">@if($LeaveRequest->hrm_employee_id){{ $LeaveRequest->employee->name }}@else{{ 'N/A' }}@endif</td>
                                </tr>

                                <tr>
                                    <th>Leave Type </th>
                                    <td>@if($LeaveRequest->hr_quota_setting_id){{ $LeaveRequest->Quota->leave_type }}@else{{ 'N/A' }}@endif</td>
                                    <th>Work Shift </th>
                                    <td>@if($LeaveRequest->work_shift_id){{ $LeaveRequest->workShift->name }}@else{{ 'N/A' }}@endif</td>
                                </tr>
                                <tr>
                                    <th>Start Date </th>
                                    <td>@if($LeaveRequest->start_date){{ $LeaveRequest->start_date  }}@else{{ 'N/A' }}@endif</td>
                                    <th>End Date</th>
                                    <td>@if($LeaveRequest->end_date){{ $LeaveRequest->end_date  }}@else{{ 'N/A' }}@endif</td>
                                </tr>
                                <tr>
                                    <th>Days</th>
                                    <td>@if($LeaveRequest->days){{ $LeaveRequest->days }}@else{{ 'N/A' }}@endif</td>
                                    <th>Applied Date</th>
                                    <td>@if($LeaveRequest->created_at){{ $LeaveRequest->created_at->format('y-m-d') }}@else{{ 'N/A' }}@endif</td>
                                </tr>

                                <tr>
                                    <th>Comments</th>
                                    <td colspan="3">@if($LeaveRequest->comments){{ $LeaveRequest->comments }}@else{{ 'N/A' }}@endif</td>

                                </tr>


                                </tbody>
                            </table>
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
        $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#end-date, #start-date').on('change', function () {
                var start_date_str = $('#start-date').val();
                var end_date_str = $('#end-date').val();

                var start_date = moment(start_date_str, "YYYY-MM-DD");
                var end_date = moment(end_date_str, "YYYY-MM-DD");

                if (end_date < start_date || start_date > end_date) {
                    alert("End Date should be greater than Start Date");
                    $('#end-date').val('');
                    $('#num-days').val('');
                } else {
                    var days = 1;
                    $('#select-duration').show();
                    if (!start_date.isSame(end_date, 'day')) {
                        days = end_date.diff(start_date, 'days') + 1;
                        $('#select-duration').hide();

                    }

                    $('#num-days').val(days);
                }
            });
        });
    </script>

    <script>

        $(document).ready(function () {


            $('#days_select').on('change', function () {
                console.log(12);
                var days = $(this).val();
                if (days === 'half_day') {
                    $('#time-duration').show()
                } else {
                    $('#time-duration').hide()
                }

            })
        })

    </script>


@endsection

