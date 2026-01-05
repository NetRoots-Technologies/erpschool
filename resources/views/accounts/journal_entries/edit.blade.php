@extends('admin.layouts.main')

@section('title', 'Edit Journal Entry - ' . $entry->entry_number)

@section('css')
<style>
    /* ===== JOURNAL ENTRY EDIT - PREMIUM THEME ===== */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
    
    .journal-edit-wrapper {
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
        font-size: 26px;
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
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        border-radius: 14px;
        margin-right: 16px;
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
    }

    .hero-title-section .icon-circle i {
        font-size: 20px;
        color: white;
    }

    .hero-actions {
        display: flex;
        gap: 12px;
    }

    .btn-hero-back {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 12px 24px;
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

    .btn-hero-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .btn-hero-view {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 500;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
    }

    .btn-hero-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.5);
        color: white;
    }

    /* Entry Number Badge */
    .entry-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #ffffff;
        margin-top: 10px;
    }

    .entry-badge i {
        color: #fbbf24;
    }

    /* Form Cards */
    .form-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .form-card-header {
        padding: 20px 28px;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(to right, #fafbfc, #ffffff);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card-header .header-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .form-card-header .header-icon.primary {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(29, 78, 216, 0.15) 100%);
        color: #3b82f6;
    }

    .form-card-header .header-icon.warning {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%);
        color: #f59e0b;
    }

    .form-card-header .header-icon.success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(22, 163, 74, 0.15) 100%);
        color: #22c55e;
    }

    .form-card-title {
        font-size: 17px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .form-card-subtitle {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .form-card-body {
        padding: 28px;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-label .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #1e293b;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .form-input::placeholder {
        color: #94a3b8;
    }

    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #1e293b;
        background: #ffffff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8.825L1.175 4 2.238 2.938 6 6.7 9.763 2.937 10.825 4z'/%3E%3C/svg%3E") no-repeat right 16px center;
        background-size: 12px;
        -webkit-appearance: none;
        appearance: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #1e293b;
        background: #ffffff;
        transition: all 0.2s ease;
        resize: vertical;
        min-height: 80px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    /* Voucher Type Cards */
    .voucher-types {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
    }

    .voucher-type-card {
        position: relative;
        cursor: pointer;
    }

    .voucher-type-card input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }

    .voucher-type-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 16px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        transition: all 0.2s ease;
        background: #f8fafc;
    }

    .voucher-type-card input:checked + .voucher-type-label {
        border-color: #3b82f6;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(29, 78, 216, 0.08) 100%);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .voucher-type-card:hover .voucher-type-label {
        border-color: #94a3b8;
    }

    .voucher-type-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        font-size: 20px;
    }

    .voucher-type-card.jv .voucher-type-icon { background: #eff6ff; color: #3b82f6; }
    .voucher-type-card.cpv .voucher-type-icon { background: #fef3c7; color: #d97706; }
    .voucher-type-card.bpv .voucher-type-icon { background: #fee2e2; color: #dc2626; }
    .voucher-type-card.crv .voucher-type-icon { background: #dcfce7; color: #16a34a; }
    .voucher-type-card.brv .voucher-type-icon { background: #d1fae5; color: #059669; }

    .voucher-type-name {
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        text-align: center;
    }

    .voucher-type-abbr {
        font-size: 10px;
        color: #64748b;
        margin-top: 2px;
    }

    /* Journal Lines Table */
    .lines-table-wrapper {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .lines-table {
        width: 100%;
        border-collapse: collapse;
    }

    .lines-table thead th {
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

    .lines-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .lines-table tbody tr:hover {
        background: #f8fafc;
    }

    .lines-table tbody tr:last-child {
        border-bottom: none;
    }

    .lines-table tbody td {
        padding: 16px 20px;
    }

    .lines-table tfoot {
        background: linear-gradient(to right, #f8fafc, #f1f5f9);
        border-top: 2px solid #e2e8f0;
    }

    .lines-table tfoot td {
        padding: 16px 20px;
    }

    .line-input {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        color: #1e293b;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .line-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .line-select {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        color: #1e293b;
        background: #ffffff;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .line-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .amount-input {
        text-align: right;
        font-family: 'SF Mono', 'Fira Code', monospace;
        font-weight: 600;
    }

    .btn-remove-line {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        background: #fee2e2;
        color: #dc2626;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-remove-line:hover {
        background: #dc2626;
        color: white;
    }

    .btn-remove-line:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .btn-add-line {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }

    .btn-add-line:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
    }

    /* Totals Display */
    .totals-row {
        display: flex;
        align-items: center;
        gap: 40px;
    }

    .total-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .total-label {
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
    }

    .total-value {
        font-size: 18px;
        font-weight: 700;
        font-family: 'SF Mono', 'Fira Code', monospace;
        color: #0f172a;
    }

    .total-value.debit { color: #dc2626; }
    .total-value.credit { color: #16a34a; }

    /* Balance Indicator */
    .balance-indicator {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .balance-indicator.balanced {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(22, 163, 74, 0.1) 100%);
        border: 2px solid #22c55e;
    }

    .balance-indicator.unbalanced {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
        border: 2px solid #ef4444;
    }

    .balance-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .balance-indicator.balanced .balance-icon {
        background: #22c55e;
        color: white;
    }

    .balance-indicator.unbalanced .balance-icon {
        background: #ef4444;
        color: white;
    }

    .balance-text {
        font-size: 14px;
        font-weight: 600;
    }

    .balance-indicator.balanced .balance-text { color: #16a34a; }
    .balance-indicator.unbalanced .balance-text { color: #dc2626; }

    /* Action Footer */
    .action-footer {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.04);
        padding: 24px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .action-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .action-info-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.15) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f59e0b;
        font-size: 20px;
    }

    .action-info-text h4 {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        margin: 0 0 4px 0;
    }

    .action-info-text p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
    }

    .btn-submit:disabled {
        background: #94a3b8;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Error Alert */
    .error-alert {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 2px solid #fecaca;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .error-alert-icon {
        width: 40px;
        height: 40px;
        background: #fee2e2;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .error-alert-icon i {
        font-size: 18px;
        color: #dc2626;
    }

    .error-alert-content h4 {
        font-size: 15px;
        font-weight: 600;
        color: #991b1b;
        margin: 0 0 8px 0;
    }

    .error-alert-content ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .error-alert-content ul li {
        font-size: 14px;
        color: #b91c1c;
        padding: 4px 0;
        position: relative;
        padding-left: 16px;
    }

    .error-alert-content ul li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: #dc2626;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .voucher-types {
            grid-template-columns: repeat(3, 1fr);
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

        .voucher-types {
            grid-template-columns: repeat(2, 1fr);
        }

        .action-footer {
            flex-direction: column;
            gap: 20px;
        }

        .totals-row {
            flex-direction: column;
            gap: 16px;
        }

        .hero-actions {
            flex-wrap: wrap;
            justify-content: center;
        }
    }

    /* Animation */
    .form-card {
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

    .form-card:nth-child(1) { animation-delay: 0.1s; }
    .form-card:nth-child(2) { animation-delay: 0.2s; }
    .form-card:nth-child(3) { animation-delay: 0.3s; }
</style>
@endsection

@section('content')
<div class="journal-edit-wrapper">
    {{-- Hero Header --}}
    <div class="journal-hero">
        <div class="hero-content">
            <div class="hero-title-section d-flex align-items-center">
                <div class="icon-circle">
                    <i class="fa fa-edit"></i>
                </div>
                <div>
                    <h1>Edit Journal Entry</h1>
                    <p>Modify the draft entry details</p>
                    <div class="entry-badge">
                        <i class="fa fa-hashtag"></i>
                        {{ $entry->entry_number }}
                    </div>
                </div>
            </div>
            <div class="hero-actions">
                <a href="{{ route('accounts.journal.show', $entry->id) }}" class="btn-hero-view">
                    <i class="fa fa-eye"></i>
                    View Entry
                </a>
                <a href="{{ route('accounts.journal.index') }}" class="btn-hero-back">
                    <i class="fa fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
    <div class="error-alert">
        <div class="error-alert-icon">
            <i class="fa fa-exclamation-triangle"></i>
        </div>
        <div class="error-alert-content">
            <h4>Please fix the following errors:</h4>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('accounts.journal.update', $entry->id) }}" method="POST" id="journalForm">
        @csrf
        @method('PUT')
        
        {{-- Entry Details Card --}}
        <div class="form-card" style="animation-delay: 0.1s;">
            <div class="form-card-header">
                <div class="header-icon warning">
                    <i class="fa fa-file-text-o"></i>
                </div>
                <div>
                    <h3 class="form-card-title">Entry Details</h3>
                    <p class="form-card-subtitle">Basic information about this journal entry</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">
                                Entry Date <span class="required">*</span>
                            </label>
                            <input type="date" name="entry_date" class="form-input" value="{{ $entry->entry_date->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Reference Number</label>
                            <input type="text" name="reference" class="form-input" value="{{ $entry->reference }}" placeholder="e.g., INV-001, CHQ-123">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Voucher Type</label>
                            <div class="voucher-types">
                                <div class="voucher-type-card jv">
                                    <input type="radio" name="entry_type" value="journal_voucher" {{ $entry->entry_type == 'journal_voucher' || $entry->entry_type == 'journal' ? 'checked' : '' }}>
                                    <div class="voucher-type-label">
                                        <div class="voucher-type-icon">
                                            <i class="fa fa-book"></i>
                                        </div>
                                        <span class="voucher-type-name">Journal</span>
                                        <span class="voucher-type-abbr">JV</span>
                                    </div>
                                </div>
                                <div class="voucher-type-card cpv">
                                    <input type="radio" name="entry_type" value="cash_payment_voucher" {{ $entry->entry_type == 'cash_payment_voucher' ? 'checked' : '' }}>
                                    <div class="voucher-type-label">
                                        <div class="voucher-type-icon">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <span class="voucher-type-name">Cash Pay</span>
                                        <span class="voucher-type-abbr">CPV</span>
                                    </div>
                                </div>
                                <div class="voucher-type-card bpv">
                                    <input type="radio" name="entry_type" value="bank_payment_voucher" {{ $entry->entry_type == 'bank_payment_voucher' ? 'checked' : '' }}>
                                    <div class="voucher-type-label">
                                        <div class="voucher-type-icon">
                                            <i class="fa fa-university"></i>
                                        </div>
                                        <span class="voucher-type-name">Bank Pay</span>
                                        <span class="voucher-type-abbr">BPV</span>
                                    </div>
                                </div>
                                <div class="voucher-type-card crv">
                                    <input type="radio" name="entry_type" value="cash_receipt_voucher" {{ $entry->entry_type == 'cash_receipt_voucher' ? 'checked' : '' }}>
                                    <div class="voucher-type-label">
                                        <div class="voucher-type-icon">
                                            <i class="fa fa-hand-o-down"></i>
                                        </div>
                                        <span class="voucher-type-name">Cash Rcpt</span>
                                        <span class="voucher-type-abbr">CRV</span>
                                    </div>
                                </div>
                                <div class="voucher-type-card brv">
                                    <input type="radio" name="entry_type" value="bank_receipt_voucher" {{ $entry->entry_type == 'bank_receipt_voucher' ? 'checked' : '' }}>
                                    <div class="voucher-type-label">
                                        <div class="voucher-type-icon">
                                            <i class="fa fa-credit-card"></i>
                                        </div>
                                        <span class="voucher-type-name">Bank Rcpt</span>
                                        <span class="voucher-type-abbr">BRV</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">
                                Description / Narration <span class="required">*</span>
                            </label>
                            <textarea name="description" class="form-textarea" rows="2" required placeholder="Enter the purpose or details of this journal entry...">{{ $entry->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Journal Lines Card --}}
        <div class="form-card" style="animation-delay: 0.2s;">
            <div class="form-card-header">
                <div class="header-icon success">
                    <i class="fa fa-list-alt"></i>
                </div>
                <div>
                    <h3 class="form-card-title">Journal Lines</h3>
                    <p class="form-card-subtitle">Debit and credit entries must be balanced</p>
                </div>
                <button type="button" class="btn-add-line ms-auto" onclick="addLine()">
                    <i class="fa fa-plus"></i>
                    Add Line
                </button>
            </div>
            <div class="form-card-body p-0">
                <div class="lines-table-wrapper" style="border: none; border-radius: 0;">
                    <table class="lines-table" id="linesTable">
                        <thead>
                            <tr>
                                <th style="width: 35%">Account Ledger</th>
                                <th style="width: 25%">Description</th>
                                <th style="width: 15%">Debit (Rs.)</th>
                                <th style="width: 15%">Credit (Rs.)</th>
                                <th style="width: 10%; text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="linesBody">
                            @foreach($entry->lines as $index => $line)
                            <tr class="line-row">
                                <td>
                                    <select name="lines[{{ $index }}][account_ledger_id]" class="line-select" required>
                                        <option value="">Select Account</option>
                                        @foreach($ledgers as $ledger)
                                            <option value="{{ $ledger->id }}" {{ $line->account_ledger_id == $ledger->id ? 'selected' : '' }}>
                                                {{ $ledger->code }} - {{ $ledger->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="lines[{{ $index }}][description]" class="line-input" value="{{ $line->description }}" placeholder="Line description">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="lines[{{ $index }}][debit]" class="line-input amount-input debit-input" value="{{ $line->debit }}" min="0" oninput="calculateTotals()">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="lines[{{ $index }}][credit]" class="line-input amount-input credit-input" value="{{ $line->credit }}" min="0" oninput="calculateTotals()">
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn-remove-line" onclick="removeLine(this)" {{ $index < 2 ? 'disabled' : '' }}>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <div class="totals-row">
                                        <div class="total-item">
                                            <span class="total-label">Total Debit:</span>
                                            <span class="total-value debit" id="totalDebit">0.00</span>
                                        </div>
                                        <div class="total-item">
                                            <span class="total-label">Total Credit:</span>
                                            <span class="total-value credit" id="totalCredit">0.00</span>
                                        </div>
                                    </div>
                                </td>
                                <td colspan="3">
                                    <div class="balance-indicator balanced" id="balanceIndicator">
                                        <div class="balance-icon">
                                            <i class="fa fa-check" id="balanceIcon"></i>
                                        </div>
                                        <span class="balance-text" id="balanceText">Entry is balanced</span>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Action Footer --}}
        <div class="action-footer">
            <div class="action-info">
                <div class="action-info-icon">
                    <i class="fa fa-info-circle"></i>
                </div>
                <div class="action-info-text">
                    <h4>Editing Draft Entry</h4>
                    <p>Changes will be saved but the entry will remain as draft until posted.</p>
                </div>
            </div>
            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fa fa-save"></i>
                Update Journal Entry
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
let lineIndex = {{ count($entry->lines) }};

function addLine() {
    const tbody = document.getElementById('linesBody');
    const newRow = document.createElement('tr');
    newRow.className = 'line-row';
    newRow.innerHTML = `
        <td>
            <select name="lines[${lineIndex}][account_ledger_id]" class="line-select" required>
                <option value="">Select Account</option>
                @foreach($ledgers as $ledger)
                    <option value="{{ $ledger->id }}">{{ $ledger->code }} - {{ $ledger->name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="text" name="lines[${lineIndex}][description]" class="line-input" placeholder="Line description">
        </td>
        <td>
            <input type="number" step="0.01" name="lines[${lineIndex}][debit]" class="line-input amount-input debit-input" value="0" min="0" oninput="calculateTotals()">
        </td>
        <td>
            <input type="number" step="0.01" name="lines[${lineIndex}][credit]" class="line-input amount-input credit-input" value="0" min="0" oninput="calculateTotals()">
        </td>
        <td style="text-align: center;">
            <button type="button" class="btn-remove-line" onclick="removeLine(this)">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(newRow);
    lineIndex++;
    
    // Animate new row
    newRow.style.animation = 'slideUp 0.3s ease forwards';
}

function removeLine(button) {
    const row = button.closest('tr');
    row.style.animation = 'fadeOut 0.2s ease forwards';
    setTimeout(() => {
        row.remove();
        calculateTotals();
    }, 200);
}

function calculateTotals() {
    let totalDebit = 0;
    let totalCredit = 0;
    
    document.querySelectorAll('.debit-input').forEach(input => {
        totalDebit += parseFloat(input.value) || 0;
    });
    
    document.querySelectorAll('.credit-input').forEach(input => {
        totalCredit += parseFloat(input.value) || 0;
    });
    
    document.getElementById('totalDebit').textContent = totalDebit.toLocaleString('en-PK', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('totalCredit').textContent = totalCredit.toLocaleString('en-PK', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    
    const difference = Math.abs(totalDebit - totalCredit);
    const indicator = document.getElementById('balanceIndicator');
    const icon = document.getElementById('balanceIcon');
    const text = document.getElementById('balanceText');
    const submitBtn = document.getElementById('submitBtn');
    
    if (difference <= 0.01 && (totalDebit > 0 || totalCredit > 0)) {
        indicator.classList.remove('unbalanced');
        indicator.classList.add('balanced');
        icon.className = 'fa fa-check';
        text.textContent = 'Entry is balanced';
        submitBtn.disabled = false;
    } else if (totalDebit === 0 && totalCredit === 0) {
        indicator.classList.remove('unbalanced');
        indicator.classList.add('balanced');
        icon.className = 'fa fa-info';
        text.textContent = 'Enter amounts';
        submitBtn.disabled = true;
    } else {
        indicator.classList.remove('balanced');
        indicator.classList.add('unbalanced');
        icon.className = 'fa fa-times';
        text.textContent = 'Difference: Rs. ' + difference.toLocaleString('en-PK', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        submitBtn.disabled = true;
    }
}

// Add fadeOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateX(-20px);
        }
    }
`;
document.head.appendChild(style);

// Calculate totals on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateTotals();
});
</script>
@endsection
