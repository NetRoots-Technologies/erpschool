@extends('admin.layouts.main')

@section('title')
    Child Benefit Create
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Child Benefit </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.child-benefits.index') !!}" class="btn btn-primary btn-sm ">
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
                        <form action="{!! route('hr.child-benefits.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf


                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:10px;">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <label for="branch"><b>Employee *</b></label>
                                            <select class="form-select-lg select2 select2-selection --single"
                                                    name="employee_id" required id="selectEmployee"
                                                    aria-label="Default select example">
                                                @foreach($employees as $employee)
                                                    <option value="{{$employee->id}}">{!! $employee->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="branch"><b>Student *</b></label>
                                            <select class="form-select-lg select2 select2-selection --single"
                                                    name="student_id" required id="selectStudent"
                                                    aria-label="Default select example">
                                                @foreach($students as $student)
                                                    <option value="{{$student->id}}">{!! $student->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="branch"><b>Discount *</b></label>

                                        </div>

                                    </div>

                                    <div class="row mt-5">
                                        <div id="loadData"></div>
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

{{--    <script>--}}
{{--        $(document).ready(function() {--}}
{{--            $('#selectEmployee').select2({--}}
{{--                placeholder: "Select Employee",--}}
{{--                --}}
{{--            });--}}


{{--            $('#selectEmployee').on('input', function() {--}}
{{--                var inputValue = $(this).val();--}}
{{--                if (inputValue.length >= 2) {--}}
{{--                    fetchEmployee(inputValue);--}}
{{--                }--}}
{{--            });--}}

{{--            function fetchEmployee(inputValue) {--}}
{{--                $.ajax({--}}
{{--                    url: '{{ route("hr.fetchEmployees") }}',--}}
{{--                    type: 'GET',--}}
{{--                    dataType: 'json',--}}
{{--                    data: { input: inputValue },--}}
{{--                    success: function(response) {--}}

{{--                        $('#selectEmployee').empty();--}}


{{--                        $.each(response, function(index, employee) {--}}
{{--                            $('#selectEmployee').append($('<option>', {--}}
{{--                                value: employee.id,--}}
{{--                                text: employee.name--}}
{{--                            }));--}}
{{--                        });--}}

{{--                        $('#selectEmployee').trigger('change');--}}
{{--                    },--}}
{{--                    error: function(xhr, status, error) {--}}
{{--                        console.error('Error:', error);--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}

{{--    </script>--}}

@endsection
