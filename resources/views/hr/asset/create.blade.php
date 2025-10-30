@extends('admin.layouts.main')

@section('title')
Asset Create
@stop
@section('css')
<style>
    .row {
        padding-bottom: 5px;
        padding-top: 5px;
    }
</style>
@endsection
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create Asset
                    </h3>
                    <div class="row    mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{!! route('hr.asset.index') !!}" class="btn btn-primary btn-sm ">
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
                    <form action="{!! route('hr.asset.store') !!}" enctype="multipart/form-data" id="form_validation"
                        autocomplete="off" method="post">
                        @csrf

                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:10px;">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="credit_type"><b>Credit Type *</b></label>
                                        <select id="credit_type" name="credit_type" class="form-control" required>
                                            <option value="" selected disabled>select</option>
                                            <option value="0">Cash</option>
                                            <option value="1">Bank</option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-lg-6">
                                        <label for="credit_ledger"><b>Credit Ledger *</b></label>
                                        <select name="credit_ledger" id="credit_ledger" class="form-control" required>
                                            <option value="" disabled selected>Select A Ledger</option>
                                            @foreach ($ledgers as $ledger)
                                            <option value="{{$ledger->id}}" data-id="{{$ledger->code}}">
                                                {{$ledger->name}}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Asset Type *</b></label>
                                        <select name="asset_type_id" id="asset_type_id" class="form-control" required>
                                            <option value="" selected disabled>select</option>
                                            @foreach($asserts as $assert)
                                            <option value="{{ $assert->id }}"
                                                data-depreciation="{{$assert->depreciation}}">{{ $assert->name }} <span
                                                    class="muted">({{ $assert->depreciation }}%)</span>
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Name *</b></label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Company Asset Code</b></label>
                                        <input type="text" name="code" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="working"><b>Is Working? *</b></label><br>
                                        <input type="checkbox" name="working" id="working">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Company *</b></label>
                                        <select name="company_id" class="form-control" required>
                                            <option value="" selected disabled>select</option>
                                            @foreach($companies as $single)
                                            <option value="{{ $single->id }}">{{ $single->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Branch *</b></label>
                                        <select name="branch_id" class="form-control" required>
                                            <option value="" selected disabled>select</option>
                                            @foreach($branches as $single)
                                            <option value="{{ $single->id }}">{{ $single->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Purchase Date</b></label>
                                        <input type="date" id="purchase_date" name="purchase_date"
                                            max="{{date('Y-m-d')}}" class="form-control" required>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Invoice #</b></label>
                                        <input type="text" name="invoice_number" class="form-control">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Manufacturer</b></label>
                                        <input type="text" name="manufacturer" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Serial #</b></label>
                                        <input type="text" name="serial_number" class="form-control">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Warranty / AMC End Date</b></label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Asset Image</b></label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Amount *</b></label>
                                        <input type="number" id="amount" name="amount" class="form-control" required>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="depreciation"><b>Depreciation</b></label>
                                        <input type="number" id="depreciation" name="depreciation" class="form-control"
                                            readonly>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="depreciation_type"><b>Depreciation Type</b></label>
                                        <select name="depreciation_type" id="depreciation_type" class="form-control" required>
                                            <option value="no_depreciation" selected>
                                                No Depreciation</option>
                                            <option value="straight_line">
                                                Straight Line</option>
                                            <option value="declining_balance">
                                                Declining Balance</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Sales Tax</b></label>
                                        <input type="number" name="sale_tax" class="form-control">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Income Tax</b></label>
                                        <input type="number" name="income_tax" class="form-control">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Narration</b></label>
                                        <input type="text" name="narration" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="branch"><b>Asset Note</b></label>
                                        <textarea name="note" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
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

            $("#form_validation").validate();
            $('#credit_type').on('change', function () {
                var credit_type = $(this).val();
                var credit_ledger = $('#credit_ledger');
                credit_ledger.val("");
                if (credit_type == '0') {
                    credit_ledger.find('option').each(function () {
                        if ($(this).data('id') !== 'cash') {
                            $(this).prop('disabled', true);
                        }else {
                            $(this).prop('disabled', false);
                        }
                    });
                }else {
                    credit_ledger.find('option').each(function () {
                        if ($(this).data('id') == 'cash') {
                            $(this).prop('disabled', true);
                        }else {
                            $(this).prop('disabled', false);
                        }
                    });
                }

            });

            $('#reset').on('click', function () {
                $("#depreciation").val('');
            });

            $('#depreciation_type').on('change', function () {
                let method = $(this).val();

                var amount = parseFloat($('#amount').val());
                var depreciationRate = parseFloat($('#asset_type_id option:selected').data('depreciation'));
                var purchaseDate = $('#purchase_date').val();

                if (!depreciationRate) {
                    toastr.error('Please select an asset type');
                    return;
                }

                if (!amount || isNaN(amount) || amount <= 0) {
                    toastr.error('Please enter a valid amount');
                    return;
                }

                if (!purchaseDate) {
                    toastr.error('Please select a purchase date');
                    return;
                }

                var purchaseDateObj = new Date(purchaseDate);
                var currentDate = new Date();
                var yearsDifference = (currentDate - purchaseDateObj) / (1000 * 60 * 60 * 24 * 365);
                var depreciationValue;

                if (method === 'straight_line') {
                    var annualDepreciation = amount * (depreciationRate / 100);
                    depreciationValue = amount - (annualDepreciation * yearsDifference);
                } else if (method === 'declining_balance') {
                    var depreciationFactor = 1 - (depreciationRate / 100);
                    depreciationValue = amount * Math.pow(depreciationFactor, yearsDifference);
                }

                $("#depreciation").val(Math.max(0, depreciationValue).toFixed(3));
            })

            $('#straight_line').on('click', function () {
                calculateDepreciation('straight_line');
            });

            $('#declining_balance').on('click', function () {
                calculateDepreciation('declining_balance');
            });


            $('#no_depreciation').on('click', function(){
                $("#depreciation").val(0);
            })


        });
</script>

@endsection
