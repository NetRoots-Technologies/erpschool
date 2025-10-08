@extends('admin.layouts.main')

@section('title', 'Income Statement')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Income Statement (Profit & Loss)</h4>
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

                <div class="text-center mb-4">
                    <h3>Income Statement</h3>
                    <p>For the period {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="table-secondary">
                                <td colspan="2"><strong>Revenue</strong></td>
                            </tr>
                            @foreach($revenue['details'] as $item)
                            <tr>
                                <td>{{ $item['ledger']->name }}</td>
                                <td class="text-end">{{ number_format($item['amount'], 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-light">
                                <th>Total Revenue</th>
                                <th class="text-end">{{ number_format($revenue['total'], 2) }}</th>
                            </tr>

                            <tr class="table-secondary">
                                <td colspan="2"><strong>Expenses</strong></td>
                            </tr>
                            @foreach($expenses['details'] as $item)
                            <tr>
                                <td>{{ $item['ledger']->name }}</td>
                                <td class="text-end">{{ number_format($item['amount'], 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="table-light">
                                <th>Total Expenses</th>
                                <th class="text-end">{{ number_format($expenses['total'], 2) }}</th>
                            </tr>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th>Net Income (Loss)</th>
                                <th class="text-end {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($netIncome, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
