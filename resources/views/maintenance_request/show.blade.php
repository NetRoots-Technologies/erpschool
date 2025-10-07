@extends('admin.layouts.main')

@section('title', 'Maintenance Request — Details')

@section('content')
@php
    // Normalize approval (works if approvals is: hasOne, hasMany, array, or collection)
    $ap = $data->approvals ?? null;

    if ($ap instanceof \Illuminate\Support\Collection) {
        $ap = $ap->sortByDesc('id')->first();
    } elseif (is_array($ap)) {
        // if approvals is a single associative array, keep as-is
        // if it's a numeric array (history), take the last
        $ap = isset($ap[0]) ? end($ap) : $ap;
        $ap = (object) $ap;
    }

    // Build safe image paths (adjust folders to your actual storage)
    $buildingImg = !empty($data->buildings['image']) ? asset($data->buildings['image']) : null;
    $issueImg    = !empty($data->issue_attachment) ? asset('issue_attachment/'.$data->issue_attachment) : null;
    // If you store issue images elsewhere (e.g. public/uploads), update the line above accordingly.

    // Status badge helper
    function badge($val, $map) {
        $v = strtolower((string)$val);
        $conf = $map[$v] ?? $map['pending'];
        return '<span class="badge badge-'.$conf[0].'" style="background: '.$conf[1].';">'.$conf[2].'</span>';
    }

    // Maps
    $reqMap = [
        'pending'     => ['info',    '#00b8ff', 'Pending'],
        'in_progress' => ['warning', '#f59e0b', 'In Progress'],
        'completed'   => ['primary', '#0014ff', 'Completed'],
        'rejected'    => ['danger',  '#ff0000', 'Rejected'],
        'reject'      => ['danger',  '#ff0000', 'Rejected'],
        'approved'    => ['success', '#22c03c', 'Approved'],
        'approval'    => ['success', '#22c03c', 'Approved'],
    ];

    $doneMap = [
        'pending'     => ['info',    '#00b8ff', 'Pending'],
        'in_progress' => ['success', '#22c03c', 'In Progress'],
        'completed'   => ['primary', '#0014ff', 'Completed'],
        'rejected'    => ['danger',  '#ff0000', 'Rejected'],
        'reject'      => ['danger',  '#ff0000', 'Rejected'],
        'approved'    => ['warning', '#ffc800', 'Approved'],
        'approval'    => ['warning', '#ffc800', 'Approved'],
    ];

    $approvalMap = $doneMap;
@endphp

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Maintenance Request #{{ $data->id }}</h3>
            <a href="{{ route('maintenance-request.index') }}" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>

    {{-- Top summary --}}
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($buildingImg)
                            <img src="{{ $buildingImg }}" alt="Building" style="width:72px;height:72px;object-fit:cover;border-radius:12px;margin-right:16px;border:1px solid #eee;">
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $data->buildings['name'] ?? '-' }}</h5>
                            <div class="text-muted small">
                                Unit: <strong>{{ $data->units['name'] ?? '-' }}</strong> &bull;
                                Type: <strong>{{ $data->types['title'] ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="p-2 rounded border">
                                <div class="text-muted small">Requested By</div>
                                <div class="fw-600">{{ $data->users['name'] ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 rounded border">
                                <div class="text-muted small">Maintainer</div>
                                <div class="fw-600">{{ $data->maintainers['name'] ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-2 rounded border">
                                <div class="text-muted small">Request Date</div>
                                <div class="fw-600">{{ \Carbon\Carbon::parse($data->request_date)->format('d M Y') }}</div>
                            </div>
                        </div>

                        {{-- <div class="col-md-4">
                            <div class="p-2 rounded border">
                                <div class="text-muted small">Amount</div>
                                <div class="fw-600">{{ number_format((float)$data->amount, 2) }}</div>
                            </div>
                        </div> --}}
                    </div>

                    {{-- <div class="row g-2 mt-2">
                        
                        <div class="col-md-4">
                            <div class="p-2 rounded border">
                                <div class="text-muted small">Fixed Date</div>
                                <div class="fw-600">{{ $data->fixed_date ? \Carbon\Carbon::parse($data->fixed_date)->format('d M Y') : '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 rounded border">
                                <div class="text-muted small">Invoice</div>
                                <div class="fw-600">
                                    @if(!empty($data->invoice))
                                        <a href="{{ asset('storage/invoices/'.$data->invoice) }}" target="_blank">View</a>
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    @if(!empty($data->notes))
                        <div class="mt-3">
                            <div class="text-muted small mb-1">Notes</div>
                            <div class="p-2 rounded border bg-light">{{ $data->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Issue image --}}
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>Issue Attachment</strong>
                </div>
                <div class="card-body">
                    @if($issueImg)
                        <img src="{{ $issueImg }}" alt="Issue Attachment" style="max-width:50%;height:auto;border-radius:8px;border:1px solid #eee;">
                    @else
                        <span class="text-muted">No attachment uploaded.</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right side: statuses --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header"><strong>Status</strong></div>
                <div class="card-body">
                    <div class="mb-2">
                        <div class="text-muted small">Request Status</div>
                        {!! badge($data->status ?? 'pending', $reqMap) !!}
                    </div>
                    <div class="mb-2">
                        <div class="text-muted small">Approval Status</div>
                        {!! badge(optional($ap)->approval_status ?? 'pending', $approvalMap) !!}
                    </div>
                    <div>
                        <div class="text-muted small">Done Status</div>
                        {!! badge(optional($ap)->done_status ?? 'pending', $doneMap) !!}
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header"><strong>Timeline</strong></div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <div class="fw-600">Requested</div>
                            <div class="text-muted small">{{ \Carbon\Carbon::parse($data->created_at)->format('d M Y, h:i A') }}</div>
                        </li>
                        @if(optional($ap)->approval_date)
                        <li class="mb-2">
                            <div class="fw-600">Last Action</div>
                            <div class="text-muted small">{{ \Carbon\Carbon::parse($ap->approval_date)->format('d M Y, h:i A') }}</div>
                        </li>
                        @endif
                        <li>
                            <div class="fw-600">Updated</div>
                            <div class="text-muted small">{{ \Carbon\Carbon::parse($data->updated_at)->format('d M Y, h:i A') }}</div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

