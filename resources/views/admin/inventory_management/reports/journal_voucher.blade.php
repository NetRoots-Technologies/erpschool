@extends('admin.layouts.main')

@section('title', 'Store Issuance Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title mb-0">STORE ISSUANCE REPORT | <span id="campusTitle">Select Campus</span></h4>
                    <small class="text-muted">Inventory module report</small>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-light btn-sm" id="printBtn" type="button">Print</button>
                    <button class="btn btn-light btn-sm" id="exportBtn" type="button">Export</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3 report-filter">
        <div class="card-body">
            <div class="filter-header">
                <div>
                    <div class="filter-title">Filters</div>
                    <div class="filter-subtitle">Select campus and period to generate report</div>
                </div>
                <div class="filter-actions">
                    <button type="button" id="searchBtn" class="btn btn-primary">Search</button>
                </div>
            </div>
            <div class="row g-3 align-items-end mt-1">
                <div class="col-md-4">
                    <label class="form-label">Campus</label>
                    <select class="form-control" id="branch_id">
                        <option value="">Select Campus</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Voucher Date</label>
                    <input type="date" id="voucher_date" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Voucher No</label>
                    <input type="text" id="voucher_no" class="form-control" placeholder="JV">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Remarks</label>
                    <input type="text" id="remarks" class="form-control" placeholder="Remarks">
                </div>
            </div>

            <div class="filter-divider"></div>

            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <div class="filter-toggle">
                        <label class="form-label mb-0">Display Acc. Title</label>
                        <div class="form-check form-switch switch-inline">
                            <input class="form-check-input" type="checkbox" id="display_acc" checked>
                            <label class="form-check-label switch-label" for="display_acc">Show</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Process</label>
                    <select class="form-control" id="process">
                        <option value="">All</option>
                        @foreach ($processes as $process)
                            <option value="{{ $process }}">{{ ucfirst(str_replace('_', ' ', $process)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Process From</label>
                    <input type="date" id="process_from" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Process To</label>
                    <input type="date" id="process_to" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle" id="voucherTable">
                    <thead class="table-light">
                        <tr>
                            <th>Sr</th>
                            <th class="col-nominal">Nominal A/C</th>
                            <th class="col-sub">Sub A/C</th>
                            <th>Description</th>
                            <th>Narration</th>
                            <th class="text-end">Dr</th>
                            <th class="text-end">Cr</th>
                            <th>Budget ID</th>
                            <th>Acti. ID</th>
                            <th>Cost</th>
                            <th>IBA</th>
                            <th>Loc. ID</th>
                            <th>Tax ID</th>
                            <th>Party ID</th>
                        </tr>
                    </thead>
                    <tbody id="voucherBody">
                        <tr>
                            <td colspan="14" class="text-center text-muted">No data available</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-end" id="totalDr">0.00</th>
                            <th class="text-end" id="totalCr">0.00</th>
                            <th colspan="7"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .page-title {
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .page-title {
        font-weight: 600;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        border: 1px solid #e9edf4;
    }
    .report-filter .filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-bottom: 10px;
        margin-bottom: 6px;
        border-bottom: 1px solid #eef2f7;
    }
    .report-filter .filter-title {
        font-weight: 700;
        font-size: 14px;
        color: #0f172a;
    }
    .report-filter .filter-subtitle {
        font-size: 12px;
        color: #6b7280;
    }
    .report-filter .filter-actions .btn {
        min-width: 120px;
    }
    .filter-divider {
        height: 1px;
        background: #eef2f7;
        margin: 10px 0;
    }
    .table-sm th, .table-sm td {
        padding: 0.4rem 0.5rem;
        font-size: 12px;
    }
    .form-label {
        font-size: 12px;
        font-weight: 600;
    }
    .col-nominal, .col-sub {
        min-width: 140px;
    }
    .filter-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 6px 10px;
        border: 1px solid #eef2f7;
        border-radius: 8px;
        background: #fbfcff;
        min-height: 42px;
    }
    .switch-inline {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 0;
    }
    .switch-label {
        font-size: 12px;
        color: #6b7280;
        user-select: none;
        margin: 0;
    }
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f7f9fc;
    }
    .table tbody tr:nth-child(even) {
        background: #fcfdff;
    }
    .btn-light {
        border: 1px solid #dfe6ee;
    }
    .form-control {
        border-radius: 8px;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        function formatAmount(value) {
            var num = parseFloat(value || 0);
            return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function updateCampusTitle() {
            var campus = $('#branch_id option:selected').text();
            $('#campusTitle').text(campus || '--');
        }

        function toggleAccountCols() {
            var show = $('#display_acc').is(':checked');
            $('.col-nominal, .col-sub').toggle(show);
            $('#voucherTable tbody tr').each(function () {
                $(this).find('td:nth-child(2), td:nth-child(3)').toggle(show);
            });
        }

        function loadReport() {
            $.ajax({
                url: "{{ route('inventory.reports.journal-voucher') }}",
                type: "GET",
                data: {
                    branch_id: $('#branch_id').val(),
                    voucher_date: $('#voucher_date').val(),
                    voucher_no: $('#voucher_no').val(),
                    remarks: $('#remarks').val(),
                    process: $('#process').val(),
                    process_from: $('#process_from').val(),
                    process_to: $('#process_to').val()
                },
                success: function (res) {
                    if (!res.success) {
                        $('#voucherBody').html('<tr><td colspan="14" class="text-center text-danger">No data found</td></tr>');
                        return;
                    }

                    var rows = '';
                    if (res.data.length === 0) {
                        rows = '<tr><td colspan="14" class="text-center text-muted">No data available</td></tr>';
                    } else {
                        res.data.forEach(function (row) {
                            rows += '<tr>' +
                                '<td>' + row.sr + '</td>' +
                                '<td class="col-nominal">' + row.nominal_ac + '</td>' +
                                '<td class="col-sub">' + row.sub_ac + '</td>' +
                                '<td>' + row.description + '</td>' +
                                '<td>' + row.narration + '</td>' +
                                '<td class="text-end">' + formatAmount(row.debit) + '</td>' +
                                '<td class="text-end">' + formatAmount(row.credit) + '</td>' +
                                '<td>' + row.budget_id + '</td>' +
                                '<td>' + row.acti_id + '</td>' +
                                '<td>' + row.cost + '</td>' +
                                '<td>' + row.iba + '</td>' +
                                '<td>' + row.loc_id + '</td>' +
                                '<td>' + row.tax_id + '</td>' +
                                '<td>' + row.party_id + '</td>' +
                                '</tr>';
                        });
                    }

                    $('#voucherBody').html(rows);
                    $('#totalDr').text(formatAmount(res.totals.debit));
                    $('#totalCr').text(formatAmount(res.totals.credit));

                    toggleAccountCols();
                },
                error: function () {
                    $('#voucherBody').html('<tr><td colspan="14" class="text-center text-danger">Error loading data</td></tr>');
                }
            });
        }

        $('#searchBtn').on('click', function () {
            updateCampusTitle();
            loadReport();
        });

        $('#display_acc').on('change', function () {
            toggleAccountCols();
        });

        $('#printBtn').on('click', function () {
            window.print();
        });
    });
</script>
@endsection
