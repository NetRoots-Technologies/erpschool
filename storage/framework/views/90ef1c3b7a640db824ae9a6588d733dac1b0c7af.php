

<?php $__env->startSection('title', 'Fee Collection Details'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Collection Details</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.fee-management.index')); ?>">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.fee-management.collections')); ?>">Collections</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </div>
                <div class="page-rightheader">
                    <a href="<?php echo e(route('admin.fee-management.collections')); ?>" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Collections
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary" style="color: #212529 !important;">
                    <h3 class="card-title" style="color: #212529 !important;">Collection Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Collection ID:</strong></td>
                                    <td><span class="badge badge-info">#<?php echo e($collection->id); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Student:</strong></td>
                                    <td><span class="text-primary font-weight-bold"><?php echo e($collection->student->fullname ?? 'N/A'); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Class:</strong></td>
                                    <td><span class="badge badge-secondary"><?php echo e($collection->academicClass->name ?? $collection->student->AcademicClass->name ?? 'N/A'); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Session:</strong></td>
                                    <td><span class="badge badge-info"><?php echo e($collection->academicSession->name ?? 'N/A'); ?></span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td><span class="text-success font-weight-bold">Rs. <?php echo e(number_format($collection->paid_amount ?? 0, 2)); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Paid Amount:</strong></td>
                                    <td><span class="text-success font-weight-bold">Rs. <?php echo e(number_format($collection->paid_amount ?? 0, 2)); ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <?php if($collection->status == 'paid'): ?>
                                            <span class="badge badge-success">Paid</span>
                                        <?php elseif($collection->status == 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php elseif($collection->status): ?>
                                            <span class="badge badge-info"><?php echo e(ucfirst($collection->status)); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Not Set</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Collection Date:</strong></td>
                                    <td><span class="text-info"><?php echo e($collection->collection_date ? \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') : 'N/A'); ?></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success" style="color: #212529 !important;">
                    <h3 class="card-title" style="color: #212529 !important;">Payment Details</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Payment Method:</strong></td>
                            <td><span class="badge badge-info"><?php echo e(ucfirst($collection->payment_method ?? 'N/A')); ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Reference:</strong></td>
                            <td><span class="text-dark"><?php echo e($collection->reference_number ?? 'N/A'); ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Remarks:</strong></td>
                            <td><span class="text-dark"><?php echo e($collection->remarks ?? 'N/A'); ?></span></td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td><span class="text-info"><?php echo e($collection->created_at->format('d M Y H:i')); ?></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if($collection->details && $collection->details->count() > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary" style="color: #212529 !important;">
                    <h3 class="card-title" style="color: #212529 !important;">Fee Breakdown</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $collection->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><span class="text-primary font-weight-bold"><?php echo e($detail->feeCategory->name ?? 'N/A'); ?></span></td>
                                    <td><span class="text-success font-weight-bold">Rs. <?php echo e(number_format($detail->amount, 2)); ?></span></td>
                                    <td>
                                        <?php if(isset($detail->status) && $detail->status == 'paid'): ?>
                                            <span class="badge badge-success">Paid</span>
                                        <?php elseif(isset($detail->status) && $detail->status == 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php elseif(isset($detail->status) && $detail->status): ?>
                                            <span class="badge badge-info"><?php echo e(ucfirst($detail->status)); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Paid</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th class="text-right">Total Amount:</th>
                                    <th class="text-success">Rs. <?php echo e(number_format($collection->details->sum('amount'), 2)); ?></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
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
.badge-secondary {
    background-color: #6c757d !important;
    color: #212529 !important;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/fee-management/collections/show.blade.php ENDPATH**/ ?>