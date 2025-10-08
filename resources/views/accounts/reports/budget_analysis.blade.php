@extends('admin.layouts.main')

@section('title', 'Budget Analysis')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Budget vs Actual Analysis</h4>
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
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Generate</button>
                        </div>
                    </div>
                </form>

                <div class="text-center mb-4">
                    <h3>Budget vs Actual Report</h3>
                    <p>For the period {{ \Carbon\Carbon::parse(request('start_date', now()->startOfMonth()->format('Y-m-d')))->format('d M Y') }} to {{ \Carbon\Carbon::parse(request('end_date', now()->format('Y-m-d')))->format('d M Y') }}</p>
                </div>

                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <strong>Note:</strong> Budget feature is under development. This report will show budget vs actual comparison once budgets are configured.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Account</th>
                                <th class="text-end">Budget</th>
                                <th class="text-end">Actual</th>
                                <th class="text-end">Variance</th>
                                <th class="text-end">% Variance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Budget data not available. Please configure budgets in the system.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <h5>Quick Actions</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('accounts.reports.trial_balance') }}" class="btn btn-outline-primary w-100">
                                <i class="fa fa-balance-scale"></i> Trial Balance
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('accounts.reports.income_statement') }}" class="btn btn-outline-success w-100">
                                <i class="fa fa-line-chart"></i> Income Statement
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('accounts.reports.balance_sheet') }}" class="btn btn-outline-info w-100">
                                <i class="fa fa-file-text"></i> Balance Sheet
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('accounts.reports.cash_flow') }}" class="btn btn-outline-warning w-100">
                                <i class="fa fa-money"></i> Cash Flow
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
