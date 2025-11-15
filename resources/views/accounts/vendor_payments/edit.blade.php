@extends('admin.layouts.main')

@section('title', 'Edit Vendor Payment')

@section('content')
<div class="container">
    <h2>Edit Vendor Payment - {{ $vp->voucher_no }}</h2>

    @if ($errors->any())
      <div class="alert alert-danger">
          <ul class="mb-0">@foreach ($errors->all() as $err) <li>{{ $err }}</li> @endforeach</ul>
      </div>
    @endif

    <form action="{{ route('accounts.payables.vendorPayments.update', $vp->id) }}" method="POST" enctype="multipart/form-data" id="editPaymentForm">
      @csrf
      @method('PUT')

      <div class="row g-2">
        <div class="col-md-3">
          <label class="form-label">Payment Date</label>
          <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $vp->payment_date) }}" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Vendor</label>
          <select name="vendor_id" id="vendorSelect" class="form-select" required>
            <option value="">-- Select Vendor --</option>
            @foreach($vendors as $v)
              <option value="{{ $v->id }}" {{ (old('vendor_id', $vp->vendor_id) == $v->id) ? 'selected' : '' }}>{{ $v->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="col-md-5">
          <label class="form-label">Payment Mode</label>
          <select name="payment_mode" id="payment_mode" class="form-select" required>
            <option value="Cash" {{ old('payment_mode', $vp->payment_mode)=='Cash'?'selected':'' }}>Cash</option>
            <option value="Cheque" {{ old('payment_mode', $vp->payment_mode)=='Cheque'?'selected':'' }}>Cheque</option>
            <option value="Bank Transfer" {{ old('payment_mode', $vp->payment_mode)=='Bank Transfer'?'selected':'' }}>Bank Transfer</option>
            <option value="Other" {{ old('payment_mode', $vp->payment_mode)=='Other'?'selected':'' }}>Other</option>
          </select>
        </div>
      </div>

      {{-- <div class="row g-2 mt-2">
        <div class="col-md-4">
          <label class="form-label">Invoice / GRN (optional)</label>
          <select name="invoice_id" id="invoiceSelect" class="form-select">
            <option value="">-- Select Invoice (or leave blank) --</option>
            @if($vp->invoice)
              <option value="{{ $vp->invoice->id }}" selected data-inv='@json([
                  "id"=>$vp->invoice->id,
                  "invoice_no"=>$vp->invoice->invoice_no,
                  "total_amount"=>$vp->invoice->total_amount,
                  "pending_amount"=>($vp->invoice->total_amount - $vp->invoice->paid_amount)
              ])'>
              
              {{ $vp->invoice->invoice_no }} | Pending: {{ number_format($vp->invoice->total_amount - $vp->invoice->paid_amount,2) }}</option>
            @endif
          </select>
        </div>

        <div class="col-md-2">
          <label class="form-label">Invoice Amount</label>
          <input type="text" id="invoice_amount" class="form-control" readonly value="{{ old('invoice_amount', $vp->invoice_amount) }}">
          <input type="hidden" name="invoice_amount" id="invoice_amount_hidden" value="{{ $vp->invoice_amount }}">
        </div>

        <div class="col-md-2">
          <label class="form-label">Pending Amount</label>
          <input type="text" id="pending_amount" class="form-control" readonly value="{{ old('pending_amount', $vp->pending_amount) }}">
          <input type="hidden" name="pending_amount" id="pending_amount_hidden" value="{{ $vp->pending_amount }}">
        </div>

        <div class="col-md-4">
          <label class="form-label">Payment Amount</label>
          <input type="number" name="payment_amount" step="0.01" id="payment_amount" class="form-control" value="{{ old('payment_amount', $vp->payment_amount) }}" required>
        </div>
      </div> --}}

      <div class="row g-2 mt-2">
        <div class="col-md-4">
          <label class="form-label">Bank / Cash Account</label>
          <select name="account_id" class="form-select">
            <option value="">-- Select Account--</option>
            
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Cheque No</label>
          <input type="text" name="cheque_no" id="cheque_no" class="form-control" value="{{ old('cheque_no', $vp->cheque_no) }}">
        </div>

        <div class="col-md-3">
          <label class="form-label">Cheque Date</label>
          <input type="date" name="cheque_date" id="cheque_date" class="form-control" value="{{ old('cheque_date', $vp->cheque_date ? \Carbon\Carbon::parse($vp->cheque_date)->format('Y-m-d') : null) }}">
        </div>

        <div class="col-md-2">
          <label class="form-label">Attachment</label>
          <input type="file" name="attachment" class="form-control">
          @if($vp->attachment)
            <small>Current: <a href="{{ asset('storage/'.$vp->attachment) }}" target="_blank">View</a></small>
          @endif
        </div>
      </div>

      <div class="row g-2 mt-2">
        <div class="col-md-6">
          <label class="form-label">Narration / Remarks</label>
          <textarea name="remarks" class="form-control">{{ old('remarks', $vp->remarks) }}</textarea>
        </div>

        <div class="col-md-3">
          <label class="form-label">Prepared By</label>
          <select name="prepared_by" class="form-select">
            <option value="">-- Select --</option>
            @if(isset($users))
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ (old('prepared_by', $vp->prepared_by) == $u->id) ? 'selected' : '' }}>{{ $u->name }}</option>
              @endforeach
            @endif
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Approved By</label>
          <select name="approved_by" class="form-select">
            <option value="">-- Select --</option>
            @if(isset($users))
              @foreach($users as $u)
                <option value="{{ $u->id }}" {{ (old('approved_by', $vp->approved_by) == $u->id) ? 'selected' : '' }}>{{ $u->name }}</option>
              @endforeach
            @endif
          </select>
        </div>
      </div>

      <div class="mt-3">
        <button class="btn btn-success">Update Payment</button>
        <a href="{{ route('accounts.payables.vendorPayments.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  const vendorSelect = document.getElementById('vendorSelect');
  const invoiceSelect = document.getElementById('invoiceSelect');
  const invoiceAmount = document.getElementById('invoice_amount');
  const pendingAmount = document.getElementById('pending_amount');
  const invoiceAmountHidden = document.getElementById('invoice_amount_hidden');
  const pendingAmountHidden = document.getElementById('pending_amount_hidden');
  const paymentMode = document.getElementById('payment_mode');
  const chequeNo = document.getElementById('cheque_no');
  const chequeDate = document.getElementById('cheque_date');

  vendorSelect?.addEventListener('change', function(){
    const vid = this.value;
    invoiceSelect.innerHTML = '<option>Loading...</option>';
    invoiceAmount.value = '';
    pendingAmount.value = '';
    invoiceAmountHidden.value = '';
    pendingAmountHidden.value = '';

    if(!vid){
      invoiceSelect.innerHTML = '<option value="">-- Select Invoice (or leave blank) --</option>';
      return;
    }

    fetch(`/vendors/${vid}/pending-invoices`)
      .then(res => res.json())
      .then(data => {
        let html = '<option value="">-- Select Invoice (or leave blank) --</option>';
        data.forEach(inv => {
          html += `<option value="${inv.id}" data-inv='${JSON.stringify(inv)}'>${inv.invoice_no} | Pending: ${parseFloat(inv.pending_amount).toFixed(2)}</option>`;
        });
        invoiceSelect.innerHTML = html;
      })
      .catch(err => {
        invoiceSelect.innerHTML = '<option value="">Failed to load</option>';
      });
  });

  invoiceSelect?.addEventListener('change', function(){
    const opt = this.options[this.selectedIndex];
    if(!opt || !opt.dataset.inv){
      invoiceAmount.value = '';
      pendingAmount.value = '';
      invoiceAmountHidden.value = '';
      pendingAmountHidden.value = '';
      return;
    }
    const inv = JSON.parse(opt.dataset.inv);
    invoiceAmount.value = parseFloat(inv.total_amount).toFixed(2);
    pendingAmount.value = parseFloat(inv.pending_amount).toFixed(2);
    invoiceAmountHidden.value = inv.total_amount;
    pendingAmountHidden.value = inv.pending_amount;

    // If no payment amount yet, prefill payment_amount with pending
    const payInput = document.getElementById('payment_amount');
    if(!payInput.value || parseFloat(payInput.value) <= 0){
      payInput.value = parseFloat(inv.pending_amount).toFixed(2);
    }
  });

  paymentMode?.addEventListener('change', function(){
    if(this.value === 'Cheque'){
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
