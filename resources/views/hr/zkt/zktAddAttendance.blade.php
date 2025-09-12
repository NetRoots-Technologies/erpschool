@extends('admin.layouts.main')

@section('title')
    Employee Attendance
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
                                <a href="{!! route('hr.attendance.index') !!}" class="btn btn-primary btn-sm "> Back </a>
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
                        <form action="{!! route('hr.attendance.store')!!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="box-body"  >
                                <div class="row mt-2">

                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-6">
                                        <label for="date">DATE </label>
                                        <input name="date" type="date" class="form-control" placeholder="date" required/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="employee">Employees  </label>
                                        <select required id="type"
                                                class="select2 form-control" name="employee">
                                            <option value="">Select Employee</option>
                                            @foreach ($employee as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
{{--                                    <div class="col-lg-6">--}}
{{--                                        <label for="status">STATUS  </label>--}}
{{--                                        <select required id="type"--}}
{{--                                                class="select2 form-control" name="status">--}}
{{--                                            <option value="">Select Status</option>--}}
{{--                                                <option value="0">Check In</option>--}}
{{--                                                <option value="1">Check Out</option>--}}
{{--                                                <option value="4">Overtime In</option>--}}
{{--                                                <option value="5">Overtime Out</option>--}}

{{--                                        </select>--}}
{{--                                    </div>--}}

{{--                                        <input name="is_machine" type="text" class="form-control" placeholder="is_machine" value="null" hidden />--}}
{{--                                    <div class="col-lg-6">--}}
{{--                                        <label for="user_id">USER ID</label>--}}
{{--                                        <input name="user_id" type="number" class="form-control" placeholder="user_id" required/>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="row mt-2">



{{--                                    <div class="col-lg-6">--}}
{{--                                        <label for="type" class="form-label">TYPE</label>--}}
{{--                                        <input name="type" class="form-control" type="text" placeholder="User Type" value="user" required>--}}
{{--                                    </div>--}}


                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-6">
                                        <label for="checkin_time">CHECK-IN TIME</label>
                                        <input name="checkin_time" type="time" class="form-control" placeholder="checkin_time" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="checkout_time">CHECK-OUT TIME</label>
                                        <input name="checkout_time" type="time" class="form-control" placeholder="checkout_time" />
                                    </div>

                                </div>
{{--                                <div class="row mt-2">--}}
{{--                                    <div class="col-lg-6">--}}
{{--                                        <label for="overtime_in">OVER TIME IN</label>--}}
{{--                                        <input name="overtime_in" type="time" class="form-control" placeholder="overtime_in" />--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-6">--}}
{{--                                        <label for="overtime_out" class="form-label">OVER TIME OUT</label>--}}
{{--                                        <input name="overtime_out" class="form-control" type="time" placeholder="overtime_out" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="row mt-2">

                                    <input name="manual_attendance" type="text" value="Manual" class="form-control" hidden/>


                                </div>

                                <div class=" row mt-5 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn btn-sm btn-primary">Save  </button>
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
@endsection
@section('js')

@endsection
