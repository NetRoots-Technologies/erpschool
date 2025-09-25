@extends('admin.layouts.main')

@section('title', 'Fee Discounts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Discounts</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Discounts</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <a href="{{ route('admin.fee-management.discounts.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add Discount
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Discounts List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="discountsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Category</th>
                                    <th>Discount Type</th>
                                    <th>Discount Value</th>
                                    <th>Valid From Month</th>
                                    <th>Valid To Month</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#discountsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.fee-management.discounts.data') }}",
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'student_name', name: 'student_name' },
                { data: 'category_name', name: 'category_name' },
                { 
                    data: 'discount_type', 
                    name: 'discount_type',
                    render: function(data, type, row) {
                        return data == 'percentage' ? 'Percentage' : 'Fixed Amount';
                    }
                },
                { 
                    data: 'discount_value', 
                    name: 'discount_value',
                    render: function(data, type, row) {
                        return row.discount_type == 'percentage' ? data + '%' : 'Rs. ' + parseFloat(data).toLocaleString();
                    }
                },
                { 
                    data: 'valid_from', 
                    name: 'valid_from',
                    render: function(data, type, row) {
                        if (data) {
                            var date = new Date(data);
                            var month = date.toLocaleDateString('en-US', { month: 'long' });
                            var year = date.getFullYear();
                            return month + ' ' + year;
                        }
                        return 'N/A';
                    }
                },
                { 
                    data: 'valid_to', 
                    name: 'valid_to',
                    render: function(data, type, row) {
                        if (data) {
                            var date = new Date(data);
                            var month = date.toLocaleDateString('en-US', { month: 'long' });
                            var year = date.getFullYear();
                            return month + ' ' + year;
                        }
                        return 'N/A';
                    }
                },
                { 
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString();
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true
        });
    });

    function deleteDiscount(id) {
        if (confirm('Are you sure you want to delete this discount?')) {
            $.ajax({
                url: "{{ route('admin.fee-management.discounts.delete', '') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#discountsTable').DataTable().ajax.reload();
                    toastr.success('Discount deleted successfully!');
                },
                error: function(xhr) {
                    toastr.error('Error deleting discount!');
                }
            });
        }
    }
</script>
@endsection
