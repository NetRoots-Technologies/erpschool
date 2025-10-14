@extends('admin.layouts.main')

@section('title', 'Create Invoice')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create New Invoice</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.receivables.invoices.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('accounts.receivables.invoices.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Invoice Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Customer <span class="text-danger">*</span></label>
                                <select name="customer_id" class="form-select" required>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->code }} - {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Reference</label>
                                <input type="text" name="reference" class="form-control" value="{{ old('reference') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Due Date <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Subtotal <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="subtotal" id="subtotal" class="form-control" value="{{ old('subtotal', 0) }}" required oninput="calculateTotal()" onchange="calculateTotal()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tax Amount</label>
                                <input type="number" step="0.01" name="tax_amount" id="tax_amount" class="form-control" value="{{ old('tax_amount', 0) }}" oninput="calculateTotal()" onchange="calculateTotal()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Discount</label>
                                <input type="number" step="0.01" name="discount" id="discount" class="form-control" value="{{ old('discount', 0) }}" oninput="calculateTotal()" onchange="calculateTotal()">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" value="{{ old('total_amount', 0) }}" required readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Subtotal:</th>
                            <td class="text-end" id="display_subtotal">Rs. 0.00</td>
                        </tr>
                        <tr>
                            <th>Tax:</th>
                            <td class="text-end" id="display_tax">Rs. 0.00</td>
                        </tr>
                        <tr>
                            <th>Discount:</th>
                            <td class="text-end" id="display_discount">Rs. 0.00</td>
                        </tr>
                        <tr class="table-light">
                            <th>Total:</th>
                            <th class="text-end" id="display_total">Rs. 0.00</th>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <i class="fa fa-info-circle"></i> A journal entry will be automatically created when you save this invoice.
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-save"></i> Create Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('js')
<script>
function calculateTotal() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    
    const total = subtotal + tax - discount;
    
    document.getElementById('total_amount').value = total.toFixed(2);
    document.getElementById('display_subtotal').textContent = 'Rs. ' + subtotal.toFixed(2);
    document.getElementById('display_tax').textContent = 'Rs. ' + tax.toFixed(2);
    document.getElementById('display_discount').textContent = 'Rs. ' + discount.toFixed(2);
    document.getElementById('display_total').textContent = 'Rs. ' + total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
});
</script>
@endsection
