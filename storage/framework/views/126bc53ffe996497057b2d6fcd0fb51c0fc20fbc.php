

<?php $__env->startSection('title', 'Fee Management Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Management Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Fee Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-primary me-3" style="color: #212529 !important;">
                            <i class="fa fa-tags"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Total Categories</h6>
                            <h3 class="mb-0"><?php echo e($data['total_categories']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-success me-3" style="color: #212529 !important;">
                            <i class="fa fa-sitemap"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Fee Structures</h6>
                            <h3 class="mb-0"><?php echo e($data['total_structures']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-info me-3" style="color: #212529 !important;">
                            <i class="fa fa-money"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Total Collected</h6>
                            <h3 class="mb-0">Rs. <?php echo e(number_format($data['total_collections'])); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-warning me-3" style="color: #212529 !important;">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Pending Amount</h6>
                            <h3 class="mb-0">Rs. <?php echo e(number_format($data['pending_amount'])); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="<?php echo e(route('admin.fee-management.categories.create')); ?>" class="btn btn-primary btn-block">
                                <i class="fa fa-plus"></i> Add Fee Category
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="<?php echo e(route('admin.fee-management.structures.create')); ?>" class="btn btn-success btn-block">
                                <i class="fa fa-sitemap"></i> Create Fee Structure
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="<?php echo e(route('admin.fee-management.collections.create')); ?>" class="btn btn-info btn-block">
                                <i class="fa fa-money"></i> Record Collection
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="<?php echo e(route('admin.fee-management.billing')); ?>" class="btn btn-warning btn-block">
                                <i class="fa fa-file-text"></i> Generate Billing
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Collections -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Fee Collections</h3>
                    <div class="card-options">
                        <a href="<?php echo e(route('admin.fee-management.collections')); ?>" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data['recent_collections'] && $data['recent_collections']->count() > 0): ?>
                                    <?php $__currentLoopData = $data['recent_collections']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($collection->student->fullname ?? 'N/A'); ?></td>
                                        <td><span class="badge badge-secondary"><?php echo e($collection->student->AcademicClass->name ?? 'N/A'); ?></span></td>
                                        <td><span class="text-success font-weight-bold">Rs. <?php echo e(number_format($collection->paid_amount, 2)); ?></span></td>
                                        <td><?php echo e($collection->collection_date ? \Carbon\Carbon::parse($collection->collection_date)->format('d M Y') : 'N/A'); ?></td>
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
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No recent collections found</td>
                                    </tr>
                                <?php endif; ?>
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
        // Dashboard initialization
        console.log('Fee Management Dashboard loaded');
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
.badge-secondary {
    background-color: #6c757d !important;
    color: #212529 !important;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/fee-management/index.blade.php ENDPATH**/ ?>