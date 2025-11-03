@extends('admin.layouts.main')

@section('title', 'Discount History')

@push('styles')
<style>
    pre {
        background: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        font-size: 13px;
    }

    table.table-sm th {
        width: 180px;
        background: #f1f1f1;
    }

    .table td, .table th {
        vertical-align: middle !important;
    }

    .created-record {
        color: green;
        font-weight: bold;
    }

    .no-data {
        color: #999;
        font-style: italic;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">
                        Discount History - {{ $discount->student->fullname ?? 'N/A' }}
                    </h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.fee-management.discounts') }}">Fee Discounts</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">History</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <a href="{{ route('admin.fee-management.discounts') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- History Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Change History</h3>
                </div>

                <div class="card-body">
                    @if($histories->isEmpty())
                        <div class="alert alert-info text-center">
                            No history found for this discount.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>#</th>
                                        <th>Updated By</th>
                                        <th>Old Data</th>
                                        <th>New Data</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    {{-- @dd($histories); --}}
                                    @foreach($histories as $index => $history)
                                        @php
                                            $oldData = collect($history->old_data)->except([
                                                "id","category_id","student_id" , "show_on_voucher",
                                                "company_id","branch_id","created_by","updated_by",
                                                "created_at","updated_at","deleted_at"
                                            ]);

                                            $newData = collect($history->new_data)->except([
                                                'id','student_id','category_id','show_on_voucher',
                                                'reason','created_by','updated_at','created_at'
                                            ]);
                                        @endphp

                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $history->updateUser->name ?? 'System' }}</td>

                                            {{-- OLD DATA --}}
                                            <td>
                                                @if($oldData->isNotEmpty())
                                                    <table class="table table-sm table-bordered mb-0">
                                                        @foreach($oldData as $key => $value)
                                                        
                                                            <tr>
                                                                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                                <td>
                                                                    @if(in_array($key, ['valid_from','valid_to']))
                                                                        {{ \Carbon\Carbon::parse($value)->format('d M Y') }}
                                                                    @else
                                                                        {{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @else
                                                    <span class="created-record">— Created Record —</span>
                                                @endif
                                            </td>

                                           
                                            {{-- NEW DATA --}}
                                            <td>
                                                @if($newData->isNotEmpty())
                                                    <table class="table table-sm table-bordered mb-0">
                                                        @foreach($newData as $key => $value)
                                                            <tr>
                                                                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                                <td>
                                                                    @if(in_array($key, ['valid_from','valid_to']))
                                                                        {{ \Carbon\Carbon::parse($value)->format('d M Y') }}
                                                                    @else
                                                                        {{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @else
                                                    <span class="no-data">No new data</span>
                                                @endif
                                            </td>

                                       {{-- UPDATED DATE --}}
                                   <td>{{ \Carbon\Carbon::parse($history->updated_at)->timezone('Asia/Karachi')->format('d M, Y h:i A') }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
