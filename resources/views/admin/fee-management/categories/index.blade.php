@extends('admin.layouts.main')

@section('title', 'Fee Categories')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Categories</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Categories</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <a href="{{ route('admin.fee-management.categories.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add New Category
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Categories List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="categoriesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Mandatory</th>
                                    <th>Affects Financials</th>
                                    <th>Status</th>
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
        $('#categoriesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.fee-management.categories.data') }}",
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'type', name: 'type' },
                { 
                    data: 'is_mandatory', 
                    name: 'is_mandatory',
                    render: function(data, type, row) {
                        return data ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>';
                    }
                },
                { 
                    data: 'affects_financials', 
                    name: 'affects_financials',
                    render: function(data, type, row) {
                        return data ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>';
                    }
                },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true
        });
    });

    function deleteCategory(id) {
        if (confirm('Are you sure you want to delete this fee category?')) {
            $.ajax({
                url: "{{ route('admin.fee-management.categories.delete', '') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#categoriesTable').DataTable().ajax.reload();
                    toastr.success('Fee category deleted successfully!');
                },
                error: function(xhr) {
                    toastr.error('Error deleting fee category!');
                }
            });
        }
    }
</script>
@endsection
