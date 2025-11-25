@extends('admin.layouts.main')

@section('title', 'Fee Bills By Account')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="page-leftheader"></div>
                    <h4 class="page-title mb-0">Fee Bills By Account</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Fee Bills By Account</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Options</h3>
                </div>
                <div class="card-body">
                    <di class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="class">Category</label>
                                <select class="form-control select2" id="category_id">
                                    <option value="" selected>--Select category--</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"> {{ $cat->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="filter_month" class="form-label">Filter by Month</label>
                                <input type="month" class="form-control" id="filter_month" value="{{ date('Y-m') }}">
                                <small class="form-text text-muted">Filter will apply automatically on current month</small>
                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="class">Class</label>
                                <select class="form-control select2" id="class_id">
                                    <option value="" selected>--Select class--</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div>
                            <button type="button" id="resetFilters" class="btn btn-sm btn-info">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Fee Billing Status</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter text-nowrap mb-0" id="feeTable">
                                <thead>
                                    <tr>
                                        
                                        <th>Challan Number</th>
                                        <th>Bill Date</th>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Father Name</th>
                                        <th>Previous Outstanding</th>
                                        <th>Fee Category</th>
                                        <th>Delayed Payment Charges</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>

                                {{-- <tfoot>
                                    <td colspan="8"></td>
                                    <td colspan="3">Outstanding Amount : Rs. <span id="outstanding_amount">0</span></td>
                                </tfoot> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @section('css')
        <style>
            .badge {
                color: #212529 !important;
            }

            .badge-success {
                background-color: #28a745 !important;
                color: #212529 !important;
            }

            .badge-danger {
                background-color: #dc3545 !important;
                color: #212529 !important;
            }

            .badge-warning {
                background-color: #ffc107 !important;
                color: #212529 !important;
            }

            .badge-info {
                background-color: #17a2b8 !important;
                color: #212529 !important;
            }

            .badge-secondary {
                background-color: #6c757d !important;
                color: #212529 !important;
            }
        </style>
    @endsection

   @section('js')
<script>
    $(document).ready(function() {
        // initialize Select2 if used
        if ($.fn.select2) {
            $('#category_id').select2({ width: '100%' });
            // Remove initial selection if you prefer empty by default
            // $('#filter_month').val('');
        }

        var table = $('#feeTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.fee-management.reports.fee-bills-by-account') }}",
                data: function(d) {
                    d.category_id = $('#category_id').val();
                    d.filter_month = $('#filter_month').val();
                }
            },
            dom: 'Bfrtip',
            buttons: [
                'pageLength',
                { extend: 'copy', text: 'Copy' },
                { extend: 'csv', text: 'CSV' },
                { extend: 'excel', text: 'Excel' },
                { extend: 'pdf', text: 'PDF' },
                { extend: 'print', text: 'Print' }
            ],
            lengthMenu: [[10,25,50,-1],[10,25,50,"All"]],
            columns: [
                { data: 'challan_number', name: 'challan_number' },
                { data: 'billing_month', name: 'billing_month' },
                { data: 'student_id', name: 'student_id' },
                { data: 'student_name', name: 'student_name' },
                { data: 'father_name', name: 'father_name' },
                { data: 'previous_outstanding', name: 'previous_outstanding' },
                { data: 'category', name: 'category' },
                { data: 'fine_amount', name: 'fine_amount' },
            ],
            // optional: add index column rendering if you want
        });

        // filters
        $('#category_id').on('change', function() {
            table.ajax.reload();
        });

        $('#filter_month').on('change', function() {
            table.ajax.reload();
        });

        $('#resetFilters').on('click', function() {
            // clear selects and inputs, then reload once
            if ($.fn.select2) {
                $('#category_id').val(null).trigger('change');
            } else {
                $('#category_id').val('');
            }
            $('#filter_month').val('');
            table.ajax.reload();
        });
    });
</script>
@endsection

