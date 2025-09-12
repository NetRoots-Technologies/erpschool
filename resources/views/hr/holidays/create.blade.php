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
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create Holidays</h3>
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
                    <form action="{{ route('hr.holidays.store') }}" enctype="multipart/form-data" id="form_validation"
                        autocomplete="off" method="post">
                        @csrf
                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:50px;">
                                <h5>Holiday Data</h5>

                                <div class="form-row">
                                    <div class="form-group col-lg-6">
                                        <label for="name">Name*</label>
                                        <input type="text" name="name" placeholder="name" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="branches">Branch Name <b>*</b></label>
                                        <select id="branchSelect" required name="branch_id"
                                            class="form-select select2 basic-single mt-3 branch_select"
                                            aria-label=".form-select-lg example">
                                            <option value="">Select Branch</option>
                                            <option value="0">All Branches</option>
                                            @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-lg-6 mt-2">
                                        <label for="email">Department<b>*</b></label>
                                        <select id="departmentDropdown" required name="department_id"
                                            class="form-select select2 basic-single departmentDropdown">
                                            <option>Select Department</option>

                                        </select>
                                    </div>

                                    <div class="col-lg-6 mt-2">
                                        <label for="email">Employee<b>*</b></label>
                                        <select id="employeeSelect" required name="employee_id"
                                            class="form-select select2 basic-single employeeSelect">
                                            <option>Select Employee</option>

                                        </select>
                                    </div>


                                    <div class="form-group col-lg-6">
                                        <label for="holiday_date">From Date*</label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                            <input type="text" name="holiday_date" id="datepicker-date"
                                                placeholder="MM/DD/YYYY" class="form-control datepicker-date" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label for="holiday_date_to">To Date*</label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                            </div>
                                            <input type="text" name="holiday_date_to" id="datepicker-date-to"
                                                placeholder="MM/DD/YYYY" class="form-control datepicker-date" required>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label for="is_recurring">Repeats Annually</label>
                                        <input type="checkbox" name="is_recurring" value="1">
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label for="holiday_length">Holiday Length*</label>
                                        <select name="holiday_length" class="form-control" required>
                                            <option value="full_day">Full Day</option>
                                            <option value="half_day">Half Day</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>

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
            $(document).on('change', '.branch_select', function () {

                var branch_id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: @json(route('hr.holiday.departments')),
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {

                        var departmentDropdown = $('#departmentDropdown').empty();
                        departmentDropdown.append(data.html);
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }


                });
            });
        });

        $(document).on('change', '.departmentDropdown', function () {

            var department_id = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{ route('hr.holiday.employees') }}',
                data: {
                    department_id: department_id
                },
                success: function (data) {

                    var employeeDropdown = $('.employeeSelect').empty();
                    employeeDropdown.append(data.html);
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                }


            });
        });
    $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

</script>

@endsection