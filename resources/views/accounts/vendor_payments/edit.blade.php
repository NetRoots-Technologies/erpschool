@extends('admin.layouts.main')

@section('title', 'Edit Vendor Payment')

@section('content')
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Vendor Payment</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('accounts.payables.vendorPayments.update', $vp->id) }}" method="POST"
                    enctype="multipart/form-data" id="paymentForm">
                    @csrf
                    @method('PUT')

                    <!-- Payment Info -->
                    <h5 class="mb-3 text-primary">Payment Info</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control form-control-lg"
                                value="{{ old('payment_date', \Carbon\Carbon::parse($vp->payment_date)->format('Y-m-d')) }}"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Vendor</label>
                            <select name="vendor_id" id="vendorSelect" class="form-select form-select-lg" required>
                                <option value="" selected>-- Select Vendor --</option>
                                @foreach ($vendors as $v)
                                    <option value="{{ $v->id }}"
                                        {{ (old('vendor_id', $vp->vendor_id) == $v->id) ? 'selected' : '' }}>
                                        {{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Payment Mode</label>
                            <select name="payment_mode" id="payment_mode" class="form-select form-select-lg" required>
                                @foreach (['Cash', 'Cheque', 'Bank Transfer', 'Other'] as $mode)
                                    <option value="{{ $mode }}"
                                        {{ old('payment_mode', $vp->payment_mode) == $mode ? 'selected' : '' }}>
                                        {{ $mode }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Invoice Info -->
                    <h5 class="mb-3 text-primary">Invoice Details</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Invoice / GRN (optional)</label>
                            <select name="invoice_id" id="invoiceSelect" class="form-select form-select-lg">
                                <option value="">-- Select Invoice --</option>

                                @if($vp->invoice)
                                    @php
                                        // Build JSON payload similar to your API so JS can parse it
                                        $invData = [
                                            'id' => $vp->invoice->id,
                                            'total_amount' => (float) ($vp->invoice_amount ?? $vp->invoice->total_amount ?? 0),
                                            'pending_amount' => (float) ($vp->pending_amount ?? $vp->invoice->pending_amount ?? 0),
                                            'delivery_status' => $vp->invoice->delivery_status ?? ($vp->invoice->status ?? 'Invoice'),
                                        ];
                                    @endphp
                                    <option value="{{ $vp->invoice->id }}" data-inv='@json($invData)' selected>
                                        {{ $invData['delivery_status'] }} | Pending: {{ number_format($invData['pending_amount'],2) }}
                                    </option>
                                @endif

                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Invoice Amount</label>
                            <input type="text" name="invoice_amount" id="invoice_amount"
                                class="form-control form-control-lg bg-light" readonly
                                value="{{ old('invoice_amount', number_format($vp->invoice_amount ?? ($vp->invoice->total_amount ?? 0), 2, '.', '')) }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Pending Amount</label>
                            <input type="text" name="pending_amount" id="pending_amount"
                                class="form-control form-control-lg bg-light" readonly
                                value="{{ old('pending_amount', number_format($vp->pending_amount ?? ($vp->invoice->pending_amount ?? 0), 2, '.', '')) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Payment Amount</label>
                            <input type="number" name="payment_amount" step="0.01" id="payment_amount"
                                class="form-control form-control-lg" value="{{ old('payment_amount', number_format($vp->payment_amount,2,'.','')) }}"
                                required>
                        </div>
                    </div>
                     {{-- Tax Amount Percentage --}}
                   <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label>Tax (%)</label>
                        <input type="number" id="tax_percentage" name="tax_percentage" class="form-control form-control-lg" value="{{ $vp->tax_amount }}">
                    </div>
                    <div class="col-md-4">
                        <label>Tax Amount</label>
                        <input type="text" id="tax_amount" name="tax_amount" class="form-control form-control-lg" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Final Amount</label>
                        <input type="text" id="final_payment" name="final_payment" class="form-control form-control-lg" readonly>
                    </div>
                </div>

                    <!-- Bank / Cheque Info -->
                    <h5 class="mb-3 text-primary">Bank / Cheque Info</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Bank / Cash Account</label>
                            <select name="account_id" id="bankSelect" class="form-select form-select-lg">
                                {{-- <option value="">-- Select Bank --</option> --}}

                                {{-- show current selected account as first option --}}
                                {{-- @if($vp->account_id)
                                    @php
                                        $currentBank = \App\Models\AccountLedger::find($vp->account_id);
                                    @endphp
                                    @if($currentBank)
                                        <option value="{{ $currentBank->id }}" selected>{{ $currentBank->name }}</option>
                                    @endif
                                @endif --}}

                                <!-- Static option fallback -->
                                <option value="1" {{ $vp->account_id == 1 ? 'selected' : '' }}>MCB Bank</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Cheque No</label>
                            <input type="text" name="cheque_no" id="cheque_no" class="form-control form-control-lg"
                                value="{{ old('cheque_no', $vp->cheque_no) }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Cheque Date</label>
                            <input type="date" name="cheque_date" id="cheque_date" class="form-control form-control-lg"
                                value="{{ old('cheque_date', $vp->cheque_date ? \Carbon\Carbon::parse($vp->cheque_date)->format('Y-m-d') : '') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Attachment</label>
                            <input type="file" name="attachment" class="form-control form-control-lg">
                            @if($vp->attachment)
                                <small class="d-block mt-1"><a href="{{ asset('storage/'.$vp->attachment) }}" target="_blank">Current attachment</a></small>
                            @endif
                        </div>
                    </div>

                    <!-- Remarks / Prepared & Approved By -->
                    <h5 class="mb-3 text-primary">Additional Info</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Narration / Remarks</label>
                            <textarea name="remarks" class="form-control form-control-lg">{{ old('remarks', $vp->remarks) }}</textarea>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Prepared By</label>
                            <input type="text" name="prepared_by" id="prepared_by"
                                class="form-control form-control-lg" value="{{ $vp->preparedByUser->name ?? auth()->user()->name }}" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Approved By</label>
                            <select name="approved_by" class="form-select form-select-lg">
                                <option value="">-- Select --</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" {{ (old('approved_by', $vp->approved_by) == $u->id) ? 'selected' : '' }}>
                                        {{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-save me-1"></i>Update
                            Payment</button>
                        <a href="{{ route('accounts.payables.vendorPayments.index') }}"
                            class="btn btn-secondary btn-lg"><i class="bi bi-x-circle me-1"></i>Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const vendorSelect = document.getElementById('vendorSelect');
            const invoiceSelect = document.getElementById('invoiceSelect');
            const invoiceAmount = document.getElementById('invoice_amount');
            const pendingAmount = document.getElementById('pending_amount');
            const paymentAmount = document.getElementById('payment_amount');
            const paymentMode = document.getElementById('payment_mode');
            const chequeNo = document.getElementById('cheque_no');
            const chequeDate = document.getElementById('cheque_date');

            vendorSelect?.addEventListener('change', function() {
                const vid = this.value;
                invoiceSelect.innerHTML = '<option>Loading...</option>';
                invoiceAmount.value = '';
                pendingAmount.value = '';
                paymentAmount.value = '';

                if (!vid) {
                    invoiceSelect.innerHTML = '<option value="">-- Select Invoice --</option>';
                    return;
                }

                fetch(`/accounts/payables/vendor-payments/${vid}/pending-invoices`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '<option value="">-- Select Invoice --</option>';
                        data.forEach(inv => {
                             const invJson = JSON.stringify(inv).replace(/'/g, "&apos;"); // safe single-quote escape
                                html += `<option value="${inv.id}" data-inv='${invJson}'>${inv.delivery_status} | Pending: ${parseFloat(inv.pending_amount).toFixed(2)}</option>`;
                            });
                        invoiceSelect.innerHTML = html;
                    })
                    .catch(err => invoiceSelect.innerHTML = '<option value="">Failed to load</option>');
            });

            invoiceSelect?.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                if (!opt || !opt.dataset.inv) {
                    invoiceAmount.value = '';
                    pendingAmount.value = '';
                    return;
                }
                const inv = JSON.parse(opt.dataset.inv);
                invoiceAmount.value = parseFloat(inv.total_amount).toFixed(2);
                pendingAmount.value = parseFloat(inv.pending_amount).toFixed(2);
                if (!paymentAmount.value || parseFloat(paymentAmount.value) <= 0) {
                    paymentAmount.value = parseFloat(inv.pending_amount).toFixed(2);
                }
            });

            paymentMode?.addEventListener('change', function() {
                if (this.value === 'Cheque') {
                    chequeNo.required = true;
                    chequeDate.required = true;
                } else {
                    chequeNo.required = false;
                    chequeDate.required = false;
                }
            });
        });
    </script>
@endsection


@section('js')
    <script>
        $("#payment_amount").on("keyup change", function() {
            let invoice = parseFloat($("#invoice_amount").val()) || 0;
            let payment = parseFloat($(this).val()) || 0;

            if (payment > invoice) {
                payment = invoice;
                $(this).val(invoice.toFixed(2));
            }

            $("#pending_amount").val((invoice - payment).toFixed(2));
        });


       $("#payment_amount, #tax_percentage").on("keyup change", function() {
    let payment = parseFloat($("#payment_amount").val()) || 0;
    let taxPerc = parseFloat($("#tax_percentage").val()) || 0;

    let taxAmount = (payment * taxPerc) / 100;
    let finalAmount = payment - taxAmount;

    $("#tax_amount").val(taxAmount.toFixed(2));
    $("#final_payment").val(finalAmount.toFixed(2));
});
    </script>



@endsection
