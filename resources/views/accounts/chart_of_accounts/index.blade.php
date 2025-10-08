@extends('admin.layouts.main')

@section('title', 'Chart of Accounts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Chart of Accounts</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.coa.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Account
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ $errors->first() }}
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
                                <th>Code</th>
                                <th>Account Name</th>
                                <th>Group</th>
                                <th>Type</th>
                                <th>Current Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $group)
                                <tr class="table-secondary">
                                    <td colspan="7"><strong>{{ $group->name }} ({{ ucfirst($group->type) }})</strong></td>
                                </tr>
                                @foreach($group->ledgers as $ledger)
                                <tr>
                                    <td>{{ $ledger->code }}</td>
                                    <td>&nbsp;&nbsp;&nbsp;{{ $ledger->name }}</td>
                                    <td>{{ $group->name }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($group->type) }}</span></td>
                                    <td class="text-end">
                                        @if($ledger->current_balance_type == 'debit')
                                            <span class="text-success">Dr. {{ number_format($ledger->current_balance, 2) }}</span>
                                        @else
                                            <span class="text-danger">Cr. {{ number_format($ledger->current_balance, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ledger->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('accounts.coa.edit', $ledger->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if(!$ledger->is_system)
                                        <form action="{{ route('accounts.coa.destroy', $ledger->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No accounts found. Please create account groups and ledgers.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
