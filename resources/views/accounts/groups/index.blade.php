@extends('admin.layouts.main')

@section('title', 'Account Groups')

@section('css')
<style>
    .group-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    .group-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .group-card.asset { border-left-color: #28a745; }
    .group-card.liability { border-left-color: #dc3545; }
    .group-card.equity { border-left-color: #007bff; }
    .group-card.revenue { border-left-color: #17a2b8; }
    .group-card.expense { border-left-color: #ffc107; }
    
    .stats-card {
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .child-group {
        background: #f8f9fa;
        border-left: 3px solid #dee2e6;
        padding: 10px;
        margin: 5px 0;
        border-radius: 5px;
    }
    
    .badge-type {
        font-size: 0.85rem;
        padding: 5px 12px;
        font-weight: 500;
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
                    <h3 class="mb-1"><i class="fa fa-sitemap text-primary"></i> Account Groups</h3>
                    <p class="text-muted mb-0">Organize your chart of accounts into categories</p>
                </div>
                <div>
                    <a href="{{ route('accounts.coa.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fa fa-list"></i> Chart of Accounts
                    </a>
                    <a href="{{ route('accounts.groups.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus-circle"></i> Add New Group
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
                <strong>Error!</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="row mb-4">
            @php
                $totalGroups = $groups->count();
                $totalSubGroups = $groups->sum(fn($g) => $g->children->count());
                $totalLedgers = $groups->sum(fn($g) => $g->ledgers->count() + $g->children->sum(fn($c) => $c->ledgers->count()));
            @endphp
            
            <div class="col-md-4">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Main Groups</h6>
                                <h2 class="mb-0">{{ $totalGroups }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa fa-folder-open"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card stats-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Sub Groups</h6>
                                <h2 class="mb-0">{{ $totalSubGroups }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa fa-sitemap"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card stats-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Ledgers</h6>
                                <h2 class="mb-0">{{ $totalLedgers }}</h2>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fa fa-book"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Groups -->
        @forelse($groups as $group)
            <div class="card group-card {{ $group->type }} mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="mb-0 me-3">
                                    <i class="fa fa-folder text-{{ 
                                        $group->type == 'asset' ? 'success' : 
                                        ($group->type == 'liability' ? 'danger' : 
                                        ($group->type == 'equity' ? 'primary' : 
                                        ($group->type == 'revenue' ? 'info' : 'warning'))) 
                                    }}"></i>
                                    {{ $group->name }}
                                </h5>
                                <span class="badge badge-type bg-{{ 
                                    $group->type == 'asset' ? 'success' : 
                                    ($group->type == 'liability' ? 'danger' : 
                                    ($group->type == 'equity' ? 'primary' : 
                                    ($group->type == 'revenue' ? 'info' : 'warning'))) 
                                }}">
                                    {{ ucfirst($group->type) }}
                                </span>
                                <span class="badge bg-secondary ms-2">{{ $group->code }}</span>
                            </div>
                            @if($group->description)
                                <p class="text-muted mb-2 small">
                                    <i class="fa fa-info-circle"></i> {{ $group->description }}
                                </p>
                            @endif
                            <div class="d-flex gap-3 small text-muted">
                                <span>
                                    <i class="fa fa-layer-group"></i> Level {{ $group->level }}
                                </span>
                                <span>
                                    <i class="fa fa-sitemap"></i> {{ $group->children->count() }} Sub-group(s)
                                </span>
                                <span>
                                    <i class="fa fa-book"></i> {{ $group->ledgers->count() }} Ledger(s)
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('accounts.groups.edit', $group->id) }}" 
                               class="btn btn-sm btn-outline-primary me-2"
                               title="Edit">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            @if($group->ledgers->count() == 0 && $group->children->count() == 0)
                                <form action="{{ route('accounts.groups.destroy', $group->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this group?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot delete - has sub-groups or ledgers">
                                    <i class="fa fa-lock"></i> Locked
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Sub Groups -->
                    @if($group->children->count() > 0)
                        <hr class="my-3">
                        <h6 class="text-muted mb-3">
                            <i class="fa fa-level-down-alt"></i> Sub Groups:
                        </h6>
                        <div class="row">
                            @foreach($group->children as $child)
                                <div class="col-md-6 mb-2">
                                    <div class="child-group">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    <i class="fa fa-chevron-right text-muted"></i>
                                                    {{ $child->name }}
                                                    <small class="text-muted">({{ $child->code }})</small>
                                                </h6>
                                                @if($child->description)
                                                    <p class="text-muted mb-1 small">{{ $child->description }}</p>
                                                @endif
                                                <small class="text-muted">
                                                    <i class="fa fa-book"></i> {{ $child->ledgers->count() }} ledger(s)
                                                </small>
                                            </div>
                                            <div>
                                                <a href="{{ route('accounts.groups.edit', $child->id) }}" 
                                                   class="btn btn-xs btn-light"
                                                   title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @if($child->ledgers->count() == 0)
                                                    <form action="{{ route('accounts.groups.destroy', $child->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Delete {{ $child->name }}?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-xs btn-light text-danger" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Account Groups Found</h5>
                    <p class="text-muted mb-4">Start by creating your first account group to organize your chart of accounts.</p>
                    <a href="{{ route('accounts.groups.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus-circle"></i> Create First Group
                    </a>
                </div>
            </div>
        @endforelse

        <!-- Info Box -->
        <div class="card mt-4 border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fa fa-lightbulb"></i> Quick Guide: Account Types</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6 class="text-success mb-2">
                                <i class="fa fa-coins"></i> Assets (اثاثے)
                            </h6>
                            <p class="small text-muted mb-1">Things you own - Resources with economic value</p>
                            <p class="small text-muted fst-italic">Examples: Cash, Bank, Furniture, Building, Accounts Receivable</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-danger mb-2">
                                <i class="fa fa-file-invoice-dollar"></i> Liabilities (قرضے)
                            </h6>
                            <p class="small text-muted mb-1">Things you owe - Financial obligations</p>
                            <p class="small text-muted fst-italic">Examples: Accounts Payable, Loans, Salaries Payable</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="fa fa-balance-scale"></i> Equity (سرمایہ)
                            </h6>
                            <p class="small text-muted mb-1">Owner's stake - Net worth of the organization</p>
                            <p class="small text-muted fst-italic">Examples: Capital, Retained Earnings</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h6 class="text-info mb-2">
                                <i class="fa fa-arrow-up"></i> Revenue (آمدنی)
                            </h6>
                            <p class="small text-muted mb-1">Income earned - Money coming in</p>
                            <p class="small text-muted fst-italic">Examples: Fee Income, Sales Revenue, Other Income</p>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="text-warning mb-2">
                                <i class="fa fa-arrow-down"></i> Expenses (خرچے)
                            </h6>
                            <p class="small text-muted mb-1">Costs incurred - Money going out</p>
                            <p class="small text-muted fst-italic">Examples: Salaries, Rent, Utilities, Stationery</p>
                        </div>
                        
                        <div class="alert alert-light mb-0">
                            <small>
                                <strong><i class="fa fa-calculator"></i> Formula:</strong><br>
                                Assets = Liabilities + Equity<br>
                                Profit = Revenue - Expenses
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endsection
