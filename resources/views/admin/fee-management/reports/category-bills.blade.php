@extends('admin.layouts.main')

@section('title', 'Fee Billing Category Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader"></div>
                <h4 class="page-title mb-0">Fee Billing Category Reports</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fee Billing Category Reports</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Filter Options</h3></div>
            <div class="card-body">
                <div class="row">
                    {{-- Fee Category --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Fee Category</label>
                            <select class="form-control select2" id="category_id">
                                <option value="" selected>--Select Category--</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            
                        </div>
                          <div>
                            <button type="button" id="resetFilters" class="btn btn-sm btn-info">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Fee Billing Category Reports</h3></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-vcenter text-nowrap mb-0" id="feeReportsTable">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Bill Date</th>
                                <th>Challan Number</th>
                                <th>Fee Category</th>
                                <th>Bill Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                              <tfoot>
                            <tr>
                                <th colspan="6" class="text-right">Total</th>
                                <th id="total_bill">0</th>
                            </tr>
                        </tfoot>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.badge { color: #212529 !important; }
.badge-success { background:#28a745!important; color:#212529!important; }
.badge-danger { background:#dc3545!important; color:#212529!important; }
.badge-warning { background:#ffc107!important; color:#212529!important; }
.badge-info { background:#17a2b8!important; color:#212529!important; }
.badge-secondary { background:#6c757d!important; color:#212529!important; }
</style>
@endsection

@section('js')
<script>
$(document).ready(function() {

    var table = $('#feeReportsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.fee-management.reports.category') }}",
            data: function(d) {
                d.category_id = $('#category_id').val();
            }
        },
        dom: 'Bfrtip',
         buttons: [
                    'pageLength',
                        { extend: 'copy', footer: true },
                        { extend: 'csv', footer: true, exportOptions: { columns: ':visible', modifier: { page: 'all' } } },
                        { extend: 'excel', footer: true, exportOptions: { columns: ':visible', modifier: { page: 'all' } } },
                        { extend: 'pdf', footer: true, exportOptions: { columns: ':visible', modifier: { page: 'all' } } },
                        { extend: 'print', footer: true, exportOptions: { columns: ':visible', modifier: { page: 'all' } } }
                    ],
        lengthMenu: [[10,25,50,-1],[10,25,50,"All"]],
        columns: [
            { data: 'student_id', name: 'student_id' },
            { data: 'student_name', name: 'student_name' },
            { data: 'father_name', name: 'father_name' },
            { data: 'billing_month', name: 'billing_month' },
            { data: 'challan_number', name: 'feeCollection.billing.challan_number', defaultContent: '-' },
            { data: 'bill_category', name: 'bill_category', defaultContent: '-' },
            { data: 'total_amount', name: 'total_amount', defaultContent: '0.00' },
        ],
        order: [[3, 'desc']],

        footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            // Parse numbers properly
            var parseNumber = function(i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g,'')*1 : typeof i === 'number' ? i : 0;
            };

            // Calculate total bill
            var totalBill = api.column(6, { page: 'all' }).data().reduce((a,b) => parseNumber(a) + parseNumber(b), 0);

            // Update footer
            $(api.column(6).footer()).html(totalBill.toLocaleString());
        }
    });

    // Filter change
    $('#category_id').change(function() {
        table.ajax.reload();
    });

    // Reset filters
    $('#resetFilters').click(function () {
        $('#category_id').val('').trigger('change');
        table.ajax.reload();
    });

});

</script>
@endsection
