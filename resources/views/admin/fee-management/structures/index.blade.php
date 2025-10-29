@extends('admin.layouts.main')

@section('title', 'Fee Structures')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Structures</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Structures</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <a href="{{ route('admin.fee-management.structures.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Create Structure
                    </a>

                    <a href="{{ route('admin.fee-management.template.download.structure') }}" class="btn btn-danger">
                        <i class="fa fa-download"></i> Download Structure Template
                    </a>

                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                      <i class="fa fa-upload"></i> Import Fee Structure
                    </button>


                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Structures List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="structuresTable">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Sturcture Name</th>
                                    <th>Class</th>
                                    <th>Session</th>
                                    <th>Total Amount</th>
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ route('admin.fee-management.structure.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Excel File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Select File</label>
                            <input type="file" name="file" id="file" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>

                
            </div>
        </div>
    </div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#structuresTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.fee-management.structures.data') }}",
                type: 'GET'
            },
            columns: [
                { data: 'student_id', name: 'student_id' , searchable: true},
                { data: 'student_name', name: 'student_name' , searchable: true },
                { data: 'name', name: 'name' },
                { data: 'class_name', name: 'class_name' },
                { data: 'session_name', name: 'session_name' },
                { 
                    data: 'total_amount', 
                    name: 'total_amount',
                    render: function(data, type, row) {
                        return 'Rs. ' + parseFloat(data).toLocaleString();
                    }
                },
                { data: 'status', name: 'status' },
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

    function deleteStructure(id) {
        if (confirm('Are you sure you want to delete this structure?')) {
            $.ajax({
                url: "{{ route('admin.fee-management.structures.delete', '') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#structuresTable').DataTable().ajax.reload();
                    toastr.success('Structure deleted successfully!');
                },
                error: function(xhr) {
                    toastr.error('Error deleting structure!');
                }
            });
        }
    }
</script>
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
</style>
@endsection