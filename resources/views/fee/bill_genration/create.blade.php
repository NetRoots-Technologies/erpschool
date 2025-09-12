@extends('admin.layouts.main')

@section('title')
Bill Generation
@stop

@section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        max-height: 100px;
        /* Adjust this value according to your design */
        overflow-y: auto;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create Bill Generation</h3>
                    <div class="row mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{!! route('admin.bill-generation.index') !!}" class="btn btn-primary btn-md">
                                Back </a>
                        </div>
                    </div>

                    <form action="{!! route('admin.bill-generation.store') !!}" enctype="multipart/form-data"
                        id="form_validation" autocomplete="off" method="post">
                        @csrf


                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:10px;">
                                <div class="row">

                                    <div class="col-md-4">
                                        <label for="Academic"><b>Academic Session *</b></label>
                                        <select name="session_id"
                                            class="form-control session_select  select2 basic-single" required
                                            id="session_id" required>
                                            <option value="" selected disabled>Select Session</option>
                                            @foreach($sessions as $key => $item)
                                            <option value="{!! $key !!}">{!! $item !!}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="branches"><b>Company *</b></label>
                                        <select name="company_id"
                                            class="form-control  select2 basic-single company_select" required
                                            id="companySelect">
                                            <option value="" selected disabled>Select Company</option>
                                            @foreach($companies as $item)
                                            <option value="{{$item->id}}">{{ $item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="branch_Style"><b>Branch*</b></label>
                                            </div>
                                            <select name="branch_id"
                                                class="form-control  select2 basic-single branch_select" required
                                                id="branch_id">
                                                <option value="" selected disabled>Select Branch</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-4">
                                        <label><b>Fee Year and Month *</b></label>
                                        <input type="date" name="year_month" class="form-control" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="classes"><b>Fee Factors *</b></label>
                                        <select name="fee_factor[]" multiple class="form-select select2 basic-single mt-3" required>
                                            <option value="" disabled hidden>Select Fee Factor</option>
                                            @foreach($feeFactors as $key => $item)
                                                <option value="{!! $item !!}">{!! $key !!}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mt-4" style="margin-top: 4px">
                                        <div class="form-check">
                                            <input type="hidden" name="arrears" value="0">
                                            <input class="form-check-input" name="arrears" type="checkbox" value="1"
                                                id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Arrears
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label><b>Bill Date</b></label>
                                        <input type="date" name="bill_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label><b>Due Date</b></label>
                                        <input type="date" name="due_date" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label><b>Valid Date</b></label>
                                        <input type="date" name="valid_date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label><b>Charges From</b></label>
                                        <input type="date" name="charge_from" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label><b>Charges To</b></label>
                                        <input type="date" name="charge_to" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label><b>Ledger Date</b></label>
                                        <input type="date" name="ledger_date" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="classes"><b>Class: *</b></label>
                                        <select required name="class_id[]" multiple
                                            class="form-select select2 basic-single mt-3 class_select"
                                            aria-label=".form-select-lg example">

                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <label for="message1"><b>Message *</b></label>
                                        <textarea id="message1" name="message" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>


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

        $('#form_validation').on('submit', function (e) {
            const selectedOptions = $('select[name="fee_factor[]"] option:selected');
            if (selectedOptions.length === 0) {
                toastr.error('Please select at least one fee factor.');
                e.preventDefault();
            }
        });

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

                        branchesDropdown.append('<option value="" selected disabled>Select Branch</option>');

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
                        classDropdown.append('<option value="" selected disabled>Select class</option>');

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
