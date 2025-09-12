@extends('admin.layouts.main')

@section('title')
    Fee Category edit
@stop

@section('content')
    {{--    @dd($feeCategory)--}}
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Fee Category</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.fee-category.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('admin.fee-category.update',$feeCategory->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:20px;">


                                    <div class="row">

                                        <div class="col-md-3">
                                            <label for="Academic"><b>Academic Session *</b></label>
                                            <select name="session_id"
                                                    class="form-control session_select  select2 basic-single select_option"
                                                    required id="session_id">
                                                <option>Select Session</option>
                                                @foreach($sessions as $key => $item)
                                                    <option
                                                        value="{!! $key !!}" {!! $feeCategory->session_id == $key ? 'selected' : '' !!}>{!! $item !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="branches"><b>Company *</b></label>
                                            <select name="company_id"
                                                    class="form-control  select2 basic-single company_select select_option"
                                                    required id="companySelect">
                                                @foreach($companies as $item)
                                                    <option
                                                        value="{{$item->id}}" {!! $feeCategory->company_id == $item->id ? 'selected' : '' !!}>{{ $item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="branch_Style"><b>Branch*</b></label>
                                                </div>
                                                <select name="branch_id"
                                                        class="form-control  select2 basic-single branch_select select_option"
                                                        required id="branch_id">

                                                </select>
                                            </div>
                                        </div>

{{--                                        <div class="col-md-3">--}}
{{--                                            <label for="branches"><b>Class: *</b></label>--}}
{{--                                            <select required name="class_id"--}}
{{--                                                    class="form-select select2 basic-single mt-3 class_select select_option"--}}
{{--                                                    aria-label=".form-select-lg example">--}}

{{--                                            </select>--}}
{{--                                        </div>--}}

                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <label for="branches">Fee Category </label>
                                            <input type="text" class="form-control"
                                                   value="{!! $feeCategory->category !!}" name="category">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="branches">FA % <b>*</b></label>
                                            <input type="text" class="form-control"
                                                   value="{!! $feeCategory->fa_percent !!}" name="fa">
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <label for="full_fee">Full fee</label>
                                            <input type="checkbox" name="full_fee"
                                                   value="1" {!! $feeCategory->full_fee == '1' ? 'checked' : '' !!} id="full_fee">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="FA">FA</label>
                                            <input type="checkbox" name="FA" value="1" {!! $feeCategory->FA == '1' ? 'checked' : '' !!} id="FA" >
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
        function handleClick(cb) {
            cb.value = cb.checked ? 1 : 0;
            console.log(cb.value);
        }

        function FA(cb) {
            cb.value = cb.checked ? 1 : 0;
            console.log(cb.value);
        }

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

                            var selectedBranch = branch.id == '{{ $feeCategory->branch_id }}' ? 'selected' : '';

                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectedBranch + '>' + branch.name + '</option>');

                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();
        });
    </script>
    <script>

        var branch_id;
        $(document).ready(function () {
            $('.branch_select').on('change', function () {
                branch_id = $(this).val();
                console.log(branch_id);
                if (branch_id == null) {
                    branch_id = {!! $feeCategory->branch_id !!};
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.class_select').empty();

                        data.forEach(function (academic_class) {
                            var selectedClass = academic_class.id == '{{ $feeCategory->class_id }}' ? 'selected' : '';

                            sectionDropdown.append('<option value="' + academic_class.id + '" ' + selectedClass + '>' + academic_class.name + '</option>');

                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
            $('.select_option').attr("disabled", true);

        });

    </script>

@endsection

