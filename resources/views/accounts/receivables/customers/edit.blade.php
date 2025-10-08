@extends('admin.layouts.main')

@section('title', 'Edit Customer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Customer</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.receivables.customers.index') }}" class="btn btn-secondary">
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

<form action="{{ route('accounts.receivables.customers.update', $customer->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Customer Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" value="{{ $customer->code }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control" value="{{ $customer->contact_person }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ $customer->address }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ $customer->city }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">State/Province</label>
                                <input type="text" name="state" class="form-control" value="{{ $customer->state }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ $customer->country }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Tax Number</label>
                                <input type="text" name="tax_number" class="form-control" value="{{ $customer->tax_number }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Payment Terms</label>
                                <select name="payment_terms" class="form-select">
                                    <option value="">Select Terms</option>
                                    <option value="Net 15" {{ $customer->payment_terms == 'Net 15' ? 'selected' : '' }}>Net 15</option>
                                    <option value="Net 30" {{ $customer->payment_terms == 'Net 30' ? 'selected' : '' }}>Net 30</option>
                                    <option value="Net 45" {{ $customer->payment_terms == 'Net 45' ? 'selected' : '' }}>Net 45</option>
                                    <option value="Net 60" {{ $customer->payment_terms == 'Net 60' ? 'selected' : '' }}>Net 60</option>
                                    <option value="Due on Receipt" {{ $customer->payment_terms == 'Due on Receipt' ? 'selected' : '' }}>Due on Receipt</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Credit Limit</label>
                                <input type="number" step="0.01" name="credit_limit" class="form-control" value="{{ $customer->credit_limit }}">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ $customer->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Statistics</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Total Invoices:</th>
                            <td class="text-end">{{ $customer->invoices->count() }}</td>
                        </tr>
                        <tr>
                            <th>Outstanding:</th>
                            <td class="text-end text-success">
                                <strong>Rs. {{ number_format($customer->total_outstanding, 2) }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Credit Limit:</th>
                            <td class="text-end">Rs. {{ number_format($customer->credit_limit, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Created:</th>
                            <td class="text-end">{{ $customer->created_at->format('d M Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa fa-save"></i> Update Customer
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
