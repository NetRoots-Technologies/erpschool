@extends('admin.layouts.main')

@section('title')
Asset Edit
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
                    <h3 class="text-22 text-midnight text-bold mb-4"> Edit Asset</h3>
                    <div class="row mt-4 mb-4">
                        <div class="col-6 text-right">
                            <a href="{!! route('hr.asset.index') !!}" class="btn btn-primary btn-sm ">
                                Back </a>
                        </div>
                        <div class="col-6 text-right" style="margin-top: -40px">
                            <img style="width: 150px;float: right" src="{{ asset('asset_image/'.$asset->image) }}"
                                alt="">
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
                    <form action="{{ route('hr.asset.update',$asset->id) }}" enctype="multipart/form-data"
                        id="form_validation" autocomplete="off" method="post">
                        @csrf
                        @method('put')
                        <div class="w-100 p-3">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="credit_type"><b>Credit Type *</b></label>
                                        <select id="credit_type" name="credit_type" class="form-control" required>
                                            <option value="" selected disabled>select</option>
                                            <option @if($asset->credit_type == 0) selected @endif value="0">Cash
                                            </option>
                                            <option @if($asset->credit_type == 1) selected @endif value="1">Bank
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="credit_ledger"><b>Credit Ledger *</b></label>
                                        <select name="credit_ledger" id="credit_ledger" class="form-control"
                                            required></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Asset Type *</b></label>
                                        <select name="asset_type_id" id="asset_type_id" class="form-control" required>
                                            <option value="" selected disabled>select</option>
                                            @foreach($asset_type as $single)
                                            <option @if($asset->asset_type_id == $single->id) selected
                                                @endif value="{{ $single->id }}"
                                                data-depreciation="{{$single->depreciation}}">{{ $single->name }} <span
                                                class="muted">({{ $single->depreciation }}%)</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="col-lg-4">
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
                                    </div> --}}

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Name *</b></label>
                                        <input type="text" value="{{ $asset->name }}" name="name" class="form-control"
                                            required>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Company Asset Code</b></label>
                                        <input type="text" value="{{ $asset->code }}" name="code" class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Is Working? *</b></label><br>
                                        <input type="checkbox" @if($asset->working == 1) checked
                                        @endif name="working">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Company *</b></label>
                                        <select name="company_id" class="form-control" required>
                                            <option value="" selected disabled>select</option>
                                            @foreach($companies as $single)
                                            <option @if($asset->company_id == $single->id) selected
                                                @endif value="{{ $single->id }}">{{ $single->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Branch *</b></label>
                                        <select name="branch_id" class="form-control" required>
                                            <option value="" selected disabled>select</option>
                                            @foreach($branches as $single)
                                            <option @if($asset->branch_id == $single->id) selected
                                                @endif value="{{ $single->id }}">{{ $single->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Purchase Date</b></label>
                                        <input type="date" id="purchase_date" name="purchase_date" class="form-control"
                                            value="{{ $asset->purchase_date }}" required>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Invoice #</b></label>
                                        <input type="text" name="invoice_number" class="form-control"
                                            value="{{ $asset->invoice_number }}">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Manufacturer</b></label>
                                        <input type="text" name="manufacturer" class="form-control"
                                            value="{{ $asset->manufacturer }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Serial #</b></label>
                                        <input type="text" name="serial_number" class="form-control"
                                            value="{{ $asset->serial_number }}">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Warranty / AMC End Date</b></label>
                                        <input type="date" name="end_date" class="form-control"
                                            value="{{ $asset->end_date }}">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Asset Image</b></label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Amount *</b></label>
                                        <input type="number" id="amount" name="amount" class="form-control" required
                                            value="{{ $asset->amount }}">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="depreciation"><b>Depreciation</b></label>
                                        <input type="number" id="depreciation" name="depreciation" class="form-control"
                                            readonly value="{{ $asset->depreciation }}">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="depreciation_type"><b>Depreciation Type</b></label>
                                        <select name="depreciation_type" id="depreciation_type" class="form-control" required>
                                            <option value="no_depreciation" @if($asset->depreciation_type == 'no_depreciation') selected @endif>No Depreciation</option>
                                            <option value="straight_line" @if($asset->depreciation_type == 'straight_line') selected @endif>Straight Line</option>
                                            <option value="declining_balance" @if($asset->depreciation_type == 'declining_balance') selected @endif>Declining Balance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="branch"><b>Sales Tax</b></label>
                                        <input type="number" name="sale_tax" class="form-control"
                                            value="{{ $asset->sale_tax }}">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Income Tax</b></label>
                                        <input type="number" name="income_tax" class="form-control"
                                            value="{{ $asset->income_tax }}">
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="branch"><b>Narration</b></label>
                                        <input type="text" name="narration" class="form-control"
                                            value="{{ $asset->narration }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="branch"><b>Asset Note</b></label>
                                        <textarea name="note" class="form-control">{!! $asset->note !!}</textarea>
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
@stop
@section('js')
<script>
    $(document).ready(function () {
            $("#form_validation").validate();
            $('#credit_type').on('change', function () {
                var credit_type = $(this).val();
                var credit_ledger = $('#credit_ledger').empty();

                var selected_credit_ledger = "{{ $asset->credit_ledger ?? '' }}";

                credit_ledger.append('<option value="" disabled>Select</option>');

                if (credit_type == '0') {
                    credit_ledger.append('<option value="0"' + (selected_credit_ledger == "0" ? ' selected' : '') + '>Cash Ledger</option>');
                } else {
                    credit_ledger.append('<option value="1"' + (selected_credit_ledger == "1" ? ' selected' : '') + '>Bank Ledger</option>');
                }
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

            $('#reset').on('click', function () {
                $("#depreciation").val('');
            });

            $('#credit_type').trigger('change');
        });
</script>
@endsection
