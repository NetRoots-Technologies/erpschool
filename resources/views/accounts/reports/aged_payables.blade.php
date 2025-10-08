@extends('admin.layouts.main')

@section('title', 'Aged Payables Report')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Aged Payables Report</h4>
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
                    <h3>Aged Payables Report</h3>
                    <p>As of {{ \Carbon\Carbon::parse($asOfDate)->format('d M Y') }}</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Bill #</th>
                                <th>Vendor</th>
                                <th>Bill Date</th>
                                <th>Due Date</th>
                                <th>Days Overdue</th>
                                <th>Aging Bucket</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentBucket = 0;
                                $currentTotal = 0;
                                $bucketTotals = [];
                            @endphp
                            @forelse($bills as $item)
                                @php
                                    $bucket = $item['aging_bucket'];
                                    if ($bucket != $currentBucket) {
                                        if ($currentBucket != 0) {
                                            $bucketTotals[$currentBucket] = $currentTotal;
                                            $currentTotal = 0;
                                        }
                                        $currentBucket = $bucket;
                                    }
                                    $currentTotal += $item['bill']->balance;
                                @endphp
                                <tr>
                                    <td>{{ $item['bill']->bill_number }}</td>
                                    <td>{{ $item['bill']->vendor->name }}</td>
                                    <td>{{ $item['bill']->bill_date->format('d M Y') }}</td>
                                    <td>{{ $item['bill']->due_date->format('d M Y') }}</td>
                                    <td>
                                        @if($item['days_overdue'] > 0)
                                            <span class="badge bg-danger">{{ $item['days_overdue'] }} days</span>
                                        @else
                                            <span class="badge bg-success">Current</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-info">{{ $item['aging_bucket'] }}</span></td>
                                    <td class="text-end">
                                        <strong class="text-danger">Rs. {{ number_format($item['bill']->balance, 2) }}</strong>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No outstanding bills found</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="6" class="text-end">Total Outstanding:</th>
                                <th class="text-end">Rs. {{ number_format(collect($bills)->sum(function($item) { return $item['bill']->balance; }), 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-4">
                    <h5>Summary by Aging Bucket</h5>
                    <div class="row">
                        @php
                            $buckets = ['Current' => 0, '1-30 days' => 0, '31-60 days' => 0, '61-90 days' => 0, '90+ days' => 0];
                            foreach($bills as $item) {
                                $buckets[$item['aging_bucket']] += $item['bill']->balance;
                            }
                        @endphp
                        @foreach($buckets as $bucket => $total)
                        <div class="col-md-2">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6>{{ $bucket }}</h6>
                                    <h5 class="text-danger">Rs. {{ number_format($total, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
