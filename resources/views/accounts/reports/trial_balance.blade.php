@extends('admin.layouts.main')

@section('title', 'Trial Balance')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Trial Balance</h4>
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
                    <h3>Trial Balance</h3>
                    <p>As of {{ \Carbon\Carbon::parse($asOfDate)->format('d M Y') }}</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Account Code</th>
                                <th>Account Name</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ledgers as $item)
                            <tr>
                                <td>{{ $item['ledger']->code }}</td>
                                <td>{{ $item['ledger']->name }}</td>
                                <td class="text-end">{{ $item['debit'] > 0 ? number_format($item['debit'], 2) : '-' }}</td>
                                <td class="text-end">{{ $item['credit'] > 0 ? number_format($item['credit'], 2) : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end">Total:</th>
                                <th class="text-end">{{ number_format($totalDebit, 2) }}</th>
                                <th class="text-end">{{ number_format($totalCredit, 2) }}</th>
                            </tr>
                            @if(abs($totalDebit - $totalCredit) > 0.01)
                            <tr class="table-danger">
                                <th colspan="4" class="text-center">
                                    <i class="fa fa-exclamation-triangle"></i> Trial Balance is not balanced! 
                                    Difference: {{ number_format(abs($totalDebit - $totalCredit), 2) }}
                                </th>
                            </tr>
                            @else
                            <tr class="table-success">
                                <th colspan="4" class="text-center">
                                    <i class="fa fa-check-circle"></i> Trial Balance is balanced
                                </th>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
