

<?php $__env->startSection('title', 'Fee Collections'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Collections</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.fee-management.index')); ?>">Fee Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Collections</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <a href="<?php echo e(route('admin.fee-management.collections.create')); ?>" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Record Collection
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Collections List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="collectionsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Status</th>
                                    <th>Collection Date</th>
                                    <th>Payment Method</th>
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
        $('#collectionsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(route('admin.fee-management.collections.data')); ?>",
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'student_name', name: 'student_name' },
                { data: 'class_name', name: 'class_name' },
                { 
                    data: 'total_amount', 
                    name: 'total_amount',
                    render: function(data, type, row) {
                        return 'Rs. ' + parseFloat(data).toLocaleString();
                    }
                },
                { 
                    data: 'paid_amount', 
                    name: 'paid_amount',
                    render: function(data, type, row) {
                        return 'Rs. ' + parseFloat(data).toLocaleString();
                    }
                },
                { data: 'status', name: 'status' },
                { 
                    data: 'collection_date', 
                    name: 'collection_date',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString();
                    }
                },
                { 
                    data: 'payment_method', 
                    name: 'payment_method',
                    render: function(data, type, row) {
                        return data ? data.replace('_', ' ').toUpperCase() : 'N/A';
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/fee-management/collections/index.blade.php ENDPATH**/ ?>