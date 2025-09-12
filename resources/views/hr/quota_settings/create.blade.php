@extends('admin.layouts.main')

@section('title')
    Quotta Create
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Quota</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.qouta_sections.index') !!}" class="btn btn-primary btn-md">
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
                        <form action="{!! route('hr.qouta_sections.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:50px;">

                                        <div class="row">
                                            <div class="form-group col-lg-6">
                                                <label for="type">Leave Type*</label>
                                                <input type="text" name="name" placeholder="Leave type"
                                                       class="form-control" required>
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label for="shot_name">Permitted Days*</label>
                                                <input type="number" name="permit_days" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="form-group col-lg-12">
                                                <label for="holiday_date">Departments*</label>
                                                <select  class="form-select select2 basic-single"
                                                        aria-label="Default select example" name="departments[]"
                                                        multiple="multiple">
                                                    @foreach($departments as $department)
                                                        <option
                                                            value="{{$department->id}}">{{$department->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
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


@endsection

