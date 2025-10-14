@extends('admin.layouts.main')

@section('title', 'Cash Flow Statement')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Cash Flow Statement</h4>
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
                    <h3>Cash Flow Statement</h3>
                    <p>For the period {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="table-secondary">
                                <td colspan="2"><strong>Operating Activities</strong></td>
                            </tr>
                            @if(isset($operatingData['details']) && count($operatingData['details']) > 0)
                                @foreach($operatingData['details'] as $item)
                                    <tr>
                                        <td class="ps-4">{{ $item['label'] }}</td>
                                        <td class="text-end {{ $item['amount'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rs. {{ number_format(abs($item['amount']), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="ps-4 text-muted">No operating activities</td>
                                    <td class="text-end">Rs. 0.00</td>
                                </tr>
                            @endif
                            <tr class="table-light">
                                <th>Net Cash from Operating Activities</th>
                                <th class="text-end {{ $operating >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rs. {{ number_format($operating, 2) }}
                                </th>
                            </tr>

                            <tr class="table-secondary">
                                <td colspan="2"><strong>Investing Activities</strong></td>
                            </tr>
                            @if(isset($investingData['details']) && count($investingData['details']) > 0)
                                @foreach($investingData['details'] as $item)
                                    <tr>
                                        <td class="ps-4">{{ $item['label'] }}</td>
                                        <td class="text-end {{ $item['amount'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rs. {{ number_format(abs($item['amount']), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="ps-4 text-muted">No investing activities</td>
                                    <td class="text-end">Rs. 0.00</td>
                                </tr>
                            @endif
                            <tr class="table-light">
                                <th>Net Cash from Investing Activities</th>
                                <th class="text-end {{ $investing >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rs. {{ number_format($investing, 2) }}
                                </th>
                            </tr>

                            <tr class="table-secondary">
                                <td colspan="2"><strong>Financing Activities</strong></td>
                            </tr>
                            @if(isset($financingData['details']) && count($financingData['details']) > 0)
                                @foreach($financingData['details'] as $item)
                                    <tr>
                                        <td class="ps-4">{{ $item['label'] }}</td>
                                        <td class="text-end {{ $item['amount'] >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rs. {{ number_format(abs($item['amount']), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="ps-4 text-muted">No financing activities</td>
                                    <td class="text-end">Rs. 0.00</td>
                                </tr>
                            @endif
                            <tr class="table-light">
                                <th>Net Cash from Financing Activities</th>
                                <th class="text-end {{ $financing >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rs. {{ number_format($financing, 2) }}
                                </th>
                            </tr>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <th>Net Increase/(Decrease) in Cash</th>
                                <th class="text-end {{ $netCashFlow >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rs. {{ number_format($netCashFlow, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fa fa-info-circle"></i> <strong>Note:</strong> This is a simplified cash flow statement. For detailed analysis, please review individual transactions.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
