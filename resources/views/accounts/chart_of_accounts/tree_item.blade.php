<div class="tree-item" style="margin-left: {{ $level * 30 }}px">
    <div class="tree-group">
        <i class="fa fa-folder-open"></i> 
        {{ $item['group']->code }} - {{ $item['group']->name }}
        <span class="badge bg-{{ $item['group']->type == 'asset' ? 'success' : ($item['group']->type == 'liability' ? 'danger' : ($item['group']->type == 'revenue' ? 'info' : 'warning')) }}">
            {{ ucfirst($item['group']->type) }}
        </span>
    </div>
    
    @foreach($item['group']->ledgers as $ledger)
    <div class="tree-ledger" style="margin-left: 20px">
        <i class="fa fa-file-text-o"></i> 
        {{ $ledger->code }} - {{ $ledger->name }}
        <span class="tree-balance">
            @if($ledger->current_balance_type == 'debit')
                <span class="text-success">Dr. {{ number_format($ledger->current_balance, 2) }}</span>
            @else
                <span class="text-danger">Cr. {{ number_format($ledger->current_balance, 2) }}</span>
            @endif
        </span>
    </div>
    @endforeach
    
    @foreach($item['children'] as $child)
        @include('accounts.chart_of_accounts.tree_item', ['item' => $child, 'level' => $level + 1])
    @endforeach
</div>
