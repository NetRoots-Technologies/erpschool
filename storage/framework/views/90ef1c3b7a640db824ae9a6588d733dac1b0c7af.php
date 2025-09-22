

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
                <div class="card-header">
                    <h3 class="card-title">Collection Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Collection ID:</strong></td>
                                    <td>#<?php echo e($collection->id); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Student:</strong></td>
                                    <td><?php echo e($collection->student->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Class:</strong></td>
                                    <td><?php echo e($collection->academicClass->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Session:</strong></td>
                                    <td><?php echo e($collection->academicSession->name ?? 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Total Amount:</strong></td>
                                    <td>Rs. <?php echo e(number_format($collection->total_amount, 2)); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Paid Amount:</strong></td>
                                    <td>Rs. <?php echo e(number_format($collection->paid_amount, 2)); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <?php if($collection->status == 'paid'): ?>
                                            <span class="badge badge-success">Paid</span>
                                        <?php elseif($collection->status == 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger"><?php echo e(ucfirst($collection->status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Collection Date:</strong></td>
                                    <td><?php echo e($collection->collection_date ? \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') : 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Details</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Payment Method:</strong></td>
                            <td><?php echo e(ucfirst($collection->payment_method ?? 'N/A')); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Reference:</strong></td>
                            <td><?php echo e($collection->reference_number ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Remarks:</strong></td>
                            <td><?php echo e($collection->remarks ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td><?php echo e($collection->created_at->format('d M Y H:i')); ?></td>
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
                <div class="card-header">
                    <h3 class="card-title">Fee Breakdown</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $collection->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($detail->feeCategory->name ?? 'N/A'); ?></td>
                                    <td>Rs. <?php echo e(number_format($detail->amount, 2)); ?></td>
                                    <td>
                                        <?php if($detail->status == 'paid'): ?>
                                            <span class="badge badge-success">Paid</span>
                                        <?php elseif($detail->status == 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger"><?php echo e(ucfirst($detail->status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/fee-management/collections/show.blade.php ENDPATH**/ ?>