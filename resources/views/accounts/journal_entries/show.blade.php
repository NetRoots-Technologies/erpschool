@extends('admin.layouts.main')

@section('title', 'Journal Entry Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Journal Entry: {{ $entry->entry_number }}</h4>
            <div class="page-title-right">
                @if($entry->status == 'draft')
                <a href="{{ route('accounts.journal.edit', $entry->id) }}" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>
                @endif
                <a href="{{ route('accounts.journal.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Entry Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 200px">Entry Number:</th>
                        <td>{{ $entry->entry_number }}</td>
                    </tr>
                    <tr>
                        <th>Entry Date:</th>
                        <td>{{ $entry->entry_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Entry Type:</th>
                        <td><span class="badge bg-info">{{ ucfirst($entry->entry_type) }}</span></td>
                    </tr>
                    <tr>
                        <th>Reference:</th>
                        <td>{{ $entry->reference ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $entry->description }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($entry->status == 'posted')
                                <span class="badge bg-success">Posted</span>
                            @elseif($entry->status == 'draft')
                                <span class="badge bg-warning">Draft</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($entry->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @if($entry->posted_at)
                    <tr>
                        <th>Posted At:</th>
                        <td>{{ $entry->posted_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($entry->source_module)
                    <tr>
                        <th>Source:</th>
                        <td><span class="badge bg-secondary">{{ ucfirst($entry->source_module) }}</span></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Journal Lines</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Account</th>
                                <th>Description</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entry->lines as $line)
                            <tr>
                                <td>
                                    <strong>{{ $line->accountLedger->code }}</strong><br>
                                    <small class="text-muted">{{ $line->accountLedger->name }}</small>
                                </td>
                                <td>{{ $line->description }}</td>
                                <td class="text-end">
                                    @if($line->debit > 0)
                                        <strong>Rs. {{ number_format($line->debit, 2) }}</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($line->credit > 0)
                                        <strong>Rs. {{ number_format($line->credit, 2) }}</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end">Total:</th>
                                <th class="text-end">Rs. {{ number_format($entry->total_debit, 2) }}</th>
                                <th class="text-end">Rs. {{ number_format($entry->total_credit, 2) }}</th>
                            </tr>
                            @if(!$entry->isBalanced())
                            <tr class="table-danger">
                                <th colspan="4" class="text-center">
                                    <i class="fa fa-exclamation-triangle"></i> Entry is not balanced!
                                </th>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Actions</h5>
            </div>
            <div class="card-body">
                @if($entry->status == 'draft')
                <form action="{{ route('accounts.journal.approve', $entry->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to post this entry? It cannot be edited after posting.')">
                    @csrf
                    <button type="submit" class="btn btn-success w-100 mb-2">
                        <i class="fa fa-check"></i> Post Entry
                    </button>
                </form>
                
                <a href="{{ route('accounts.journal.edit', $entry->id) }}" class="btn btn-primary w-100 mb-2">
                    <i class="fa fa-edit"></i> Edit Entry
                </a>
                
                <form action="{{ route('accounts.journal.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this entry?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fa fa-trash"></i> Delete Entry
                    </button>
                </form>
                @else
                <div class="alert alert-info mb-0">
                    <i class="fa fa-info-circle"></i> This entry has been posted and cannot be modified.
                </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Audit Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Created:</th>
                        <td class="text-end">{{ $entry->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Updated:</th>
                        <td class="text-end">{{ $entry->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                    @if($entry->posted_at)
                    <tr>
                        <th>Posted:</th>
                        <td class="text-end">{{ $entry->posted_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
