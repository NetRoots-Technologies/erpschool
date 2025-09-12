@extends('admin.layouts.main')

@section('title')
    Bill Generation
@stop

@section('content')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            max-height: 100px; /* Adjust this value according to your design */
            overflow-y: auto;
        }

    </style>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Update Bill Generation</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.bill-generation.index') !!}"
                                   class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('admin.bill-generation.update',$bill->id) !!}"
                              enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')

                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:10px;">

                                    <div class="row mt-4">

                                        <div class="col-md-4">
                                            <label><b>Class</b></label>
                                            <input type="text" class="form-control" value="{!! $bill->AcademicClass->name ?? '' !!}" disabled>
                                        </div>

                                        <div class="col-md-4">
                                            <label><b>Student</b></label>
                                            <input type="text" class="form-control" value="{!! $bill->student->first_name . ' ' . $bill->student->last_name ?? '' !!}" disabled>
                                        </div>

                                        <div class="col-md-4">
                                            <label><b>Fee</b></label>
                                            <input type="text" class="form-control" value="{!! $bill->fees ?? '' !!}" disabled>
                                        </div>

                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <label><b>Bill Date</b></label>
                                            <input type="date" value="{{ $bill->bill_date }}" name="bill_date" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label><b>Due Date</b></label>
                                            <input type="date" value="{{ $bill->due_date }}" name="due_date" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label><b>Valid Date</b></label>
                                            <input type="date" value="{{ $bill->valid_date }}" name="valid_date" class="form-control">
                                        </div>
                                    </div>

                                    {{--                                    <div class="row mt-4">--}}
                                    {{--                                        <div class="col-md-4">--}}
                                    {{--                                            <label><b>Charges From</b></label>--}}
                                    {{--                                            <input type="date" name="charge_from" class="form-control">--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col-md-4">--}}
                                    {{--                                            <label><b>Charges To</b></label>--}}
                                    {{--                                            <input type="date" name="charge_to" class="form-control">--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <div class="col-md-4">--}}
                                    {{--                                            <label><b>Ledger Date</b></label>--}}
                                    {{--                                            <input type="date" name="ledger_date" class="form-control">--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}

                                    {{--                                    <div class="row mt-4">--}}
                                    {{--                                        <div class="col-md-12">--}}
                                    {{--                                            <label for="classes"><b>Class: *</b></label>--}}
                                    {{--                                            <select required name="class_id[]" multiple--}}
                                    {{--                                                    class="form-select select2 basic-single mt-3 class_select"--}}
                                    {{--                                                    aria-label=".form-select-lg example">--}}

                                    {{--                                            </select>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}

                                    {{--                                    <div class="row mt-4">--}}
                                    {{--                                        <div class="col-md-12">--}}
                                    {{--                                            <label for="message1"><b>Message *</b></label>--}}
                                    {{--                                            <textarea id="message1" name="message" class="form-control"--}}
                                    {{--                                                      rows="3"></textarea>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}


                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="panel-body pad table-responsive">

                                <div id="loadData"></div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"
                                        style="margin-bottom: 10px;margin-left: 10px;">Save
                                </button>
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
