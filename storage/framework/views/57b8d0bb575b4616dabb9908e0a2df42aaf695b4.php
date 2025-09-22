

<?php $__env->startSection('title', 'Fee Discounts'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Discounts</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.fee-management.index')); ?>">Fee Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Discounts</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <a href="<?php echo e(route('admin.fee-management.discounts.create')); ?>" class="btn btn-primary">
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
                                    <th>Reason</th>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function() {
        $('#discountsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(route('admin.fee-management.discounts.data')); ?>",
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
                { data: 'reason', name: 'reason' },
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

    function deleteDiscount(id) {
        if (confirm('Are you sure you want to delete this discount?')) {
            $.ajax({
                url: "<?php echo e(route('admin.fee-management.discounts.delete', '')); ?>/" + id,
                type: 'DELETE',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>"
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/fee-management/discounts/index.blade.php ENDPATH**/ ?>