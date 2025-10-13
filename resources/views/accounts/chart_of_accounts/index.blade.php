@extends('admin.layouts.main')

@section('title', 'Chart of Accounts')

@section('css')
<style>
    .group-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .group-header:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
    .group-header.collapsed {
        background: #6c757d;
    }
    
    .ledger-row {
        transition: all 0.3s ease;
    }
    .ledger-row:hover {
        background-color: #f8f9fa !important;
        transform: translateX(5px);
    }
    
    .balance-positive {
        color: #28a745;
        font-weight: bold;
    }
    .balance-negative {
        color: #dc3545;
        font-weight: bold;
    }
    
    .account-type-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
    }
    
    .collapsible-icon {
        transition: transform 0.3s ease;
    }
    .collapsed .collapsible-icon {
        transform: rotate(-90deg);
    }
    
    .empty-state {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 15px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">
                        <i class="fa fa-sitemap text-primary"></i> Chart of Accounts
                    </h3>
                    <p class="text-muted mb-0">Complete listing of all accounts organized by groups</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('accounts.groups.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-folder"></i> Manage Groups
                    </a>
                    <a href="{{ route('accounts.coa.tree') }}" class="btn btn-outline-info">
                        <i class="fa fa-project-diagram"></i> Tree View
                    </a>
                <a href="{{ route('accounts.coa.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus-circle"></i> Add Account
                </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>
                <strong>Error!</strong> {{ $errors->first() }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

        <!-- Summary Cards -->
        <div class="row mb-4">
            @php
                $totalAccounts = 0;
                $totalBalance = 0;
                $assetBalance = 0;
                $liabilityBalance = 0;
                
                foreach($groups as $group) {
                    foreach($group->ledgers as $ledger) {
                        $totalAccounts++;
                        $balance = $ledger->current_balance;
                        if($ledger->current_balance_type == 'credit' && in_array($group->type, ['liability', 'equity', 'revenue'])) {
                            $balance = $balance; // Credit is positive for these types
                        } elseif($ledger->current_balance_type == 'debit' && in_array($group->type, ['asset', 'expense'])) {
                            $balance = $balance; // Debit is positive for these types
                        } else {
                            $balance = -$balance; // Negative for opposite
                        }
                        
                        if(in_array($group->type, ['asset', 'expense'])) {
                            $assetBalance += $balance;
                        } else {
                            $liabilityBalance += $balance;
                        }
                        
                        $totalBalance += $balance;
                    }
                }
            @endphp
            
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Accounts</h6>
                                <h2 class="mb-0">{{ $totalAccounts }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa fa-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Asset Balance</h6>
                                <h2 class="mb-0">{{ number_format($assetBalance, 2) }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa fa-coins"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Liability Balance</h6>
                                <h2 class="mb-0">{{ number_format($liabilityBalance, 2) }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa fa-file-invoice-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-info text-white">
            <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Net Worth</h6>
                                <h2 class="mb-0">{{ number_format($assetBalance - $liabilityBalance, 2) }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa fa-balance-scale"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart of Accounts Table -->
        <div class="card shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fa fa-list"></i> Account Structure
                </h5>
            </div>
            <div class="card-body p-0">
                @forelse($groups as $group)
                    <div class="group-container">
                        <!-- Group Header (Collapsible) -->
                        <div class="group-header" onclick="toggleGroup('group-{{ $group->id }}')">
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-chevron-down collapsible-icon me-3"></i>
                                    <div>
                                        <h5 class="mb-0">
                                            <i class="fa fa-folder me-2"></i>{{ $group->name }}
                                        </h5>
                                        <small class="opacity-75">{{ $group->description ?? 'No description' }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-light text-dark">{{ $group->code }}</span>
                                    <span class="badge bg-{{ 
                                        $group->type == 'asset' ? 'success' : 
                                        ($group->type == 'liability' ? 'danger' : 
                                        ($group->type == 'equity' ? 'primary' : 
                                        ($group->type == 'revenue' ? 'info' : 'warning'))) 
                                    }} account-type-badge">
                                        {{ ucfirst($group->type) }}
                                    </span>
                                    <span class="badge bg-secondary">{{ $group->ledgers->count() }} accounts</span>
                                </div>
                            </div>
                        </div>

                        <!-- Group Content (Collapsible) -->
                        <div id="group-{{ $group->id }}" class="group-content">
                            @if($group->ledgers->count() > 0)
                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                                <th style="width: 15%">Code</th>
                                                <th style="width: 35%">Account Name</th>
                                                <th style="width: 15%">Type</th>
                                                <th style="width: 20%">Current Balance</th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($group->ledgers as $ledger)
                                                <tr class="ledger-row">
                                                    <td>
                                                        <strong class="text-primary">{{ $ledger->code }}</strong>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fa fa-chevron-right text-muted me-2"></i>
                                                            <div>
                                                                <strong>{{ $ledger->name }}</strong>
                                                                @if($ledger->description)
                                                                    <br><small class="text-muted">{{ $ledger->description }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ 
                                                            $group->type == 'asset' ? 'success' : 
                                                            ($group->type == 'liability' ? 'danger' : 
                                                            ($group->type == 'equity' ? 'primary' : 
                                                            ($group->type == 'revenue' ? 'info' : 'warning'))) 
                                                        }} account-type-badge">
                                                            {{ ucfirst($group->type) }}
                                                        </span>
                                                    </td>
                                    <td class="text-end">
                                        @if($ledger->current_balance_type == 'debit')
                                                            <span class="balance-positive">
                                                                Dr. {{ number_format($ledger->current_balance, 2) }}
                                                            </span>
                                        @else
                                                            <span class="balance-negative">
                                                                Cr. {{ number_format($ledger->current_balance, 2) }}
                                                            </span>
                                                        @endif
                                                        @if($ledger->opening_balance > 0)
                                                            <br><small class="text-muted">
                                                                Opening: {{ number_format($ledger->opening_balance, 2) }}
                                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ledger->is_active)
                                                            <span class="badge bg-success">
                                                                <i class="fa fa-check"></i> Active
                                                            </span>
                                        @else
                                                            <span class="badge bg-secondary">
                                                                <i class="fa fa-times"></i> Inactive
                                                            </span>
                                                        @endif
                                                        @if($ledger->is_system)
                                                            <br><small class="badge bg-warning">System</small>
                                        @endif
                                    </td>
                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('accounts.coa.edit', $ledger->id) }}" 
                                                               class="btn btn-sm btn-outline-primary"
                                                               title="Edit Account">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if(!$ledger->is_system)
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-danger"
                                                                        onclick="deleteAccount({{ $ledger->id }}, '{{ $ledger->name }}')"
                                                                        title="Delete Account">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                                            @else
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-secondary"
                                                                        disabled
                                                                        title="System Account - Cannot Delete">
                                                                    <i class="fa fa-lock"></i>
                                                                </button>
                                        @endif
                                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fa fa-inbox fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-3">No accounts in this group yet</p>
                                    <a href="{{ route('accounts.coa.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus"></i> Add First Account
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state text-center py-5">
                        <i class="fa fa-sitemap fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">No Account Groups Found</h4>
                        <p class="text-muted mb-4">
                            You need to create account groups first, then add individual accounts.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('accounts.groups.create') }}" class="btn btn-primary">
                                <i class="fa fa-folder-plus"></i> Create Groups
                            </a>
                            <a href="{{ route('accounts.coa.create') }}" class="btn btn-outline-primary">
                                <i class="fa fa-plus-circle"></i> Add Account
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="mb-3"><i class="fa fa-lightbulb"></i> Quick Actions:</h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('accounts.coa.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Add New Account
                            </a>
                            <a href="{{ route('accounts.groups.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-folder"></i> Manage Groups
                            </a>
                            <a href="{{ route('accounts.coa.tree') }}" class="btn btn-sm btn-outline-info">
                                <i class="fa fa-project-diagram"></i> Tree View
                            </a>
                            <button class="btn btn-sm btn-outline-success" onclick="expandAll()">
                                <i class="fa fa-expand-arrows-alt"></i> Expand All
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="collapseAll()">
                                <i class="fa fa-compress-arrows-alt"></i> Collapse All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this account?</p>
                <div class="alert alert-warning">
                    <strong>Account:</strong> <span id="accountName"></span>
                </div>
                <p class="text-danger mb-0">
                    <i class="fa fa-exclamation-circle"></i> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash"></i> Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// Toggle group collapse/expand
function toggleGroup(groupId) {
    const groupContent = document.getElementById(groupId);
    const header = groupContent.previousElementSibling;
    const icon = header.querySelector('.collapsible-icon');
    
    if (groupContent.style.display === 'none') {
        groupContent.style.display = 'block';
        header.classList.remove('collapsed');
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
    } else {
        groupContent.style.display = 'none';
        header.classList.add('collapsed');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
    }
}

// Expand all groups
function expandAll() {
    document.querySelectorAll('.group-content').forEach(content => {
        content.style.display = 'block';
        const header = content.previousElementSibling;
        const icon = header.querySelector('.collapsible-icon');
        header.classList.remove('collapsed');
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
    });
}

// Collapse all groups
function collapseAll() {
    document.querySelectorAll('.group-content').forEach(content => {
        content.style.display = 'none';
        const header = content.previousElementSibling;
        const icon = header.querySelector('.collapsible-icon');
        header.classList.add('collapsed');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
    });
}

// Delete account confirmation
function deleteAccount(id, name) {
    document.getElementById('accountName').textContent = name;
    document.getElementById('deleteForm').action = `/accounts/chart-of-accounts/${id}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Auto-dismiss alerts
setTimeout(function() {
    $('.alert').fadeOut('slow');
}, 5000);

// Initialize - all groups expanded by default
document.addEventListener('DOMContentLoaded', function() {
    expandAll();
});
</script>
@endsection
