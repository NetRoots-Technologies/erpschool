@extends('admin.layouts.main')

@section('title', 'Edit Journal Entry')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Journal Entry: {{ $entry->entry_number }}</h4>
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

<form action="{{ route('accounts.journal.update', $entry->id) }}" method="POST" id="journalForm">
    @csrf
    @method('PUT')
    
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
                                <input type="date" name="entry_date" class="form-control" value="{{ $entry->entry_date->format('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Entry Type</label>
                                <select name="entry_type" class="form-select">
                                    <option value="journal" {{ $entry->entry_type == 'journal' ? 'selected' : '' }}>Journal</option>
                                    <option value="payment" {{ $entry->entry_type == 'payment' ? 'selected' : '' }}>Payment</option>
                                    <option value="receipt" {{ $entry->entry_type == 'receipt' ? 'selected' : '' }}>Receipt</option>
                                    <option value="transfer" {{ $entry->entry_type == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Reference</label>
                                <input type="text" name="reference" class="form-control" value="{{ $entry->reference }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="2" required>{{ $entry->description }}</textarea>
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
                                @foreach($entry->lines as $index => $line)
                                <tr class="line-row">
                                    <td>
                                        <select name="lines[{{ $index }}][account_ledger_id]" class="form-select form-select-sm" required>
                                            <option value="">Select Account</option>
                                            @foreach($ledgers as $ledger)
                                                <option value="{{ $ledger->id }}" {{ $line->account_ledger_id == $ledger->id ? 'selected' : '' }}>
                                                    {{ $ledger->code }} - {{ $ledger->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="lines[{{ $index }}][description]" class="form-control form-control-sm" value="{{ $line->description }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="lines[{{ $index }}][debit]" class="form-control form-control-sm debit-input" value="{{ $line->debit }}" onchange="calculateTotals()">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="lines[{{ $index }}][credit]" class="form-control form-control-sm credit-input" value="{{ $line->credit }}" onchange="calculateTotals()">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeLine(this)" {{ $index < 2 ? 'disabled' : '' }}>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
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
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Journal Entry
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
let lineIndex = {{ count($entry->lines) }};

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

document.addEventListener('DOMContentLoaded', function() {
    calculateTotals();
});
</script>
@endsection
