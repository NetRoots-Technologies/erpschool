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
                        <small class="opacity-75">{{ $group->description ?? 'No description' }}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-light text-dark">{{ $group->code }}</span>
                    <span class="badge bg-{{ $group->type }}">{{ ucfirst($group->type) }}</span>
                    <span class="badge bg-light text-dark">{{ $group->ledgers->count() }} accounts</span>
                    <button class="expand-btn" onclick="toggleGroup('group-{{ $group->id }}')">
                        <i class="fa fa-chevron-down" id="icon-group-{{ $group->id }}"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ledgers -->
    <div id="group-{{ $group->id }}" class="ledgers-container">
        @foreach($group->ledgers as $ledger)
            <div class="ledger-card mb-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $ledger->code }}</strong> - {{ $ledger->name }}
                        @if($ledger->description)
                            <br><small>{{ $ledger->description }}</small>
                        @endif
                    </div>
                    <div>
                        {{ number_format($ledger->current_balance, 2) }} ({{ ucfirst($ledger->current_balance_type) }})
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Recursive Children -->
        @foreach($group->childrenRecursive as $child)
            @include('accounts.chart_of_accounts.partials.group', ['group' => $child])
        @endforeach
    </div>
</div>
