@extends('admin.layouts.main')

@section('title')
   Eobi Create
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Eobi </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.eobis.index') !!}" class="btn btn-primary btn-sm "> Back </a>
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
                        <form action="{!! route('hr.eobis.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf


                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:10px;">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label for="branch"><b>Branch Name *</b></label>
                                            <select class="form-select-lg select2  select2-selection --single"
                                                    name = "branch_id" required id="selectBranch"  aria-label="Default select example">
                                                <option >Select Branch</option>
                                                @foreach($branches as $branch)
                                                    <option
                                                        value="{!! $branch->id !!}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="employee_leave_applying"><b>Department</b></label>
                                                <select class="form-select select2" id="selectDepartment"
                                                        aria-label="Default select example">


                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="employee"><b>Employee Name </b></label>
                                            <select class="form-select-lg select2 select2-selection--single" id="selectEmployee"
                                                    aria-label="Default select example">
                                            </select>
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


{{--                            <div class="w-100 p-3">--}}
{{--                                <div class="box-body">--}}

{{--                                    <button type="button" id="add-row-btn" class="btn btn-primary">Add Row</button>--}}
{{--                                    <table class="table table-bordered table-striped" style="margin-top: 10px">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th><b>Employee</b></th>--}}
{{--                                            <th><b>Total %</b></th>--}}
{{--                                            <th><b>Employee %</b></th>--}}
{{--                                            <th><b>Company %</b></th>--}}
{{--                                            <th><b>Actions</b></th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody id="table-body">--}}

{{--                                                                                @foreach($taxes as $tax)--}}
{{--                                        <tr>--}}
{{--                                            <td>--}}
{{--                                                                                                    <input type="hidden" name="tax_type[]" value="rentalTax">--}}
{{--                                                <select  class="form-select select2 basic-single" required name="employee_id[]">--}}
{{--                                                    @foreach($employees as $employee)--}}
{{--                                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </td>--}}
{{--                                                                                            <td><input type="number" class="form-control" required name="fix_amount[]" value="{{ $tax->fix_amount }}"></td>--}}
{{--                                            <td><input type="text" class="form-control" required name="total[]" value="{{ $eobi_values['total'] }}"  ></td>--}}
{{--                                            <td><input type="text" class="form-control" required name="employee[]"  value="{{ $eobi_values['employee'] }}" ></td>--}}
{{--                                            <td><input type="text" class="form-control" required name="company[]"  value="{{ $eobi_values['company'] }}"></td>--}}
{{--                                            <td>--}}
{{--                                                <button type="button" class="btn btn-danger delete-row"><i class="fas fa-trash"></i></button>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                                                                @endforeach--}}
{{--                                        </tbody>--}}
{{--                                        <tfoot>--}}
{{--                                        <tr>--}}
{{--                                            <td colspan="6">--}}
{{--                                                <button type="submit" class="btn btn-primary" >Save</button>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        </tfoot>--}}
{{--                                    </table>--}}

{{--                                </div>--}}
{{--                            </div>--}}

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
{{--    <script>--}}
{{--        $(document).ready(function () {--}}

{{--            $('#add-row-btn').click(function () {--}}
{{--                var totalValue = "{{ $eobi_values['total'] }}";--}}
{{--                var employeeValue = "{{ $eobi_values['emplo1	Bethany Cyee'] }}";--}}
{{--                var companyValue = "{{ $eobi_values['company'] }}";--}}

{{--                var newRow = '<tr>' +--}}
{{--                    '<td>' +--}}
{{--                    '<select class="form-select select2 basic-single" required name="employee_id[]">' +--}}
{{--                    '@foreach($employees as $employee)' +--}}
{{--                    '<option value="{{ $employee->id }}">{{ $employee->name }}</option>' +--}}
{{--                    '@endforeach' +--}}
{{--                    '</select>' +--}}
{{--                    '</td>' +--}}
{{--                    '<td><input type="text" required class="form-control" name="total[]" value="' + totalValue + '"></td>' +--}}
{{--                    '<td><input type="text" required class="form-control" name="employee[]" value="' + employeeValue + '"></td>' +--}}
{{--                    '<td><input type="text" required class="form-control" name="company[]" value="' + companyValue + '"></td>' +--}}
{{--                    '<td><button type="button" class="btn btn-danger delete-row"><i class="fas fa-trash"></i></button></td>' +--}}
{{--                    '</tr>';--}}

{{--                $('#table-body').append(newRow);--}}
{{--            });--}}




{{--            $(document).on('click', '.delete-row', function () {--}}
{{--                $(this).closest('tr').remove();--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}



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
                        var employeesDropdown = $('#selectEmployee').empty();

                        employeesDropdown.append('<option value="">Select Employee</option>');

                        data.forEach(function (employee) {
                            employeesDropdown.append('<option value="' + employee.id + '">' + employee.name + '</option>');
                        });
                    },

                });
            });


        });
    </script>

    {{--    for load employees on button click--}}
    <script>
        $("#Load").click(function () {

            var branch_id = $('#selectBranch').val();
            var department_id = $('#selectDepartment').val();
            var employee_id = $('#selectEmployee').val();

            $.ajax({

                url: "{{route('hr.eobi.data')}}",
                type: 'POST',
                data: {
                    'branch_id': branch_id,
                    'department_id': department_id,
                    'employee_id': employee_id,
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    $('#loadData').html(data);
                },
                error: function (request, error) {
                    console.log("Request: " + JSON.stringify(request));
                }
            });
        });
    </script>



@endsection
