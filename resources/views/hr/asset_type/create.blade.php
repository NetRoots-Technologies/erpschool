@extends('admin.layouts.main')

@section('title')
Asset Type Create
@stop

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create Asset Type </h3>
                    <div class="row    mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{!! route('hr.asset_type.index') !!}" class="btn btn-primary btn-sm ">
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
                    <form action="{!! route('hr.asset_type.store') !!}" enctype="multipart/form-data"
                        id="form_validation" autocomplete="off" method="post">
                        @csrf


                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:10px;">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="asset_name"><b>Name <span class="danger">*</span></b></label>
                                        <input id="asset_name" type="text" name="name" class="form-control" required>
                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="depreciation"><b>Depreciation Percentage <span class="danger">*</span> </b></label>
                                        <input id="depreciation" type="number" min="0" max="100" name="depreciation" class="form-control" placeholder="10%" required>
                                    </div>
                                </div>
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

@endsection

@section('js')
<script>
    $(document).ready(function () {
        $("#form_validation").validate();

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

{{-- for load employees on button click--}}
<script>
    $("#Load").click(function () {

            var branch_id = $('#selectBranch').val();
            var department_id = $('#selectDepartment').val();
            var employee_id = $('#selectEmployee').val();

            $.ajax({

                url: "{{route('hr.overtime.data')}}",
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
