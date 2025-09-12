@extends('admin.layouts.main')

@section('title')
    Attendance
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Attendance</h3>
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
                        <form action="{{ route('hr.attendance.update',$attendance->id) }}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')
{{--                            @dd($attendance)--}}
                            <div class="w-100 p-3">
                                <div class="col-lg-3" style="float: right;margin-bottom: 40px">
                                    <label for="start-date"><b> Date*</b></label>
                                    <div class="input-group">
                                        <div class="input-group-text ">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                        <input required name="start_date"
                                               class="form-control datepicker-date"
                                               id="start-date" placeholder="MM/DD/YYYY"
                                               type="text" readonly value="{{ $attendance->attendance_date }}">
                                    </div>
                                </div>
                                <div class="box-body" >
                                    <input type="hidden" name="branch_id" value="{!! $attendance->branch_id !!}">
                                <table id="users-table" class="table table-bordered table-striped datatable mt-3"
                                           style="width: 100%">
                                        <thead>

                                        <tr>
                                            <th>Sr.#</th>
                                            <th>User Name</th>
                                            <th>Status</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                            <th>Remarks</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{--@dd($attendance)--}}
                                        @php($i=1)

                                        <tr>
                                            <td>
                                                {{$i++}}
                                            </td>
                                            <td>
                                                {!! @$attendance->employee->name !!}
                                                <input type="hidden" name="employee_id"
                                                       value="{{$attendance->employee_id}}">
                                            </td>
                                            <td>
                                                <select readonly name="status" id="status-{!! $attendance->id !!}"
                                                        data-id="{!! $attendance->id !!}"
                                                        class="form-control status">

                                                    <option value="1" {{$attendance->status == 1 ? 'selected' : ''}}>
                                                        Present
                                                    </option>
{{--                                                    <option value="2" {{$attendance->status == 2 ? 'selected' : ''}}>--}}
{{--                                                        Absent--}}
{{--                                                    </option>--}}
                                                </select>
                                            </td>
                                            <td>
                                                <input name="timeIn" readonly id="timeIn-{!! $attendance->id !!}"
                                                       data-id="{!! $attendance->id !!}" type="time"
                                                       class="form-control" value="{!! $attendance->timeIn !!}"
                                                       data-old-value="{!! $attendance->timeIn !!}">
                                            </td>
                                            <td>
                                                <input name="timeOut" readonly id="timeOut-{!! $attendance->id !!}"
                                                       data-id="" type="time"
                                                       class="form-control" value="{!! $attendance->timeOut !!}"
                                                       data-old-value="{!! $attendance->timeOut !!}">
                                            </td>
                                            <td>
                                                <input name="remarks" id="remarks-{!! $attendance->id !!}"
                                                       data-id="{!! $attendance->id !!}"
                                                       placeholder="Remarks" value="{!! $attendance->remarks !!}"
                                                       data-old-value="{!! $attendance->remarks !!}"
                                                       type="text" class="form-control">
                                            </td>
                                        </tr>


                                        </tbody>
                                    </table>


                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary"
                                            style="margin-bottom: 10px;margin-left: 10px;">Save
                                    </button>
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
            status();

            $('.status').on('change', function () {
                status();
            });

            function status() {
                $('.status').each(function() {
                    var id = $(this).data('id');
                    var timeInField = $("#timeIn-" + id);
                    var timeOutField = $("#timeOut-" + id);
                    var remarksField = $("#remarks-" + id);

                    if ($(this).val() == 1) {
                        timeInField.prop('readonly', false).val(timeInField.data('old-value'));
                        timeOutField.prop('readonly', false).val(timeOutField.data('old-value'));
                        remarksField.prop('readonly', false).val(remarksField.data('old-value'));
                    } else {
                        timeInField.prop('readonly', true).val('');
                        timeOutField.prop('readonly', true).val('');
                        remarksField.prop('readonly', true).val('');
                    }
                });
            }
        });

    </script>


@endsection

