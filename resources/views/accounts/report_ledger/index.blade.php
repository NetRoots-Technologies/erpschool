{{-- resources/views/accounts/report_ledger/index.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Report Ledger')

{{-- Optional CSS for DataTables & Select2 (will be included only for this page) --}}
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet"/>
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet"/>
<style>
    /* small styling tweaks */
    table.dataTable td { vertical-align: middle; }
    .dt-center { text-align: center; }
    .dt-right  { text-align: right; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Report Ledger</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('accounts.dashboard') }}">Accounts & Finance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reports</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation & other errors --}}
    @if ($errors->any())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Filters card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form id="ledgerFilterForm" class="form-inline">
                        @csrf
                        <div class="form-row w-100">
                            <div class="form-group col-md-3">
                                <label for="start_date" class="d-block">From Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" required
                                       value="{{ old('start_date') ?? (isset($startDate) ? \Carbon\Carbon::parse($startDate)->toDateString() : '') }}">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="end_date" class="d-block">To Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" required
                                       value="{{ old('end_date') ?? (isset($endDate) ? \Carbon\Carbon::parse($endDate)->toDateString() : '') }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="account_ledger_id" class="d-block">Account</label>
                                <select name="account_ledger_id" id="account_ledger_id" class="form-control select2" style="width:100%">
                                    <option value="">-- All Accounts --</option>
                                    @if(isset($availableLedgers))
                                        @foreach($availableLedgers as $l)
                                            <option value="{{ $l->id }}"
                                                {{ (old('account_ledger_id') == $l->id) || (isset($selectedAccount) && $selectedAccount && $selectedAccount->id == $l->id) ? 'selected' : '' }}>
                                                {{ $l->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <small class="text-muted d-block">Type to search accounts.</small>
                            </div>

                            <div class="form-group col-md-2 d-flex align-items-end">
                                <button type="button" id="btnShow" class="btn btn-primary btn-block" style="margin-bottom: 16px;">Show</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- DataTable card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Ledger Records
                            @if(isset($selectedAccount) && $selectedAccount)
                                — {{ $selectedAccount->name }}
                            @endif
                        </h5>
                        <small class="text-muted">
                            @if(isset($startDate) && isset($endDate))
                                From: <strong>{{ \Carbon\Carbon::parse($startDate)->toDateString() }}</strong>
                                To: <strong>{{ \Carbon\Carbon::parse($endDate)->toDateString() }}</strong>
                            @else
                                Select date range and click Show
                            @endif
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('accounts.report_ledger') }}" class="btn btn-secondary">Reset</a>
                        <button id="btnExportCsv" class="btn btn-outline-success">Export CSV</button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ledgerTable" class="table table-sm table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:60px">#</th>
                                    <th>Date</th>
                                    <th>Journal Ref</th>
                                    <th>Account</th>
                                    <th>Description</th>
                                    <th class="text-right">Debit</th>
                                    <th class="text-right">Credit</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Totals(Debit - Credit)</th>
                                    <th id="pageTotalDebit" class="text-right">0.00</th>
                                    <th id="pageTotalCredit" class="text-right">0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<!-- Required scripts -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    // initialize select2
    if ($.fn.select2) {
        $('#account_ledger_id').select2({
            placeholder: 'Select an account',
            allowClear: true,
            width: '100%'
        });
    }

    // DataTable variable
    var table = null;

    // helper: build ajax params
    function getFilterParams() {
        return {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            account_ledger_id: $('#account_ledger_id').val()
        };
    }

    // init DataTable (called when user clicks Show)
    function initTable() {
        // validate dates
        var from = $('#start_date').val();
        var to   = $('#end_date').val();
        if (!from || !to) {
            alert('Please select both From and To dates.');
            return;
        }
        if (from > to) {
            alert('From Date cannot be later than To Date.');
            return;
        }

        // destroy existing
        if ($.fn.DataTable.isDataTable('#ledgerTable')) {
            $('#ledgerTable').DataTable().destroy();
            $('#ledgerTable').find('tbody').empty();
        }

        table = $('#ledgerTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('accounts.report_ledger.data') }}",
                data: function(d) {
                    var params = getFilterParams();
                    d.start_date = params.start_date;
                    d.end_date = params.end_date;
                    d.account_ledger_id = params.account_ledger_id;
                }
            },
            columns: [
                { data: null, orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'date_display', name: 'date_display' },
                { data: 'journal_ref', name: 'journal_ref' },
                { data: 'account_name', name: 'account_name' },
                { data: 'description', name: 'description' },
                { data: 'debit', name: 'debit', className: 'dt-right' },
                { data: 'credit', name: 'credit', className: 'dt-right' },
            ],
            order: [[1, 'asc']],
            lengthMenu: [[25,50,100],[25,50,100]],
            drawCallback: function(settings) {
                // calculate page totals
                var api = this.api();
                var pageData = api.rows({ page: 'current' }).data();
                var pageDebit = 0, pageCredit = 0;
                for (var i=0; i<pageData.length; i++) {
                    var d = parseFloat((pageData[i].debit || '0').toString().replace(/,/g,'')) || 0;
                    var c = parseFloat((pageData[i].credit || '0').toString().replace(/,/g,'')) || 0;
                    pageDebit += d;
                    pageCredit += c;
                }
                $('#pageTotalDebit').text(pageDebit.toFixed(2));
                $('#pageTotalCredit').text(pageCredit.toFixed(2));

                // optionally fetch all totals (via separate endpoint or DataTable meta)
                // here we'll ask server for totals via a quick AJAX call (same filters)
                fetchAllTotals();
            }
        });
    }

    // function to fetch all totals (server-side) - implement endpoint or reuse DataTable metadata
    function fetchAllTotals() {
        var params = getFilterParams();
        params._totals = 1; // flag to ask server for totals in same route
        $.get("{{ route('accounts.report_ledger.data') }}", params, function(response) {
            // If server returns totals directly (when _totals=1), it should include totals in JSON
            // Yajra returns DataTables JSON normally; our controller can detect _totals and return { totals: {...} }
            if (response && response.totals) {
                $('#allTotalDebit').text(Number(response.totals.debit).toFixed(2));
                $('#allTotalCredit').text(Number(response.totals.credit).toFixed(2));
            } else {
                // fallback: clear totals
                $('#allTotalDebit').text('0.00');
                $('#allTotalCredit').text('0.00');
            }
        }).fail(function() {
            $('#allTotalDebit').text('0.00');
            $('#allTotalCredit').text('0.00');
        });
    }

    // Show button click: initialize or reload table
    $('#btnShow').on('click', function(e) {
        e.preventDefault();
        if (table) {
            table.ajax.reload();
        } else {
            initTable();
        }
    });

    // Export CSV: we will open a GET to data endpoint with export flag; backend should handle _export=csv
    $('#btnExportCsv').on('click', function(e) {
        e.preventDefault();
        var params = getFilterParams();
        params._export = 'csv';
        var url = "{{ route('accounts.report_ledger.data') }}" + '?' + $.param(params);
        window.location = url;
    });

    // If page already had pre-filled dates and you want auto-load, uncomment:
    // if ($('#start_date').val() && $('#end_date').val()) { initTable(); }
});
</script>
@endsection
