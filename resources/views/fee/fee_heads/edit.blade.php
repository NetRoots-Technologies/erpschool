@extends('admin.layouts.main')

@section('title')
    Fee Head Edit
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Update Fee Head</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.fee-heads.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('admin.fee-heads.update',$feeHead->id) !!}"
                              enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:20px;">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label for="Academic"><b>Academic Session </b></label>
                                            <select name="session_id"
                                                    class="form-control session_select  select2 basic-single"
                                                    id="session_id">
                                                <option value="" disabled selected>Select Session</option>
                                                @foreach($sessions as $key => $item)
                                                    <option
                                                        value="{!! $key !!}" {{ $feeHead->session_id == $key ? 'selected' : '' }}>{!! $item !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Company </b></label>
                                            <select name="company_id"
                                                    class="form-control  select2 basic-single company_select"
                                                    id="companySelect">
                                                @foreach($companies as $item)
                                                    <option
                                                        value="{{$item->id}}" {{ $feeHead->company_id == $item->id ? 'selected' : '' }}>{{ $item->name}}</option>
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

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="branches"><b>Class: *</b></label>
                                            <select name="class_id"
                                                    class="form-select select2 basic-single mt-3 class_select"
                                                    aria-label=".form-select-lg example" required>

                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Account Head: </b></label>
                                            <select name="account_head_id"
                                                    class="form-select select2 basic-single mt-3"
                                                    aria-label=".form-select-lg example" required>
                                                <option value="" disabled selected>Select Account Head</option>
                                                @foreach($accountHeads as $item)
                                                    <option
                                                        value="{{$item->id}}" {{ $feeHead->account_head_id == $item->id ? 'selected' : '' }}>{{ $item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Fee Section: </b></label>
                                            <select name="fee_section_id"
                                                    class="form-select select2 basic-single mt-3 fee_section"
                                                    aria-label=".form-select-lg example" required>

                                            </select>
                                        </div>

                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <label for="branches"><b>Fee Head: *</b></label>
                                            <input type="text" class="form-control" value="{!! $feeHead->fee_head !!}"
                                                   name="fee_head" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Details: </b></label>
                                            <input type="text" class="form-control"
                                                   value="{!! $feeHead->detail ?? '' !!}" name="detail" required>

                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Dividable *</b></label>
                                            <select class="form-select select2 basic-single" name="dividable">
                                                <option value="" disabled selected>Select Dividable</option>
                                                <option
                                                    value="yes" {{ $feeHead->dividable == 'yes' ? 'selected' : '' }}>Yes
                                                </option>
                                                <option value="no" {{ $feeHead->dividable == 'no' ? 'selected' : '' }}>
                                                    No
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>

                        </form>
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

                                branchesDropdown.append('<option value="" disabled selected>Select Branch</option>');

                                data.forEach(function (branch) {

                                    var selectedBranch = branch.id == '{{ $feeHead->branch_id }}' ? 'selected' : '';

                                    branchesDropdown.append('<option value="' + branch.id + '" ' + selectedBranch + '>' + branch.name + '</option>');

                                });
                            },
                            error: function (error) {
                                console.error('Error fetching branches:', error);
                            }
                        });
                    }).change();

                })

            </script>

            <script>
                var branch_id;

                $(document).ready(function () {
                    $('.branch_select').on('change', function () {
                        branch_id = $(this).val();
                        console.log(branch_id);
                        if (branch_id == null) {
                            branch_id = {!! $feeHead->branch_id !!};
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
                                    var selectedClass = academic_class.id == '{{ $feeHead->class_id }}' ? 'selected' : '';

                                    sectionDropdown.append('<option value="' + academic_class.id + '" ' + selectedClass + '>' + academic_class.name + '</option>');

                                });
                            },
                            error: function (error) {
                                console.error('Error fetching branches:', error);
                            }
                        });

                    }).change();

                })

            </script>

            <script>
                var branch_id
                $('.branch_select').on('change', function () {

                    branch_id = $(this).val();
                    console.log(branch_id);
                    if (branch_id == null) {
                        branch_id = {!! $feeHead->branch_id !!};
                    }
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('academic.fetchFeeSection') }}',
                        data: {
                            branch_id: branch_id
                        },
                        success: function (data) {
                            var feeSectionDropdown = $('.fee_section').empty();
                            feeSectionDropdown.append('<option value="">Select Section</option>');

                            data.forEach(function (fee_section) {

                                var selectedSection = fee_section.id == '{{ $feeHead->fee_section_id }}' ? 'selected' : '';
                                feeSectionDropdown.append('<option value="' + fee_section.id + '" ' + selectedSection + '>' + fee_section.name + '</option>');

                                // feeSectionDropdown.append('<option value="' + fee_section.id + '">' + fee_section.name + '</option>');
                            });
                        },
                        error: function (error) {
                            console.error('Error fetching branches:', error);
                        }
                    });

                });
            </script>

@endsection
