@extends('admin.layouts.main')

@section('title')
    Fee Category Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Fee Category</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.fee-category.index') !!}" class="btn btn-primary btn-md"> Back </a>
                            </div>
                        </div>

                        <form action="{!! route('admin.fee-category.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:20px;">

                                    <div class="row">

                                        <div class="col-md-4">
                                            <label for="Academic"><b>Academic Session *</b></label>
                                            <select name="session_id"
                                                    class="form-control session_select  select2 basic-single"
                                                    required id="session_id">
                                                <option value="" selected disabled>Select Session</option>
                                                @foreach($sessions as $key => $item)
                                                    <option value="{!! $key !!}">{!! $item !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Company *</b></label>
                                            <select name="company_id"
                                                    class="form-control  select2 basic-single company_select"
                                                    required id="companySelect">
                                                <option value="" selected disabled>Select Company</option>
                                                @foreach($companies as $item)
                                                    <option
                                                        value="{{$item->id}}">{{ $item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="branch_Style"><b>Branch*</b></label>
                                                </div>
                                                <select name="branch_id"
                                                        class="form-control  select2 basic-single branch_select"
                                                        required id="branch_id">
                                                    <option value="" selected disabled>Select Branch</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label for="branches">Fee Category <b>*</b></label>
                                            <input type="text" class="form-control" name="category" id="categoryInput" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="branches">FA % <b>*</b></label>
                                            <input type="number" class="form-control" name="fa%" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label for="branches">Full fee </label>
                                            <input type="checkbox" name="full_fee" id="full_fee_checkbox" value="1" >
                                        </div>
                                        <div class="col-md-6">
                                            <label for="branches">FA </label>
                                            <input type="checkbox" name="FA" id="fa_checkbox" value="1" >
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
        $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

    </script>


    <script>
        $(document).ready(function () {

            $('#companySelect').on('change', function () {
                var selectedCompanyId = $('#companySelect').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {
                        var branchesDropdown = $('.branch_select').empty();

                        branchesDropdown.append('<option value="">Select Branch</option>');

                        data.forEach(function (branch) {
                            branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


            $('.branch_select').on('change', function () {

                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var classDropdown = $('.class_select').empty();
                        classDropdown.append('<option value="">Select class</option>');

                        data.forEach(function (academic_class) {
                            classDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            });
        })
    </script>

@endsection

