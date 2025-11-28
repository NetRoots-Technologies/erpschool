@extends('admin.layouts.main')

@section('title', 'Fee Bills Financial Assistance')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="page-leftheader"></div>
                    <h4 class="page-title mb-0">Fee Bills Financial Assistance</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Fee Bills Financial Assistance</li>
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
                                <label for="class">Students</label>
                                <select class="form-control select2" id="student_id">
                                    <option value="" selected>--Select Students--</option>
                                    @foreach ($students as $std)
                                        <option value="{{ $std->id }}"> {{ $std->full_name }} ({{ $std->student_id }})
                                        </option>
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
                        <h3 class="card-title">Fee Billing Financial Assistance</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-vcenter text-nowrap mb-0" id="feeTable">
                                <thead>
                                    <tr>

                                        <td>Student ID</td>
                                        <td>Student Name</td>
                                        <td>Total Fee</td>
                                        <td>Applied Discounted</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="2">Summary</td>
                                        <td>Average Discount</td>
                                        <td id="discount_avg">0%</td>
                                    </tr>
                                </tfoot>

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
                if ($.fn.select2) {
                    $('#student_id').select2({
                        width: '100%'
                    });
                }

                var table = $('#feeTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.fee-management.reports.fee-bills-by-financial-aid') }}",
                        data: function(d) {
                            d.student_id = $('#student_id').val();
                            d.filter_month = $('#filter_month').val();
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: ['pageLength', 'copy', 'csv', 'excel', 'pdf', 'print'],
                    columns: [{
                            data: 'student_id',
                            name: 'student_id'
                        },
                        {
                            data: 'student_name',
                            name: 'student_name'
                        },
                        {
                            data: 'total_amount',
                            name: 'total_amount'
                        },
                        {
                            data: 'discount_value',
                            name: 'discount_value'
                        },

                    ],
                    drawCallback: function(settings) {
                        var api = this.api();
                        var avgDiscount = api.ajax.json().average_discount;
                        $('#discount_avg').html(avgDiscount + '%');
                    }
                });


                // filters
                $('#student_id').on('change', function() {
                    table.ajax.reload();
                });

                $('#filter_month').on('change', function() {
                    table.ajax.reload();
                });

                $('#resetFilters').on('click', function() {
                    if ($.fn.select2) {
                        $('#student_id').val(null).trigger('change');
                    } else {
                        $('#category_id').val('');
                    }
                    $('#filter_month').val('');
                    table.ajax.reload();
                });
            });
        </script>
    @endsection
