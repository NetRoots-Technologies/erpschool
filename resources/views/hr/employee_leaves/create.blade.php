@extends('admin.layouts.main')

@section('title')
    Employee Leaves

@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Employee Leaves

                        </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.employee_leaves.index') !!}" class="btn btn-primary btn-sm ">
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
                        <form action="{!! route('hr.employee_leaves.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            <div class="w-100">
                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="leave_title"> Leave Title <b>*</b> </label>
                                            <input required name="leave_title" id="leave_title" type="text"
                                                   class="form-control"
                                                   value="{{old('leave_title')}}"/>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="employee_id"> Employee Name </label>
                                            <select required id="employee_id"
                                                    class="select2 form-control" name="employee_id">
                                                <option value="">Select Employee</option>
                                                @foreach ($employee as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="leave_type"> Leave Type </label>
                                            <select required name="leave_type" id="leave_type" type="text"
                                                    class="form-control"
                                                    value="{{old('leave_type')}}">
                                                <option value="">Select Leave Type</option>
                                                <option value="1">Casual Leave</option>
                                                <option value="2">Half Leave</option>
                                                <option value="3">Annual Leave</option>

                                            </select>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="leave_reason"> Leave Reason </label>
                                            <input required name="leave_reason" id="leave_reason" type="text"
                                                   class="form-control"
                                                   value="{{old('leave_reason')}}"/>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="leave_reason"> Leave Date </label>
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input required name="leave_date" class="form-control"
                                                       id="datepicker-date" placeholder="MM/DD/YYYY"
                                                       type="text" value="{{old('leave_date')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr style="background-color: darkgray">
                                <div class="row mt-8 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">

                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            <a href="{!! route('hr.employee_leaves.index') !!}"
                                               class=" btn btn-sm btn-danger">Cancel </a>
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
    <script>
        $('#datepicker-date').bootstrapdatepicker({
            format: "dd-mm-yyyy",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });
    </script>


@endsection

