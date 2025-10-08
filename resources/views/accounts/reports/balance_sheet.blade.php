@extends('admin.layouts.main')

@section('title', 'Balance Sheet')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Balance Sheet</h4>
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
                            <label class="form-label">As of Date</label>
                            <input type="date" name="as_of_date" class="form-control" value="{{ $asOfDate }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Generate</button>
                        </div>
                    </div>
                </form>

                <div class="text-center mb-4">
                    <h3>Balance Sheet</h3>
                    <p>As of {{ \Carbon\Carbon::parse($asOfDate)->format('d M Y') }}</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Assets</h5>
                        <table class="table table-bordered">
                            <tbody>
                                @foreach($assets['details'] as $item)
                                <tr>
                                    <td>{{ $item['ledger']->name }}</td>
                                    <td class="text-end">{{ number_format($item['amount'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th>Total Assets</th>
                                    <th class="text-end">{{ number_format($assets['total'], 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Liabilities & Equity</h5>
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="table-secondary">
                                    <td colspan="2"><strong>Liabilities</strong></td>
                                </tr>
                                @foreach($liabilities['details'] as $item)
                                <tr>
                                    <td>{{ $item['ledger']->name }}</td>
                                    <td class="text-end">{{ number_format($item['amount'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-light">
                                    <th>Total Liabilities</th>
                                    <th class="text-end">{{ number_format($liabilities['total'], 2) }}</th>
                                </tr>
                                <tr class="table-secondary">
                                    <td colspan="2"><strong>Equity</strong></td>
                                </tr>
                                @foreach($equity['details'] as $item)
                                <tr>
                                    <td>{{ $item['ledger']->name }}</td>
                                    <td class="text-end">{{ number_format($item['amount'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-light">
                                    <th>Total Equity</th>
                                    <th class="text-end">{{ number_format($equity['total'], 2) }}</th>
                                </tr>
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <th>Total Liabilities & Equity</th>
                                    <th class="text-end">{{ number_format($liabilities['total'] + $equity['total'], 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
