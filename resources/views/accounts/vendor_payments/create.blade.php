@extends('admin.layouts.main')

@section('title', 'Create Vendor Payment')

@section('content')
    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Create Vendor Payment</h4>
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

                <form action="{{ route('accounts.payables.vendorPayments.store') }}" method="POST"
                    enctype="multipart/form-data" id="paymentForm">
                    @csrf

                    <!-- Payment Info -->
                    <h5 class="mb-3 text-primary"></i>Payment Info</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control form-control-lg"
                                value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Vendor</label>
                            <select name="vendor_id" id="vendorSelect" class="form-select form-select-lg select2" required>
                                <option value="" selected>-- Select Vendor --</option>
                                @foreach ($vendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Payment Mode</label>
                            <select name="payment_mode" id="payment_mode" class="form-select form-select-lg" required>
                                @foreach (['Cash', 'Cheque', 'Bank Transfer', 'Other'] as $mode)
                                    <option value="{{ $mode }}" {{ old('payment_mode') == $mode ? 'selected' : '' }}>
                                        {{ $mode }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    
                    

                    <!-- Invoice Info -->
                    <h5 class="mb-3 text-primary"></i>Invoice Details</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Invoice / GRN (optional)</label>
                            <select name="invoice_id" id="invoiceSelect" class="form-select form-select-lg">
                                <option value="">-- Select Invoice --</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Invoice Amount</label>
                            <input type="text" name="invoice_amount" id="invoice_amount"
                                class="form-control form-control-lg bg-light" readonly>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Pending Amount</label>
                            <input type="text" name="pending_amount" id="pending_amount"
                                class="form-control form-control-lg bg-light" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Payment Amount</label>
                            <input type="number" name="payment_amount" step="0.01" id="payment_amount"
                                class="form-control form-control-lg" value="{{ old('payment_amount') }}" required>
                        </div>
                    </div>

                    {{-- Tax Amount Percentage --}}
                   <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label>Tax (%)</label>
                        {{-- <input type="text" id="tax_percentage" name="tax_percentage" class="form-control form-control-lg" value="0"> --}}
                        <input type="number" id="tax_percentage" name="tax_percentage" class="form-control form-control-lg" value="0" step="0.01" min="0">
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
                    <div id="bankChequeSection" style="display:none;">
                        <h5 class="mb-3 text-primary">Bank / Cheque Info</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Bank / Cash Account</label>
                                <select name="account_id" id="account_id" class="form-select form-select-lg">
                                    <option value="">-- Select Bank --</option>

                                    <!-- Static Bank List -->
                                    <option value="1" {{ old('account_id') == 'MCB Bank' ? 'selected' : '' }}>MCB Bank
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Cheque No</label>
                                <input type="text" name="cheque_no" id="cheque_no" class="form-control form-control-lg"
                                    value="{{ old('cheque_no') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Cheque Date</label>
                                <input type="text" name="cheque_date" id="cheque_date" class="form-control form-control-lg"
                                    value="{{ old('cheque_date') }}" placeholder="dd/mm/yyyy">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Attachment</label>
                                <input type="file" name="attachment" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>

                    {{-- Withholding Tax Payable --}}
                    <h5 class="mb-3 text-primary"></i>Withholding Tax Payable</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <select name="wht_group_code" id="wht_group_code" class="form-select form-select-lg" required>
                                <option value="" selected>-- Select Withholding Tax Payable --</option>
                                <option value="040020050001">040020050001 - WHT Payable Supplies</option>
                                <option value="040020050002">040020050002 - WHT Payable Services</option>
                                <option value="040020050003">040020050003 - WHT Payable Rent</option>
                                <option value="040020050004">040020050004 - WHT Payable Salaries</option>
                                <option value="040020050005">040020050005 - WHT Payable Construction Contracts</option>
                                <option value="040020050006">040020050006 - With Holding Tax on Fee</option>
                                <option value="040020050007">040020050007 - SALES TAX - PRA</option>
                            </select>
                        </div>
                    </div>



                    <!-- Remarks / Prepared & Approved By -->
                    <h5 class="mb-3 text-primary"></i>Additional Info</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Narration / Remarks</label>
                            <textarea name="remarks" class="form-control form-control-lg">{{ old('remarks') }}</textarea>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Prepared By</label>
                            <input type="text" name="prepared_by" id="prepared_by"
                                class="form-control form-control-lg" value="{{ auth()->user()->name }}" readonly>
                            {{-- <select name="prepared_by" class="form-select form-select-lg"> --}}
                            {{-- <option value="">-- Select --</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" {{ old('prepared_by')==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach --}}
                            {{-- </select> --}}
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Approved By</label>
                            <select name="approved_by" class="form-select form-select-l">
                                <option value="">-- Select --</option>
                                @foreach ($users as $u)
                                    <option value="{{ $u->id }}" {{ old('approved_by') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-save me-1"></i>Save
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

           
            $('#vendorSelect').on('change', function() {
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

        // for hide and show cheque section
         document.addEventListener('DOMContentLoaded', function() {
        const paymentMode = document.getElementById('payment_mode');
        const bankChequeSection = document.getElementById('bankChequeSection');
        const chequeNo = document.getElementById('cheque_no');
        const chequeDate = document.getElementById('cheque_date');
        const accountSelect = document.getElementById('account_id');

        function toggleBankChequeSection() {
            const isCheque = paymentMode && paymentMode.value === 'Cheque';
            if (!bankChequeSection) return;

            if (isCheque) {
                bankChequeSection.style.display = ''; // show
                // make required when visible
                if (chequeNo) chequeNo.setAttribute('required', 'required');
                if (chequeDate) chequeDate.setAttribute('required', 'required');
                if (accountSelect) accountSelect.setAttribute('required', 'required');
            } else {
                bankChequeSection.style.display = 'none'; // hide
                // remove required when hidden
                if (chequeNo) chequeNo.removeAttribute('required');
                if (chequeDate) chequeDate.removeAttribute('required');
                if (accountSelect) accountSelect.removeAttribute('required');

                // clear values optionally (uncomment if you want)
                // if (chequeNo) chequeNo.value = '';
                // if (chequeDate) chequeDate.value = '';
                // if (accountSelect) accountSelect.value = '';
            }
        }

        // run on load (handles old() or preselected values)
        toggleBankChequeSection();

        // run on change
        if (paymentMode) {
            paymentMode.addEventListener('change', function() {
                toggleBankChequeSection();
            });
        }
    });
    </script>

@endsection
