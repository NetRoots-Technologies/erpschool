@extends('admin.layouts.main')

@section('title', 'Chart of Accounts - Tree View')

@section('css')
<style>
/* --- Your existing CSS for tree, cards, expand/collapse --- */
.tree-container { background: #f8f9fa; border-radius: 15px; padding: 20px; }
.tree-node { margin: 10px 0; position: relative; }
.group-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; transition: all 0.3s ease; }
.ledger-card { background: white; border: 2px solid #e9ecef; border-radius: 8px; transition: all 0.3s ease; margin-left: 20px; }
.expand-btn { background: rgba(255,255,255,0.2); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; }
.type-indicator { width: 4px; height: 100%; position: absolute; left: 0; top: 0; border-radius: 8px 0 0 8px; }
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
        <div class="tree-container">
            @forelse($groups as $group)
                @include('accounts.chart_of_accounts.partials.group', ['group' => $group])
            @empty
                <div class="text-center py-5">
                    <h4 class="text-muted">No Account Groups Found</h4>
                    <p>Create groups first, then add accounts.</p>
                    <a href="{{ route('accounts.groups.create') }}" class="btn btn-primary">Create Groups</a>
                </div>
            @endforelse
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
    document.querySelectorAll('.ledgers-container').forEach(container => container.style.display = 'block');
    document.querySelectorAll('.expand-btn i').forEach(icon => {
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
    });
}

// Collapse all groups
function collapseAll() {
    document.querySelectorAll('.ledgers-container').forEach(container => container.style.display = 'none');
    document.querySelectorAll('.expand-btn i').forEach(icon => {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
    });
}

// Auto-expand all on load
document.addEventListener('DOMContentLoaded', function() { expandAll(); });
</script>
@endsection
