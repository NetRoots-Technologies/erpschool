@extends('admin.layouts.main')

@section('title', 'General Ledger')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">General Ledger</h4>
            <div class="page-title-right">
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Select Account <span class="text-danger">*</span></label>
                            <select name="ledger_id" class="form-select" required>
                                <option value="">Select Account</option>
                                @foreach(\App\Models\Accounts\AccountLedger::where('is_active', true)->get() as $l)
                                    <option value="{{ $l->id }}" {{ request('ledger_id') == $l->id ? 'selected' : '' }}>
                                        {{ $l->code }} - {{ $l->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Generate</button>
                        </div>
                    </div>
                </form>

                @if(isset($ledger))
                <div class="text-center mb-4">
                    <h3>General Ledger</h3>
                    <h5>{{ $ledger->code }} - {{ $ledger->name }}</h5>
                    <p>For the period {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Entry #</th>
                                <th>Description</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Credit</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $runningBalance = $ledger->opening_balance; @endphp
                            <tr class="table-secondary">
                                <td colspan="5"><strong>Opening Balance</strong></td>
                                <td class="text-end"><strong>Rs. {{ number_format($runningBalance, 2) }}</strong></td>
                            </tr>
                            @forelse($transactions as $trans)
                                @php
                                    if ($ledger->current_balance_type == 'debit') {
                                        $runningBalance += $trans->debit - $trans->credit;
                                    } else {
                                        $runningBalance += $trans->credit - $trans->debit;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $trans->journalEntry->entry_date->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('accounts.journal.show', $trans->journalEntry->id) }}">
                                            {{ $trans->journalEntry->entry_number }}
                                        </a>
                                    </td>
                                    <td>{{ $trans->description ?: $trans->journalEntry->description }}</td>
                                    <td class="text-end">{{ $trans->debit > 0 ? number_format($trans->debit, 2) : '-' }}</td>
                                    <td class="text-end">{{ $trans->credit > 0 ? number_format($trans->credit, 2) : '-' }}</td>
                                    <td class="text-end">Rs. {{ number_format($runningBalance, 2) }}</td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No transactions found for this period</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">Closing Balance:</th>
                                <th class="text-end">Rs. {{ number_format($runningBalance, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Please select an account to view its general ledger.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
