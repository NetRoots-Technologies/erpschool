

<?php $__env->startSection('title', 'Income Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Income Report</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.fee-management.index')); ?>">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.fee-management.reports')); ?>">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Income Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Report</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.fee-management.reports.income')); ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="from_date" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" 
                                           value="<?php echo e($fromDate); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="to_date" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="to_date" name="to_date" 
                                           value="<?php echo e($toDate); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-search"></i> Generate Report
                                        </button>
                                        <a href="<?php echo e(route('admin.fee-management.reports.income')); ?>" class="btn btn-secondary">
                                            <i class="fa fa-refresh"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-primary"><?php echo e($collections->count()); ?></h3>
                    <p class="mb-0">Total Collections</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-success">Rs. <?php echo e(number_format($collections->sum('paid_amount'))); ?></h3>
                    <p class="mb-0">Total Collected</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-info">Rs. <?php echo e(number_format($collections->sum('total_amount'))); ?></h3>
                    <p class="mb-0">Total Amount</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3 class="text-warning"><?php echo e($collections->where('payment_method', 'cash')->count()); ?></h3>
                    <p class="mb-0">Cash Collections</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Collections Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Collection Details</h3>
                    <div class="card-options">
                        <button class="btn btn-success" onclick="exportToExcel()">
                            <i class="fa fa-file-excel-o"></i> Export Excel
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="incomeTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Total Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Collection Date</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($collection->id); ?></td>
                                    <td><?php echo e($collection->student->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($collection->academicClass->name ?? 'N/A'); ?></td>
                                    <td>Rs. <?php echo e(number_format($collection->total_amount)); ?></td>
                                    <td>Rs. <?php echo e(number_format($collection->paid_amount)); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($collection->collection_date)->format('d M Y')); ?></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $collection->payment_method))); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo e($collection->status == 'paid' ? 'success' : 'warning'); ?>">
                                            <?php echo e(ucfirst($collection->status)); ?>

                                        </span>
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
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function() {
        $('#incomeTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true
        });
    });

    function exportToExcel() {
        // Implement Excel export functionality
        toastr.info('Excel export feature will be implemented');
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/fee-management/reports/income.blade.php ENDPATH**/ ?>