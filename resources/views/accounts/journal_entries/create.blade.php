@extends('admin.layouts.main')

@section('title', 'Create Journal Entry')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create Journal Entry</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.journal.index') }}" class="btn btn-secondary">
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

<form action="{{ route('accounts.journal.store') }}" method="POST" id="journalForm">
    @csrf
    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Entry Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Entry Date <span class="text-danger">*</span></label>
                                <input type="date" name="entry_date" class="form-control" value="{{ old('entry_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Entry Type</label>
                                <select name="entry_type" class="form-select">
                                    <option value="journal">Journal</option>
                                    <option value="payment">Payment</option>
                                    <option value="receipt">Receipt</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Reference</label>
                                <input type="text" name="reference" class="form-control" value="{{ old('reference') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="2" required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Journal Lines</h5>
                    <button type="button" class="btn btn-sm btn-success" onclick="addLine()">
                        <i class="fa fa-plus"></i> Add Line
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="linesTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 35%">Account</th>
                                    <th style="width: 25%">Description</th>
                                    <th style="width: 15%">Debit</th>
                                    <th style="width: 15%">Credit</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="linesBody">
                                <tr class="line-row">
                                    <td>
                                        <select name="lines[0][account_ledger_id]" class="form-select form-select-sm" required>
                                            <option value="">Select Account</option>
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->id }}">{{ $ledger->code }} - {{ $ledger->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="lines[0][description]" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="lines[0][debit]" class="form-control form-control-sm debit-input" value="0" onchange="calculateTotals()">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="lines[0][credit]" class="form-control form-control-sm credit-input" value="0" onchange="calculateTotals()">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeLine(this)" disabled>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="line-row">
                                    <td>
                                        <select name="lines[1][account_ledger_id]" class="form-select form-select-sm" required>
                                            <option value="">Select Account</option>
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->id }}">{{ $ledger->code }} - {{ $ledger->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="lines[1][description]" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="lines[1][debit]" class="form-control form-control-sm debit-input" value="0" onchange="calculateTotals()">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="lines[1][credit]" class="form-control form-control-sm credit-input" value="0" onchange="calculateTotals()">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeLine(this)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                    <td><strong id="totalDebit">0.00</strong></td>
                                    <td><strong id="totalCredit">0.00</strong></td>
                                    <td></td>
                                </tr>
                                <tr id="differenceRow" style="display: none;">
                                    <td colspan="2" class="text-end"><strong class="text-danger">Difference:</strong></td>
                                    <td colspan="3"><strong class="text-danger" id="difference">0.00</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input type="checkbox" name="auto_post" class="form-check-input" id="auto_post" value="1">
                        <label class="form-check-label" for="auto_post">
                            Post entry immediately (cannot be edited after posting)
                        </label>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Save Journal Entry
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
let lineIndex = 2;

function addLine() {
    const tbody = document.getElementById('linesBody');
    const newRow = document.createElement('tr');
    newRow.className = 'line-row';
    newRow.innerHTML = `
        <td>
            <select name="lines[${lineIndex}][account_ledger_id]" class="form-select form-select-sm" required>
                <option value="">Select Account</option>
                @foreach($ledgers as $ledger)
                    <option value="{{ $ledger->id }}">{{ $ledger->code }} - {{ $ledger->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" name="lines[${lineIndex}][description]" class="form-control form-control-sm">
        </td>
        <td>
            <input type="number" step="0.01" name="lines[${lineIndex}][debit]" class="form-control form-control-sm debit-input" value="0" onchange="calculateTotals()">
        </td>
        <td>
            <input type="number" step="0.01" name="lines[${lineIndex}][credit]" class="form-control form-control-sm credit-input" value="0" onchange="calculateTotals()">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeLine(this)">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(newRow);
    lineIndex++;
}

function removeLine(button) {
    button.closest('tr').remove();
    calculateTotals();
}

function calculateTotals() {
    let totalDebit = 0;
    let totalCredit = 0;
    
    document.querySelectorAll('.debit-input').forEach(input => {
        totalDebit += parseFloat(input.value) || 0;
    });
    
    document.querySelectorAll('.credit-input').forEach(input => {
        totalCredit += parseFloat(input.value) || 0;
    });
    
    document.getElementById('totalDebit').textContent = totalDebit.toFixed(2);
    document.getElementById('totalCredit').textContent = totalCredit.toFixed(2);
    
    const difference = Math.abs(totalDebit - totalCredit);
    if (difference > 0.01) {
        document.getElementById('differenceRow').style.display = '';
        document.getElementById('difference').textContent = difference.toFixed(2);
    } else {
        document.getElementById('differenceRow').style.display = 'none';
    }
}

// Calculate totals on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotals();
});
</script>
@endsection
