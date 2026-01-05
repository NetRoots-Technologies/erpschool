@extends('admin.layouts.main')

@section('title', 'Journal Entries')

@section('css')
<style>
    /* ===== JOURNAL ENTRIES PREMIUM THEME ===== */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
    
    .journal-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Hero Header */
    .journal-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #0d253f 50%, #0a1929 100%);
        border-radius: 20px;
        padding: 32px 40px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(30, 58, 95, 0.3);
    }

    .journal-hero::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 400px;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='400' height='200' viewBox='0 0 400 200' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='350' cy='50' r='120' fill='rgba(255,255,255,0.03)'/%3E%3Ccircle cx='300' cy='150' r='80' fill='rgba(255,255,255,0.02)'/%3E%3Ccircle cx='380' cy='180' r='60' fill='rgba(255,255,255,0.02)'/%3E%3C/svg%3E") no-repeat right center;
    }

    .journal-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(34, 197, 94, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .hero-title-section h1 {
        font-size: 28px;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 8px 0;
        letter-spacing: -0.5px;
    }

    .hero-title-section p {
        color: rgba(255, 255, 255, 0.6);
        font-size: 14px;
        margin: 0;
    }

    .hero-title-section .icon-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border-radius: 14px;
        margin-right: 16px;
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
    }

    .hero-title-section .icon-circle i {
        font-size: 22px;
        color: white;
    }

    .hero-actions {
        display: flex;
        gap: 12px;
    }

    .btn-hero-primary {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4);
        text-decoration: none;
    }

    .btn-hero-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(34, 197, 94, 0.5);
        color: white;
    }

    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 14px 24px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        backdrop-filter: blur(10px);
    }

    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.total::before { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
    .stat-card.posted::before { background: linear-gradient(90deg, #22c55e, #16a34a); }
    .stat-card.draft::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .stat-card.amount::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .stat-card.total .stat-icon { background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(29, 78, 216, 0.15) 100%); color: #3b82f6; }
    .stat-card.posted .stat-icon { background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(22, 163, 74, 0.15) 100%); color: #22c55e; }
    .stat-card.draft .stat-icon { background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%); color: #f59e0b; }
    .stat-card.amount .stat-icon { background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(124, 58, 237, 0.15) 100%); color: #8b5cf6; }

    .stat-icon i {
        font-size: 24px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }

    /* Main Card */
    .journal-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .journal-card-header {
        padding: 24px 28px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(to right, #fafbfc, #ffffff);
    }

    .journal-card-title {
        font-size: 17px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .journal-card-title i {
        color: #3b82f6;
    }

    /* Filters */
    .filter-group {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .filter-input {
        padding: 10px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        color: #475569;
        background: #f8fafc;
        transition: all 0.2s ease;
        min-width: 180px;
    }

    .filter-input:focus {
        outline: none;
        border-color: #3b82f6;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filter-btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .filter-btn-primary {
        background: #3b82f6;
        color: white;
    }

    .filter-btn-primary:hover {
        background: #2563eb;
    }

    .filter-btn-secondary {
        background: #f1f5f9;
        color: #64748b;
    }

    .filter-btn-secondary:hover {
        background: #e2e8f0;
    }

    /* Table */
    .journal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .journal-table thead th {
        background: #f8fafc;
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }

    .journal-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .journal-table tbody tr:hover {
        background: #f8fafc;
    }

    .journal-table tbody tr:last-child {
        border-bottom: none;
    }

    .journal-table tbody td {
        padding: 18px 20px;
        font-size: 14px;
        color: #334155;
    }

    .entry-number {
        font-weight: 600;
        color: #1e3a5f;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: color 0.2s ease;
    }

    .entry-number:hover {
        color: #3b82f6;
    }

    .entry-number i {
        font-size: 10px;
        opacity: 0;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .entry-number:hover i {
        opacity: 1;
        transform: translateX(3px);
    }

    .date-cell {
        color: #64748b;
    }

    .date-cell i {
        margin-right: 6px;
        color: #94a3b8;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        gap: 6px;
    }

    .type-badge.jv { background: #eff6ff; color: #1d4ed8; }
    .type-badge.cpv { background: #fef3c7; color: #b45309; }
    .type-badge.bpv { background: #fee2e2; color: #b91c1c; }
    .type-badge.crv { background: #dcfce7; color: #15803d; }
    .type-badge.brv { background: #d1fae5; color: #047857; }

    .amount-cell {
        font-weight: 600;
        font-family: 'SF Mono', 'Fira Code', monospace;
        color: #0f172a;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        gap: 6px;
    }

    .status-badge.posted {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #15803d;
    }

    .status-badge.draft {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #b45309;
    }

    .status-badge.cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #b91c1c;
    }

    .status-badge i {
        font-size: 10px;
    }

    .action-btns {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .action-btn.view {
        background: #eff6ff;
        color: #3b82f6;
    }

    .action-btn.view:hover {
        background: #3b82f6;
        color: white;
    }

    .action-btn.edit {
        background: #fef3c7;
        color: #d97706;
    }

    .action-btn.edit:hover {
        background: #f59e0b;
        color: white;
    }

    .action-btn.post {
        background: #dcfce7;
        color: #16a34a;
    }

    .action-btn.post:hover {
        background: #22c55e;
        color: white;
    }

    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-btn.delete:hover {
        background: #ef4444;
        color: white;
    }

    /* Empty State */
    .empty-state {
        padding: 80px 40px;
        text-align: center;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        border-radius: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
    }

    .empty-state-icon i {
        font-size: 40px;
        color: #94a3b8;
    }

    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 24px;
    }

    /* Pagination */
    .journal-pagination {
        padding: 20px 28px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pagination-info {
        font-size: 13px;
        color: #64748b;
    }

    .pagination {
        margin: 0;
        gap: 4px;
    }

    .pagination .page-item .page-link {
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        background: transparent;
    }

    .pagination .page-item .page-link:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .pagination .page-item.active .page-link {
        background: #3b82f6;
        color: white;
    }

    /* Alert Styles */
    .alert-floating {
        position: fixed;
        top: 100px;
        right: 30px;
        z-index: 9999;
        min-width: 350px;
        border-radius: 12px;
        padding: 16px 20px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease;
    }

    .alert-floating.alert-success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .journal-hero {
            padding: 24px;
        }

        .hero-content {
            flex-direction: column;
            gap: 20px;
            text-align: center;
        }

        .stats-row {
            grid-template-columns: 1fr;
        }

        .filter-group {
            flex-wrap: wrap;
        }

        .journal-card-header {
            flex-direction: column;
            gap: 16px;
        }
    }

    /* Description Truncate */
    .desc-cell {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .reference-cell {
        color: #64748b;
        font-size: 13px;
    }

    /* Animation for rows */
    .journal-table tbody tr {
        animation: fadeIn 0.3s ease forwards;
        opacity: 0;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }

    .journal-table tbody tr:nth-child(1) { animation-delay: 0.05s; }
    .journal-table tbody tr:nth-child(2) { animation-delay: 0.1s; }
    .journal-table tbody tr:nth-child(3) { animation-delay: 0.15s; }
    .journal-table tbody tr:nth-child(4) { animation-delay: 0.2s; }
    .journal-table tbody tr:nth-child(5) { animation-delay: 0.25s; }
    .journal-table tbody tr:nth-child(6) { animation-delay: 0.3s; }
    .journal-table tbody tr:nth-child(7) { animation-delay: 0.35s; }
    .journal-table tbody tr:nth-child(8) { animation-delay: 0.4s; }
    .journal-table tbody tr:nth-child(9) { animation-delay: 0.45s; }
    .journal-table tbody tr:nth-child(10) { animation-delay: 0.5s; }
</style>
@endsection

@section('content')
<div class="journal-wrapper">
    {{-- Success Alert --}}
    @if(session('success'))
    <div class="alert-floating alert-success alert-dismissible fade show">
        <i class="fa fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Hero Header --}}
    <div class="journal-hero">
        <div class="hero-content">
            <div class="hero-title-section d-flex align-items-center">
                <div class="icon-circle">
                    <i class="fa fa-book"></i>
                </div>
                <div>
                    <h1>Journal Entries</h1>
                    <p>Manage your financial transactions and vouchers</p>
                </div>
            </div>
            <div class="hero-actions">
                <a href="{{ route('accounts.journal.create') }}" class="btn-hero-primary">
                    <i class="fa fa-plus"></i>
                    New Entry
                </a>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    @php
        $totalEntries = $entries->total();
        $postedCount = \App\Models\Accounts\JournalEntry::where('status', 'posted')->count();
        $draftCount = \App\Models\Accounts\JournalEntry::where('status', 'draft')->count();
        $totalAmount = \App\Models\Accounts\JournalEntry::where('status', 'posted')
            ->with('lines')
            ->get()
            ->sum(function($e) { return $e->lines->sum('debit'); });
    @endphp
    <div class="stats-row">
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fa fa-file-text-o"></i>
            </div>
            <div class="stat-value">{{ number_format($totalEntries) }}</div>
            <div class="stat-label">Total Entries</div>
        </div>
        <div class="stat-card posted">
            <div class="stat-icon">
                <i class="fa fa-check-circle-o"></i>
            </div>
            <div class="stat-value">{{ number_format($postedCount) }}</div>
            <div class="stat-label">Posted Entries</div>
        </div>
        <div class="stat-card draft">
            <div class="stat-icon">
                <i class="fa fa-pencil-square-o"></i>
            </div>
            <div class="stat-value">{{ number_format($draftCount) }}</div>
            <div class="stat-label">Draft Entries</div>
        </div>
        <div class="stat-card amount">
            <div class="stat-icon">
                <i class="fa fa-money"></i>
            </div>
            <div class="stat-value">Rs. {{ number_format($totalAmount, 0) }}</div>
            <div class="stat-label">Total Posted Amount</div>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="journal-card">
        <div class="journal-card-header">
            <h3 class="journal-card-title">
                <i class="fa fa-list"></i>
                All Journal Entries
            </h3>
            <div class="filter-group">
                <input type="text" class="filter-input" id="searchInput" placeholder="Search entries...">
                <select class="filter-input" id="statusFilter" style="min-width: 140px;">
                    <option value="">All Status</option>
                    <option value="posted">Posted</option>
                    <option value="draft">Draft</option>
                </select>
                <select class="filter-input" id="typeFilter" style="min-width: 160px;">
                    <option value="">All Types</option>
                    <option value="journal_voucher">Journal Voucher</option>
                    <option value="cash_payment_voucher">Cash Payment</option>
                    <option value="bank_payment_voucher">Bank Payment</option>
                    <option value="cash_receipt_voucher">Cash Receipt</option>
                    <option value="bank_receipt_voucher">Bank Receipt</option>
                </select>
            </div>
        </div>

        @if($entries->count() > 0)
        <div class="table-responsive">
            <table class="journal-table" id="journalTable">
                <thead>
                    <tr>
                        <th>Entry Number</th>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                    <tr data-status="{{ $entry->status }}" data-type="{{ $entry->entry_type }}">
                        <td>
                            <a href="{{ route('accounts.journal.show', $entry->id) }}" class="entry-number">
                                {{ $entry->entry_number }}
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </td>
                        <td class="date-cell">
                            <i class="fa fa-calendar"></i>
                            {{ $entry->entry_date->format('d M Y') }}
                        </td>
                        <td class="reference-cell">{{ $entry->reference ?: '-' }}</td>
                        <td class="desc-cell" title="{{ $entry->description }}">{{ $entry->description }}</td>
                        <td>
                            @php
                                $typeClass = match($entry->entry_type) {
                                    'journal_voucher', 'journal' => 'jv',
                                    'cash_payment_voucher' => 'cpv',
                                    'bank_payment_voucher' => 'bpv',
                                    'cash_receipt_voucher' => 'crv',
                                    'bank_receipt_voucher' => 'brv',
                                    default => 'jv'
                                };
                                $typeLabel = match($entry->entry_type) {
                                    'journal_voucher' => 'JV',
                                    'cash_payment_voucher' => 'CPV',
                                    'bank_payment_voucher' => 'BPV',
                                    'cash_receipt_voucher' => 'CRV',
                                    'bank_receipt_voucher' => 'BRV',
                                    default => ucfirst(str_replace('_', ' ', $entry->entry_type))
                                };
                            @endphp
                            <span class="type-badge {{ $typeClass }}">
                                <i class="fa fa-tag"></i>
                                {{ $typeLabel }}
                            </span>
                        </td>
                        <td class="amount-cell">Rs. {{ number_format($entry->total_debit, 2) }}</td>
                        <td>
                            @if($entry->status == 'posted')
                                <span class="status-badge posted">
                                    <i class="fa fa-circle"></i>
                                    Posted
                                </span>
                            @elseif($entry->status == 'draft')
                                <span class="status-badge draft">
                                    <i class="fa fa-circle"></i>
                                    Draft
                                </span>
                            @else
                                <span class="status-badge cancelled">
                                    <i class="fa fa-circle"></i>
                                    {{ ucfirst($entry->status) }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-btns" style="justify-content: center;">
                                <a href="{{ route('accounts.journal.show', $entry->id) }}" class="action-btn view" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if($entry->status == 'draft')
                                <a href="{{ route('accounts.journal.edit', $entry->id) }}" class="action-btn edit" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <form action="{{ route('accounts.journal.approve', $entry->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to post this entry?')">
                                    @csrf
                                    <button type="submit" class="action-btn post" title="Post Entry">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="journal-pagination">
            <div class="pagination-info">
                Showing {{ $entries->firstItem() ?? 0 }} to {{ $entries->lastItem() ?? 0 }} of {{ $entries->total() }} entries
            </div>
            <div>
                {{ $entries->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fa fa-file-text-o"></i>
            </div>
            <h3>No Journal Entries Found</h3>
            <p>Create your first journal entry to get started</p>
            <a href="{{ route('accounts.journal.create') }}" class="btn-hero-primary">
                <i class="fa fa-plus"></i>
                Create New Entry
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const table = document.getElementById('journalTable');
    const rows = table ? table.querySelectorAll('tbody tr') : [];

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const rowStatus = row.dataset.status;
            const rowType = row.dataset.type;

            const matchesSearch = text.includes(searchTerm);
            const matchesStatus = !statusValue || rowStatus === statusValue;
            const matchesType = !typeValue || rowType === typeValue;

            row.style.display = matchesSearch && matchesStatus && matchesType ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
    if (typeFilter) typeFilter.addEventListener('change', filterTable);

    // Auto-hide alert after 5 seconds
    const alert = document.querySelector('.alert-floating');
    if (alert) {
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
});
</script>
@endsection
