@extends('admin.layouts.main')

@section('title', 'Family Profile Report')

@php
    use Carbon\Carbon;
    $fmt = fn($n) => number_format($n ?? 0, 2);
@endphp

@section('styles')
<style>
    .student-badge { font-size: 13px; font-weight: 600; }
    .card-header-custom { background-color: #f8f9fa; font-weight: 600; font-size: 14px; }
    .progress-bar-custom { background: linear-gradient(90deg,#0d6efd,#6610f2); }
    @media print {
        .d-print-none { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #000 !important; }
        a.btn { display: none !important; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-3">

    <div class="card shadow-sm">
        <div class="card-body">

            {{-- Header: Family Info --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3 gap-3">
                <div>
                    <h4 class="mb-1">{{ $student->fullname ?? $student->first_name ?? 'Student' }}</h4>
                    <div class="mb-1">
                        Father CNIC / Family: 
                        <span class="badge bg-warning text-dark student-badge">
                            {{ $familyData[0]->father_cnic ?? 'N/A' }}
                        </span>
                    </div>
                    <div>
                        {{-- Student Table ID: 
                        <span class="badge bg-primary text-white student-badge">
                            {{ $student->student_id ?? $student->id }}
                        </span> --}}
                    </div>
                </div>

                <div class="text-md-end d-flex flex-column gap-2">
                    <button class="btn btn-outline-secondary btn-sm d-print-none" onclick="window.print()">
                        Print Report
                    </button>
                    <div class="text-muted small">
                        <div><strong>Total Billed:</strong> Rs {{ $fmt($familyTotals['billed'] ?? 0) }}</div>
                        <div><strong>Total Paid:</strong> Rs {{ $fmt($familyTotals['paid'] ?? 0) }}</div>
                        <div><strong>Remaining:</strong> <span class="text-danger">Rs {{ $fmt($familyTotals['remaining'] ?? 0) }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Students Summary --}}
            <div class="mb-3">
                <small class="text-muted">Students in Family</small>
                <div class="d-flex flex-wrap gap-2 mt-1">
                    @foreach($familyData as $fs)
                        <span class="badge bg-info text-white student-badge">{{ $fs->first_name }} ({{ $fs->student_id }})</span>
                    @endforeach
                </div>
            </div>

            {{-- Billed/Paid Progress --}}
            @php
                $billed = $familyTotals['billed'] ?? 0;
                $paid = $familyTotals['paid'] ?? 0;
                $paidPct = $billed > 0 ? round(($paid/$billed)*100,1) : 0;
            @endphp
            <div class="mb-3">
                <div class="small text-muted mb-1">Total Billed vs Paid</div>
                <div class="d-flex justify-content-between mb-1">
                    <small>Billed: Rs {{ $fmt($billed) }}</small>
                    <small>Paid: Rs {{ $fmt($paid) }}</small>
                </div>
                <div class="progress" style="height:12px;">
                    <div class="progress-bar progress-bar-custom" role="progressbar" style="width: {{ min(100,$paidPct) }}%" aria-valuenow="{{ $paidPct }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $paidPct }}%
                    </div>
                </div>
            </div>

            {{-- Student Bills --}}
            @foreach($familyData as $f)
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">{{ $f->first_name }} — Rs {{ $fmt($f->billed_total) }} billed · Rs {{ $fmt($f->paid_total) }} paid · <span class="text-danger">Rs {{ $fmt($f->remaining) }}</span> remaining</h6>
                    <small class="text-muted">Joined: {{ $f->admission_date ? Carbon::parse($f->admission_date)->toDateString() : '-' }}</small>
                </div>

                @if(empty($f->bill_rows) || $f->bill_rows->isEmpty())
                    <div class="text-muted small mb-2">No bills found for this student.</div>
                @else
                <div class="table-responsive mb-2">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bill ID</th>
                                <th>Description</th>
                                <th class="text-end">Billed</th>
                                <th class="text-end">Paid</th>
                                <th>Last Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($f->bill_rows as $b)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $b->billing_id }}</span></td>
                                <td>{{ $b->description }}</td>
                                <td class="text-end">Rs {{ $fmt($b->billed_amount) }}</td>
                                <td class="text-end">Rs {{ $fmt($b->paid_amount) }}</td>
                                <td>{{ $b->last_payment ? Carbon::parse($b->last_payment)->toDateString() : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @endforeach

            {{-- Total Summary --}}
            <div class="d-flex justify-content-end gap-3 mt-3 pt-2 border-top">
                <div><strong>Total Billed:</strong> Rs {{ $fmt($familyTotals['billed'] ?? 0) }}</div>
                <div><strong>Total Paid:</strong> Rs {{ $fmt($familyTotals['paid'] ?? 0) }}</div>
                <div><strong>Remaining:</strong> <span class="text-danger">Rs {{ $fmt($familyTotals['remaining'] ?? 0) }}</span></div>
            </div>

        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && $.fn.dataTable) {
        $('#familyQuickTable').DataTable({
            pageLength: 10,
            order: [[1,'asc']],
        });
    }
});
</script>
@endsection
