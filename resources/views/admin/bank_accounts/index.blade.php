@extends('admin.layouts.main')

@section('title', 'Bank Accounts')

@section('content')
<div class="container-fluid mt-4">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Bank Accounts</h4>
            <a href="{{ route('admin.bank_accounts.create') }}" class="btn btn-primary btn-sm">+ Add New Account</a>
        </div>

        <div class="card-body">

            {{-- Bank Accounts Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bank</th>
                            <th>Branch</th>
                            <th>Account No</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @dd($bankAccounts) --}}
                        @forelse($bankAccounts as $account)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $account->bank->name ?? 'N/A' }}</td>
                                <td>{{ $account->branches->branch_name ?? 'N/A' }}</td>
                                <td>{{ $account->account_no }}</td>
                                <td>{{ $account->type }}</td>
                                <td>
                                    <a href="{{ route('admin.bank_accounts.edit', $account->id) }}" class="btn btn-sm btn-info">Edit</a>

                                    <form action="{{ route('admin.bank_accounts.destroy', $account->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this account?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No bank accounts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
