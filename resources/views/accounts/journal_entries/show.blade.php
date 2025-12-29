@extends('admin.layouts.main')

@section('title', 'Journal Entry - ' . $entry->entry_number)

@section('css')
<style>
    /* ===== JOURNAL ENTRY VIEW - PREMIUM VOUCHER STYLE ===== */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
    
    .journal-view-wrapper {
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
        background: url("data:image/svg+xml,%3Csvg width='400' height='200' viewBox='0 0 400 200' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='350' cy='50' r='120' fill='rgba(255,255,255,0.03)'/%3E%3Ccircle cx='300' cy='150' r='80' fill='rgba(255,255,255,0.02)'/%3E%3C/svg%3E") no-repeat right center;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .hero-title-section h1 {
        font-size: 24px;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 6px 0;
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
        border-radius: 14px;
        margin-right: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .hero-title-section .icon-circle.posted {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .hero-title-section .icon-circle.draft {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .hero-title-section .icon-circle i {
        font-size: 20px;
        color: white;
    }

    .hero-actions {
        display: flex;
        gap: 12px;
    }

    .btn-hero-action {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .btn-hero-back {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .btn-hero-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .btn-hero-edit {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    .btn-hero-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
        color: white;
    }

    .btn-hero-print {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
    }

    .btn-hero-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.5);
        color: white;
    }

    /* Main Layout */
    .voucher-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
    }

    /* Voucher Card */
    .voucher-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .voucher-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 28px 32px;
        border-bottom: 2px solid #e2e8f0;
        position: relative;
    }

    .voucher-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 32px;
        right: 32px;
        height: 2px;
        background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 50%, #22c55e 100%);
    }

    .voucher-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .voucher-title-section h2 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 8px 0;
    }

    .voucher-number {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        color: #1d4ed8;
    }

    .voucher-number i {
        font-size: 14px;
    }

    .voucher-meta {
        text-align: right;
    }

    .voucher-date {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 8px;
    }

    .voucher-date strong {
        color: #0f172a;
    }

    .voucher-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .voucher-type-badge.jv { background: #eff6ff; color: #1d4ed8; }
    .voucher-type-badge.cpv { background: #fef3c7; color: #b45309; }
    .voucher-type-badge.bpv { background: #fee2e2; color: #b91c1c; }
    .voucher-type-badge.crv { background: #dcfce7; color: #15803d; }
    .voucher-type-badge.brv { background: #d1fae5; color: #047857; }

    .voucher-body {
        padding: 28px 32px;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    .info-item {
        background: #f8fafc;
        padding: 16px 20px;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
    }

    .info-item.full-width {
        grid-column: span 3;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 500;
        color: #0f172a;
    }

    /* Lines Table */
    .lines-section-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .lines-section-title i {
        color: #3b82f6;
    }

    .voucher-lines-table {
        width: 100%;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
    }

    .voucher-lines-table thead th {
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    .voucher-lines-table thead th:last-child,
    .voucher-lines-table thead th:nth-child(3) {
        text-align: right;
    }

    .voucher-lines-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
    }

    .voucher-lines-table tbody tr:last-child {
        border-bottom: none;
    }

    .voucher-lines-table tbody tr:hover {
        background: #fafbfc;
    }

    .voucher-lines-table tbody td {
        padding: 18px 20px;
        font-size: 14px;
        color: #334155;
    }

    .voucher-lines-table tbody td:last-child,
    .voucher-lines-table tbody td:nth-child(3) {
        text-align: right;
    }

    .account-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .account-code {
        font-weight: 600;
        color: #1e3a5f;
        font-family: 'SF Mono', 'Fira Code', monospace;
    }

    .account-name {
        font-size: 13px;
        color: #64748b;
    }

    .amount-cell {
        font-weight: 600;
        font-family: 'SF Mono', 'Fira Code', monospace;
        color: #0f172a;
    }

    .amount-cell.debit { color: #dc2626; }
    .amount-cell.credit { color: #16a34a; }

    .voucher-lines-table tfoot {
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
        border-top: 2px solid #e2e8f0;
    }

    .voucher-lines-table tfoot td {
        padding: 18px 20px;
        font-size: 15px;
    }

    .voucher-lines-table tfoot td:last-child,
    .voucher-lines-table tfoot td:nth-child(3) {
        text-align: right;
    }

    .total-label {
        font-weight: 700;
        color: #1e293b;
    }

    .total-amount {
        font-weight: 700;
        font-family: 'SF Mono', 'Fira Code', monospace;
    }

    .total-amount.debit { color: #dc2626; }
    .total-amount.credit { color: #16a34a; }

    /* Balance Alert */
    .balance-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        border-radius: 12px;
        margin-top: 20px;
    }

    .balance-alert.balanced {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(22, 163, 74, 0.1) 100%);
        border: 2px solid #22c55e;
    }

    .balance-alert.unbalanced {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
        border: 2px solid #ef4444;
    }

    .balance-alert-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .balance-alert.balanced .balance-alert-icon {
        background: #22c55e;
        color: white;
    }

    .balance-alert.unbalanced .balance-alert-icon {
        background: #ef4444;
        color: white;
    }

    .balance-alert-text {
        font-size: 14px;
        font-weight: 600;
    }

    .balance-alert.balanced .balance-alert-text { color: #16a34a; }
    .balance-alert.unbalanced .balance-alert-text { color: #dc2626; }

    /* Sidebar Cards */
    .sidebar-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .sidebar-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(to right, #fafbfc, #ffffff);
    }

    .sidebar-card-title {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-card-title i {
        color: #3b82f6;
    }

    .sidebar-card-body {
        padding: 20px;
    }

    /* Status Badge Large */
    .status-badge-large {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 20px;
        border-radius: 14px;
        margin-bottom: 16px;
    }

    .status-badge-large.posted {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(22, 163, 74, 0.15) 100%);
        border: 2px solid #22c55e;
    }

    .status-badge-large.draft {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%);
        border: 2px solid #f59e0b;
    }

    .status-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .status-badge-large.posted .status-icon {
        background: #22c55e;
        color: white;
    }

    .status-badge-large.draft .status-icon {
        background: #f59e0b;
        color: white;
    }

    .status-info {
        flex: 1;
    }

    .status-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-value {
        font-size: 18px;
        font-weight: 700;
    }

    .status-badge-large.posted .status-value { color: #16a34a; }
    .status-badge-large.draft .status-value { color: #b45309; }

    /* Action Buttons */
    .action-btn-full {
        width: 100%;
        padding: 14px 20px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        margin-bottom: 10px;
    }

    .action-btn-full:last-child {
        margin-bottom: 0;
    }

    .action-btn-full.primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .action-btn-full.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .action-btn-full.success {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .action-btn-full.success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
        color: white;
    }

    .action-btn-full.danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .action-btn-full.danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        color: white;
    }

    /* Audit Info */
    .audit-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .audit-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .audit-list li:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .audit-list li:first-child {
        padding-top: 0;
    }

    .audit-label {
        font-size: 13px;
        color: #64748b;
    }

    .audit-value {
        font-size: 13px;
        font-weight: 500;
        color: #0f172a;
    }

    /* Info Alert */
    .info-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(29, 78, 216, 0.1) 100%);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 12px;
    }

    .info-alert-icon {
        width: 36px;
        height: 36px;
        background: #3b82f6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .info-alert-text {
        font-size: 13px;
        color: #1d4ed8;
        line-height: 1.5;
    }

    /* Print Styles */
    @media print {
        .journal-hero,
        .hero-actions,
        .sidebar-card:not(.print-show),
        .btn-hero-action,
        .no-print {
            display: none !important;
        }

        .voucher-layout {
            display: block;
        }

        .voucher-card {
            box-shadow: none;
            border: 2px solid #000;
        }

        body {
            background: white !important;
        }

        .main-content {
            padding: 0 !important;
        }
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .voucher-layout {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .info-item.full-width {
            grid-column: span 2;
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

        .hero-actions {
            flex-wrap: wrap;
            justify-content: center;
        }

        .voucher-header-content {
            flex-direction: column;
            gap: 16px;
        }

        .voucher-meta {
            text-align: left;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item.full-width {
            grid-column: span 1;
        }
    }

    /* Animations */
    .voucher-card,
    .sidebar-card {
        animation: slideUp 0.4s ease forwards;
        opacity: 0;
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .voucher-card { animation-delay: 0.1s; }
    .sidebar-card:nth-child(1) { animation-delay: 0.15s; }
    .sidebar-card:nth-child(2) { animation-delay: 0.2s; }
</style>
@endsection

@section('content')
<div class="journal-view-wrapper">
    {{-- Hero Header --}}
    <div class="journal-hero no-print">
        <div class="hero-content">
            <div class="hero-title-section d-flex align-items-center">
                <div class="icon-circle {{ $entry->status }}">
                    @if($entry->status == 'posted')
                        <i class="fa fa-check"></i>
                    @else
                        <i class="fa fa-pencil"></i>
                    @endif
                </div>
                <div>
                    <h1>{{ $entry->entry_number }}</h1>
                    <p>Journal Entry Details</p>
                </div>
            </div>
            <div class="hero-actions">
                <button onclick="window.print()" class="btn-hero-action btn-hero-print">
                    <i class="fa fa-print"></i>
                    Print Voucher
                </button>
                @if($entry->status == 'draft')
                <a href="{{ route('accounts.journal.edit', $entry->id) }}" class="btn-hero-action btn-hero-edit">
                    <i class="fa fa-edit"></i>
                    Edit Entry
                </a>
                @endif
                <a href="{{ route('accounts.journal.index') }}" class="btn-hero-action btn-hero-back">
                    <i class="fa fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="voucher-layout">
        {{-- Main Voucher Card --}}
        <div class="voucher-card">
            <div class="voucher-header">
                <div class="voucher-header-content">
                    <div class="voucher-title-section">
                        @php
                            $typeLabels = [
                                'journal_voucher' => 'Journal Voucher',
                                'cash_payment_voucher' => 'Cash Payment Voucher',
                                'bank_payment_voucher' => 'Bank Payment Voucher',
                                'cash_receipt_voucher' => 'Cash Receipt Voucher',
                                'bank_receipt_voucher' => 'Bank Receipt Voucher',
                            ];
                            $typeClass = match($entry->entry_type) {
                                'journal_voucher', 'journal' => 'jv',
                                'cash_payment_voucher' => 'cpv',
                                'bank_payment_voucher' => 'bpv',
                                'cash_receipt_voucher' => 'crv',
                                'bank_receipt_voucher' => 'brv',
                                default => 'jv'
                            };
                        @endphp
                        <h2>{{ $typeLabels[$entry->entry_type] ?? 'Journal Voucher' }}</h2>
                        <div class="voucher-number">
                            <i class="fa fa-hashtag"></i>
                            {{ $entry->entry_number }}
                        </div>
                    </div>
                    <div class="voucher-meta">
                        <div class="voucher-date">
                            <i class="fa fa-calendar"></i>
                            <strong>{{ $entry->entry_date->format('d M Y') }}</strong>
                        </div>
                        <div class="voucher-type-badge {{ $typeClass }}">
                            <i class="fa fa-tag"></i>
                            {{ strtoupper($typeClass) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="voucher-body">
                {{-- Info Grid --}}
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Reference Number</div>
                        <div class="info-value">{{ $entry->reference ?: 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Entry Status</div>
                        <div class="info-value">
                            @if($entry->status == 'posted')
                                <span style="color: #16a34a; font-weight: 600;">● Posted</span>
                            @else
                                <span style="color: #d97706; font-weight: 600;">● Draft</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Branch</div>
                        <div class="info-value">{{ $entry->branch->name ?? 'Main Branch' }}</div>
                    </div>
                    <div class="info-item full-width">
                        <div class="info-label">Description / Narration</div>
                        <div class="info-value">{{ $entry->description }}</div>
                    </div>
                </div>

                {{-- Lines Table --}}
                <h4 class="lines-section-title">
                    <i class="fa fa-list-alt"></i>
                    Voucher Lines
                </h4>
                <table class="voucher-lines-table">
                    <thead>
                        <tr>
                            <th style="width: 45%">Account</th>
                            <th style="width: 25%">Description</th>
                            <th style="width: 15%">Debit (Rs.)</th>
                            <th style="width: 15%">Credit (Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entry->lines as $line)
                        <tr>
                            <td>
                                <div class="account-cell">
                                    <span class="account-code">{{ $line->accountLedger->code }}</span>
                                    <span class="account-name">{{ $line->accountLedger->name }}</span>
                                </div>
                            </td>
                            <td>{{ $line->description ?: '-' }}</td>
                            <td>
                                @if($line->debit > 0)
                                    <span class="amount-cell debit">{{ number_format($line->debit, 2) }}</span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td>
                                @if($line->credit > 0)
                                    <span class="amount-cell credit">{{ number_format($line->credit, 2) }}</span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="total-label">Total Amount</td>
                            <td><span class="total-amount debit">Rs. {{ number_format($entry->total_debit, 2) }}</span></td>
                            <td><span class="total-amount credit">Rs. {{ number_format($entry->total_credit, 2) }}</span></td>
                        </tr>
                    </tfoot>
                </table>

                {{-- Balance Alert --}}
                @if($entry->isBalanced())
                <div class="balance-alert balanced">
                    <div class="balance-alert-icon">
                        <i class="fa fa-check"></i>
                    </div>
                    <span class="balance-alert-text">This entry is balanced and accurate</span>
                </div>
                @else
                <div class="balance-alert unbalanced">
                    <div class="balance-alert-icon">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <span class="balance-alert-text">Warning: This entry is not balanced!</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="voucher-sidebar">
            {{-- Status & Actions --}}
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h4 class="sidebar-card-title">
                        <i class="fa fa-cog"></i>
                        Status & Actions
                    </h4>
                </div>
                <div class="sidebar-card-body">
                    <div class="status-badge-large {{ $entry->status }}">
                        <div class="status-icon">
                            @if($entry->status == 'posted')
                                <i class="fa fa-check"></i>
                            @else
                                <i class="fa fa-pencil"></i>
                            @endif
                        </div>
                        <div class="status-info">
                            <div class="status-label">Current Status</div>
                            <div class="status-value">{{ ucfirst($entry->status) }}</div>
                        </div>
                    </div>

                    @if($entry->status == 'draft')
                        <form action="{{ route('accounts.journal.approve', $entry->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to post this entry? It cannot be edited after posting.')">
                            @csrf
                            <button type="submit" class="action-btn-full success">
                                <i class="fa fa-check-circle"></i>
                                Post Entry
                            </button>
                        </form>

                        <a href="{{ route('accounts.journal.edit', $entry->id) }}" class="action-btn-full primary">
                            <i class="fa fa-edit"></i>
                            Edit Entry
                        </a>

                        <form action="{{ route('accounts.journal.destroy', $entry->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this entry?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn-full danger">
                                <i class="fa fa-trash"></i>
                                Delete Entry
                            </button>
                        </form>
                    @else
                        <div class="info-alert">
                            <div class="info-alert-icon">
                                <i class="fa fa-lock"></i>
                            </div>
                            <div class="info-alert-text">
                                This entry has been posted and is now locked. Posted entries cannot be modified to maintain accounting integrity.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Audit Info --}}
            <div class="sidebar-card print-show">
                <div class="sidebar-card-header">
                    <h4 class="sidebar-card-title">
                        <i class="fa fa-history"></i>
                        Audit Trail
                    </h4>
                </div>
                <div class="sidebar-card-body">
                    <ul class="audit-list">
                        <li>
                            <span class="audit-label">Created On</span>
                            <span class="audit-value">{{ $entry->created_at->format('d M Y, H:i') }}</span>
                        </li>
                        <li>
                            <span class="audit-label">Last Updated</span>
                            <span class="audit-value">{{ $entry->updated_at->format('d M Y, H:i') }}</span>
                        </li>
                        @if($entry->posted_at)
                        <li>
                            <span class="audit-label">Posted On</span>
                            <span class="audit-value">{{ $entry->posted_at->format('d M Y, H:i') }}</span>
                        </li>
                        @endif
                        @if($entry->source_module)
                        <li>
                            <span class="audit-label">Source Module</span>
                            <span class="audit-value">{{ ucfirst($entry->source_module) }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Any interactive scripts can go here
</script>
@endsection
