@extends('admin.layouts.main')
@section('title', 'Chart of Accounts')

@section('content')
    <div class="container-fluid">
        <div class="card p-4">
            <div class="row">
                <div class="col-md-12">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="general-reports-tab" data-bs-toggle="tab" href="#general-reports"
                                role="tab">General Reports</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="receivables-tab" data-bs-toggle="tab" href="#receivables"
                                role="tab">Receivables and Payables</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- General Reports -->
                        <div class="tab-pane fade show active" id="general-reports" role="tabpanel"
                            aria-labelledby="general-reports-tab">
                            <div class="card p-4">
                                <h4 class="mb-3"><i class="fas fa-file-alt me-2 text-primary"></i>General Reports</h4>
                                <ul class="report-list">
                                    <li>
                                        <a href="{{ route('admin.accounts.reports.general-ledger') }}">
                                            <i class="fa-solid fa-caret-right text-primary me-2"></i>
                                             General Ledger
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.accounts.reports.subsidiary-ledger') }}">
                                            <i class="fa-solid fa-caret-right text-primary me-2"></i>
                                             Subsidry Ledger
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Receivables and Payables -->
                        <div class="tab-pane fade" id="receivables" role="tabpanel" aria-labelledby="receivables-tab">
                            <div class="card p-4">
                                <h4 class="mb-3"><i class="fas fa-exchange-alt me-2 text-primary"></i>Receivables & Payables
                                </h4>
                                <ul class="report-list">
                                    <li>
                                        <a href="#">
                                            <i class="fas fa-user-clock text-success me-2"></i> Customer Aging Report
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fas fa-user-shield text-warning me-2"></i> Supplier Balances
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fas fa-calendar-alt text-info me-2"></i> Payables Aging Report
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style type="text/css">
        .accordion-button::after {
            background: none !important;
        }

        .accordion-button {
            padding: 0.5rem 1.25rem !important;
            cursor: pointer;
        }

        .menu .accordion-heading {
            position: relative;
            border-left: 4px solid #f38787;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd !important;
            border: 1px solid #0d6efd !important;
            background-color: #eaf4ff;
            font-weight: bold;
        }

        .nav-tabs .nav-link {
            font-size: 1.1rem;
        }

        .report-list {
            list-style: none;
            padding-left: 0;
            margin-top: 1rem;
        }

        .report-list li {
            margin-bottom: 0.75rem;
        }

        .report-list li a {
            text-decoration: none;
            color: #0d6efd;
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .report-list li a i {
            font-size: 1.2rem;
        }
    </style>
@endsection