@extends('admin.layouts.main')

@section('title', 'Chart of Accounts - Tree View')

@section('css')
<style>
    .tree-container {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
    }
    
    .tree-node {
        margin: 10px 0;
        position: relative;
    }
    
    .tree-node::before {
        content: '';
        position: absolute;
        left: -20px;
        top: 50%;
        width: 15px;
        height: 1px;
        background: #dee2e6;
    }
    
    .tree-node::after {
        content: '';
        position: absolute;
        left: -20px;
        top: 0;
        width: 1px;
        height: 100%;
        background: #dee2e6;
    }
    
    .tree-node:last-child::after {
        height: 50%;
    }
    
    .group-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .group-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .ledger-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-left: 20px;
    }
    
    .ledger-card:hover {
        border-color: #007bff;
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,123,255,0.1);
    }
    
    .balance-badge {
        font-size: 0.85rem;
        padding: 6px 12px;
        border-radius: 20px;
    }
    
    .expand-btn {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .expand-btn:hover {
        background: rgba(255,255,255,0.3);
        transform: scale(1.1);
    }
    
    .type-indicator {
        width: 4px;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        border-radius: 8px 0 0 8px;
    }
    
    .type-asset { background: #28a745; }
    .type-liability { background: #dc3545; }
    .type-equity { background: #007bff; }
    .type-revenue { background: #17a2b8; }
    .type-expense { background: #ffc107; }
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
                        <i class="fa fa-project-diagram text-primary"></i> Chart of Accounts - Tree View
                    </h3>
                    <p class="text-muted mb-0">Hierarchical view of your account structure</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('accounts.coa.index') }}" class="btn btn-outline-primary">
                    <i class="fa fa-list"></i> List View
                </a>
                    <a href="{{ route('accounts.groups.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-folder"></i> Manage Groups
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

        <!-- Tree Structure -->
        <div class="tree-container">
            @forelse($groups as $group)
                <div class="tree-node">
                    <!-- Group Card -->
                    <div class="card group-card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="type-indicator type-{{ $group->type }}"></div>
                                    <div class="ms-3">
                                        <h5 class="mb-1">
                                            <i class="fa fa-folder me-2"></i>{{ $group->name }}
                                        </h5>
                                        <small class="opacity-75">
                                            {{ $group->description ?? 'No description available' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-light text-dark">{{ $group->code }}</span>
                                    <span class="badge bg-{{ 
                                        $group->type == 'asset' ? 'success' : 
                                        ($group->type == 'liability' ? 'danger' : 
                                        ($group->type == 'equity' ? 'primary' : 
                                        ($group->type == 'revenue' ? 'info' : 'warning'))) 
                                    }}">
                                        {{ ucfirst($group->type) }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        {{ $group->ledgers->count() }} accounts
                                    </span>
                                    <button class="expand-btn" onclick="toggleGroup('group-{{ $group->id }}')">
                                        <i class="fa fa-chevron-down" id="icon-group-{{ $group->id }}"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ledgers in Group -->
                    <div id="group-{{ $group->id }}" class="ledgers-container">
                        @foreach($group->ledgers as $ledger)
                            <div class="ledger-card mb-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="type-indicator type-{{ $group->type }}"></div>
                                            <div class="ms-3">
                                                <div class="d-flex align-items-center">
                                                    <strong class="text-primary me-2">{{ $ledger->code }}</strong>
                                                    <h6 class="mb-0">{{ $ledger->name }}</h6>
                                                </div>
                                                @if($ledger->description)
                                                    <small class="text-muted">{{ $ledger->description }}</small>
                                                @endif
                                                @if($ledger->opening_balance > 0)
                                                    <br><small class="text-info">
                                                        Opening: {{ number_format($ledger->opening_balance, 2) }} 
                                                        ({{ ucfirst($ledger->opening_balance_type) }})
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-end">
                                                <div class="balance-badge bg-{{ 
                                                    $ledger->current_balance_type == 'debit' ? 'success' : 'danger' 
                                                }} text-white">
                                                    {{ $ledger->current_balance_type == 'debit' ? 'Dr.' : 'Cr.' }} 
                                                    {{ number_format($ledger->current_balance, 2) }}
                                                </div>
                                            </div>
                                            <div>
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
                                            </div>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($group->ledgers->count() == 0)
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
                <div class="text-center py-5">
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

        <!-- Summary Statistics -->
        @if($groups->count() > 0)
            <div class="row mt-4">
                @php
                    $totalGroups = $groups->count();
                    $totalLedgers = $groups->sum(function($group) {
                        return $group->ledgers->count();
                    });
                    $activeLedgers = $groups->sum(function($group) {
                        return $group->ledgers->where('is_active', true)->count();
                    });
                @endphp
                
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Groups</h6>
                                    <h2 class="mb-0">{{ $totalGroups }}</h2>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="fa fa-folder"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Accounts</h6>
                                    <h2 class="mb-0">{{ $totalLedgers }}</h2>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="fa fa-book"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Active Accounts</h6>
                                    <h2 class="mb-0">{{ $activeLedgers }}</h2>
                                </div>
                                <div class="fs-1 opacity-50">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                            <a href="{{ route('accounts.coa.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-list"></i> List View
                            </a>
                            <a href="{{ route('accounts.groups.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-folder"></i> Manage Groups
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
    const icon = document.getElementById('icon-' + groupId);
    
    if (groupContent.style.display === 'none') {
        groupContent.style.display = 'block';
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
    } else {
        groupContent.style.display = 'none';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
    }
}

// Expand all groups
function expandAll() {
    document.querySelectorAll('.ledgers-container').forEach(container => {
        container.style.display = 'block';
        const groupId = container.id;
        const icon = document.getElementById('icon-' + groupId);
        if (icon) {
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        }
    });
}

// Collapse all groups
function collapseAll() {
    document.querySelectorAll('.ledgers-container').forEach(container => {
        container.style.display = 'none';
        const groupId = container.id;
        const icon = document.getElementById('icon-' + groupId);
        if (icon) {
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        }
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