@extends('admin.layouts.main')

@section('title', 'Chart of Accounts Tree')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Chart of Accounts - Tree View</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.coa.index') }}" class="btn btn-secondary">
                    <i class="fa fa-list"></i> List View
                </a>
                <a href="{{ route('accounts.coa.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add Account
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="tree-view">
                    @foreach($tree as $item)
                        @include('accounts.chart_of_accounts.tree_item', ['item' => $item, 'level' => 0])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.tree-view {
    font-family: monospace;
}
.tree-item {
    padding: 8px;
    margin-left: 20px;
    border-left: 2px solid #e0e0e0;
}
.tree-group {
    font-weight: bold;
    color: #0066cc;
    margin: 10px 0;
}
.tree-ledger {
    color: #333;
    padding: 5px 0;
}
.tree-balance {
    float: right;
    font-weight: bold;
}
</style>
@endsection
