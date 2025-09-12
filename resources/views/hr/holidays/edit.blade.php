@extends('admin.layouts.main')

@section('title')
    Holiday Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Holidays</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.holidays.index') !!}" class="btn btn-primary btn-md"> Back </a>
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
                        <form action="{!! route('hr.holidays.update', $holidays->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:50px;">
                                    <h5>Holiday Data</h5>

                                    <div class="form-row">
                                        <div class="form-group col-lg-6">
                                            <label for="name">Name*</label>
                                            <input type="text" name="name" value="{!! $holidays->name !!}" placeholder="name" class="form-control" required>
                                        </div>


                                        <div class="col-md-6">
                                            <label for="branches">Branch Name </label>
                                            <select id="branchSelect"  name="branch_id" class="form-select select2 basic-single mt-3 branch_select" aria-label=".form-select-lg example">
                                                <option value="">Select Branch</option>
                                                <option value="0">All Branches</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{$branch->id}}" {{$holidays->branch_id == $branch->id ? 'selected' : ''}}>{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-lg-6 mt-2">
                                            <label for="department">Department<b>*</b></label>
                                            <select id="departmentDropdown" name="department_id"
                                                    class="form-select select2 basic-single departmentDropdown" >

                                                <input type="hidden"  id="department_val" value="{!! $holidays->department_id  !!}">
                                            </select>
                                        </div>

                                        <div class="col-lg-6 mt-2">
                                            <label for="employee">Employee<b>*</b></label>
                                            <select id="employeeSelect"  name="employee_id"
                                                    class="form-select select2 basic-single employeeSelect" >
                                                <option>Select Employee</option>
                                                <input type="hidden" id = "employee_value" value="{!! $holidays->employee_id  !!}">

                                            </select>
                                        </div>

                                        <div class="form-group col-lg-6">
                                            <label for="holiday_date">From Date*</label>
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input type="text" name="holiday_date" id="datepicker-date"
                                                       placeholder="MM/DD/YYYY" value="{!! $holidays->holiday_date !!}" class="form-control datepicker-date" required>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-6">
                                            <label for="holiday_date_to">To Date*</label>
                                            <div class="input-group">
                                                <div class="input-group-text">
                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                </div>
                                                <input type="text" name="holiday_date_to" id="datepicker-date-to"
                                                       placeholder="MM/DD/YYYY" value="{!! $holidays->holiday_date_to !!}" class="form-control datepicker-date" required>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-6">
                                            <label for="is_recurring">Repeats Annually</label>
                                            <input type="checkbox" value="{!! $holidays->is_recurring ? '1' : '0' !!}" name="is_recurring" {{ $holidays->is_recurring ? 'checked' : '' }}>

                                        </div>

                                        <div class="form-group col-lg-6">
                                            <label for="holiday_length">Holiday Length*</label>
                                            <select name="holiday_length" class="form-control">
                                                <option value="full_day" {{ $holidays->length == 'full_day' ? 'selected' : '' }}>Full Day</option>
                                                <option value="half_day" {{ $holidays->length == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                            </select>
                                        </div>



                                    </div>

                                    <button type="submit" class="btn btn-primary">Update</button>

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
            $('.branch_select').on('change', function () {

                var branch_id = $(this).val();
                var department_id = $('#department_val').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.holiday.departments') }}',
                    data: {
                        branch_id: branch_id,
                        department_id : department_id,
                    },
                    success: function (data) {

                        var departmentDropdown = $('#departmentDropdown');
                        departmentDropdown.append(data.html);
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }


                });
            }).change();
        });
    </script>


    <script>
        var department_id;
        $('.departmentDropdown').on('change', function () {

            department_id = $(this).val();
            if(department_id ==null){
                 department_id = $('#department_val').val();
            }
            var employee_id = $('#employee_value').val();
            $.ajax({
                type: 'GET',
                url: '{{ route('hr.holiday.employees') }}',
                data: {
                    department_id: department_id,
                    employee_id : employee_id,
                },
                success: function (data) {

                    var employeeDropdown = $('.employeeSelect').empty();
                    employeeDropdown.append(data.html);
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                }


            });
        }).change();
    </script>

    <script>
        $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

    </script>

@endsection

