
<?php $helper = app('App\Helper\Helpers'); ?>
<?php $__env->startSection('title'); ?>
Dashboard
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<style>
    #chartdiv {
        width: 100%;
        height: 400px;
    }

    .chart-heading {
        text-align: center;
        margin-top: 40px !important;
    }
</style>
<!-- row -->
<div class="row row-sm">
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-primary-gradient">
            <a href="<?php echo url('/hr/employee'); ?>">
                <div class="px-3 pt-3  pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">TOTAL EMPLOYEE</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <?php if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('main desk')): ?>
                                <h4 class="tx-20 fw-bold mb-1 text-white">
                                    <span><?php echo e($helper->getEmployeeCount()); ?></span>
                                </h4>
                                <?php endif; ?>
                                <?php if(Auth::user()->hasRole('Agent')): ?>
                                <h4 class="tx-20 fw-bold mb-1 text-white">
                                    <span><?php echo e($helper->agentsStudent()); ?></span>
                                </h4>
                                <?php endif; ?>
                            </div>
                            <span class="float-end my-auto ms-auto">
                                <i class="fas fa-arrow-circle-up text-white"></i>
                                <span class="text-white op-7"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline" class="pt-1"></span>
            </a>
        </div>
    </div>
    <?php if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('main desk')): ?>
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-danger-gradient">
            <a href="<?php echo url('/academic/student_view'); ?>">
                <div class="px-3 pt-3  pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">TOTAL STUDENTS</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 fw-bold mb-1 text-white">
                                    <span><?php echo e($helper->getStudent()); ?></span>
                                </h4>
                            </div>
                            <span class="float-end my-auto ms-auto">
                                <i class="fas fa-arrow-circle-up text-white"></i>
                                <span class="text-white op-7"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline2" class="pt-1"></span>
            </a>
        </div>
    </div>
    <?php endif; ?>
    <?php if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('main desk') ): ?>
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-success-gradient">
            <a href="">
                <div class="px-3 pt-3  pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">TOTAL REVENUE </h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 fw-bold mb-1 text-white">
                                    <span><?php echo e($helper->getAgentCounts()); ?></span>
                                </h4>
                            </div>
                            <span class="float-end my-auto ms-auto">
                                <i class="fas fa-arrow-circle-up text-white"></i>
                                <span class="text-white op-7"> </span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline3" class="pt-1"></span>
            </a>
        </div>
    </div>
    <?php endif; ?>
    <?php if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('main desk')): ?>
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-warning-gradient">
            <a href="">
                <div class="px-3 pt-3  pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">FEE DEFAULTER </h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 fw-bold mb-1 text-white">
                                    <span><?php echo e($helper->moreThan30k()); ?></span>
                                </h4>
                            </div>
                            <span class="float-end my-auto ms-auto">
                                <i class="fas fa-arrow-circle-up text-white"></i>
                                <span class="text-white op-7"> </span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline4" class="pt-1"></span>
            </a>
        </div>
    </div>
    <?php endif; ?>
    <?php if(Auth::user()->hasRole('Agent') ): ?>
    <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
        <div class="card overflow-hidden sales-card bg-warning-gradient">
            <div class="px-3 pt-3  pb-2 pt-0">
                <div class="">
                    <h6 class="mb-3 tx-12 text-white">Agent's Comission</h6>
                </div>
                <div class="pb-0 mt-0">
                    <div class="d-flex">
                        <div class="">
                            <h4 class="tx-20 fw-bold mb-1 text-white">
                                <span><?php echo e($helper->agentsComission()); ?></span>
                            </h4>
                        </div>
                        <span class="float-end my-auto ms-auto">
                            <i class="fas fa-arrow-circle-up text-white"></i>
                            <span class="text-white op-7"> </span>
                        </span>
                    </div>
                </div>
            </div>
            <span id="compositeline4" class="pt-1"></span>
        </div>
    </div>
    <?php endif; ?>
</div>
<!-- row closed -->
<?php if(Auth::user()->hasRole('Agent')): ?>
<div class="row row-sm">
    <div class="col-md-12 col-lg-12 col-xl-7">
        <div class="card">
            <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">Comission Status</h4>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
                <p class="tx-12 text-muted mb-0"></p>
            </div>
            <div class="card-body b-p-apex">
                <div class="total-revenue">
                    <div>
                        <h4><?php echo $helper->agentstotalComission(); ?></h4>
                        <label><span class="bg-primary"></span>Total Comission</label>
                    </div>
                    <div>
                        <h4><?php echo $helper->agentsComission(); ?></h4>
                        <label><span class="bg-danger"></span>Comission</label>
                    </div>
                    <div>
                        <h4><?php echo e($helper->agentsRecovery()); ?></h4>
                        <label><span class="bg-warning"></span>Recovery</label>
                    </div>
                </div>
                <div id="bar" class="sales-bar mt-4"></div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="row row-sm">
        <?php if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('main desk')): ?>
        <div class="col-md-12 col-lg-12 col-xl-8">
            <div class="card">
                <div id="chartHeading" class="chart-heading"><b>Employee Distribution by Department</b></div>
                <div id="chartdiv">
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Recent Employees</h3>
                </div>
                <div class="card-body p-0 customers mt-1">
                    <?php $__currentLoopData = $employees_name; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group list-lg-group list-group-flush">
                        <div class="list-group-item list-group-item-action" href="javascript:void(0);">
                            <div class="media mt-0">
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-0">
                                            <h5 class="mb-1 tx-15">
                                                <span>
                                                    <?php echo e(strtoupper($item->name)); ?>;
                                                </span>
                                            </h5>
                                            <p class="mb-0 tx-13 text-muted"><span>Mobile No: <a
                                                        href="#"><?php echo $item->mobile_no; ?></a>
                                                </span>
                                            </p>
                                        </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if(Auth::user()->hasRole('Agent')): ?>
        
            
                
                    
                    
                
                    
                    
                         
                            
                                
                                    
                                        
                                            
                                                
                                                        
                                                        
                                                        
                                             
                                                
                                            
                                        
                                        
                                    
                                
                            
                        
                    
                    
                
            
        <?php endif; ?>
    </div>
    <?php if(Auth::user()->hasRole('Admin')): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="justify-center text-center text-capitalize bold">
                <h4>Brief Income/Expense Summary</h4>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="px-3 pt-3  pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">TODAY INCOME</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 fw-bold mb-1 text-white">
                                    <span><?php echo e($today_fee); ?></span>
                                </h4>
                            </div>
                            <span class="float-end my-auto ms-auto">
                                <i class="fas fa-arrow-circle-up text-white"></i>
                                <span class="text-white op-7"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline5" class="pt-1"></span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="px-3 pt-3  pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">MONTH'S INCOME</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 fw-bold mb-1 text-white">
                                    <span><?php echo e($monthFee); ?></span>
                                </h4>
                            </div>
                            <span class="float-end my-auto ms-auto">
                                <i class="fas fa-arrow-circle-up text-white"></i>
                                <span class="text-white op-7"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline6" class="pt-1"></span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="px-3 pt-3  pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">TODAY'S EXPENSE</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 fw-bold mb-1 text-white">
                                    <span><?php echo e($helper->expense_summary_report_daily()); ?></span>
                                </h4>
                            </div>
                            <span class="float-end my-auto ms-auto">
                                <i class="fas fa-arrow-circle-up text-white"></i>
                                <span class="text-white op-7"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline7" class="pt-1"></span>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="px-3 pt-3  pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white">MONTH'S EXPENSE</h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 fw-bold mb-1 text-white">
                                    <span><?php echo e($helper->expense_summary_report_monthly()); ?></span>
                                </h4>
                            </div>
                            <span class="float-end my-auto ms-auto">
                                <i class="fas fa-arrow-circle-up text-white"></i>
                                <span class="text-white op-7"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <span id="compositeline8" class="pt-1"></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<!-- Chart code -->
<script defer>
    $(document).ready(function() {
            "use strict";
            const percentagePerDepartment = <?php echo json_encode($percentagePerDepartment, 15, 512) ?>;
            const data = Object.entries(percentagePerDepartment)
                .map(([department, values]) => ({
                    value: values.total_employees,
                    category: department,
                }))
                .sort((a, b) => b.value - a.value);
            console.log("🚀 ~ data>>", data.sort())
            var root = am5.Root.new("chartdiv");
            root.setThemes([
                am5themes_Animated.new(root)
            ]);
            var chart = root.container.children.push(am5percent.PieChart.new(root, {
                startAngle: 180,
                endAngle: 360,
                layout: root.verticalLayout,
                innerRadius: am5.percent(50)
            }));
            var series = chart.series.push(am5percent.PieSeries.new(root, {
                startAngle: 180,
                endAngle: 360,
                valueField: "value",
                categoryField: "category",
                alignLabels: false
            }));
            series.states.create("hidden", {
                startAngle: 180,
                endAngle: 180
            });
            series.slices.template.setAll({
                cornerRadius: 5
            });
            series.ticks.template.setAll({
                forceHidden: true
            });
            series.labels.template.setAll({
                text: "",
                textAlign: "",
                fontSize: 14
            });
            series.data.setAll(data);
            series.appear(1000, 100);
        });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\afnan\Desktop\cornerstone\resources\views/dashboard/home.blade.php ENDPATH**/ ?>