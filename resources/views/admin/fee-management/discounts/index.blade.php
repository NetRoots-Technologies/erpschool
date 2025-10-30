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
                    <a href="{{ route('admin.fee-management.template.download.discount') }}" class="btn btn-danger">
                        <i class="fa fa-download"></i> Download Discount Template
                    </a>

                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
                      <i class="fa fa-upload"></i> Import Fee Discount
                    </button>


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
                                    <th>Student ID</th>
                                    <th>Student</th>
                                    <th>Category</th>
                                    <th>Discount Type</th>
                                    <th>Discount Value</th>
                                    <th>Valid From Month</th>
                                    <th>Valid To Month</th>
                                    <th>Show on Voucher</th>
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

                <form action="{{ route('admin.fee-management.discount.import') }}" method="POST" enctype="multipart/form-data">
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
        $('#discountsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.fee-management.discounts.data') }}",
                type: 'GET'
            },
            columns: [
                { data: 'student_id', name: 'student_id' , searchable: true},
                { data: 'student_name', name: 'student_name', searchable: true},
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
                    data: 'ShowOnVoucher',
                    name: 'show_on_voucher',
                    render: function(data, type, row) {
                        console.log(row);
                        if (row.show_on_voucher) {
                            return '<span style="display: inline-block; padding: 2px 6px; font-size: 12px; color: white; background-color: green; border-radius: 4px;">Yes</span>';
                        } else {
                            return '<span style="display: inline-block; padding: 2px 6px; font-size: 12px; color: white; background-color: gray; border-radius: 4px;">No</span>';
                        }
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
