@extends('admin.layouts.main')

@section('title', 'Journal Entries')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Journal Entries</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.journal.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New Entry
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Entry #</th>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entries as $entry)
                            <tr>
                                <td><a href="{{ route('accounts.journal.show', $entry->id) }}">{{ $entry->entry_number }}</a></td>
                                <td>{{ $entry->entry_date->format('d M Y') }}</td>
                                <td>{{ $entry->reference }}</td>
                                <td>{{ $entry->description }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($entry->entry_type) }}</span></td>
                                <td class="text-end">Rs. {{ number_format($entry->total_debit, 2) }}</td>
                                <td>
                                    @if($entry->status == 'posted')
                                        <span class="badge bg-success">Posted</span>
                                    @elseif($entry->status == 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($entry->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('accounts.journal.show', $entry->id) }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if($entry->status == 'draft')
                                    <a href="{{ route('accounts.journal.edit', $entry->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('accounts.journal.approve', $entry->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Post this entry?')">
                                            <i class="fa fa-check"></i> Post
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No journal entries found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $entries->links('pagination::bootstrap-4') }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
