<style>
    .slide-item.active {
        background-color: #0b5bc9;
        color: white !important;
    }

    .app-sidebar a.slide-menu_item.slide-change a:before {
        display: none;
    }

    .icon-style {
        font-size: 18px !important;
    }

    .circular-logo {
        margin-top: -15px !important;
        height: 60px !important;
        width: 100px;
        border-radius: 50%;
        object-fit: cover;
    }

    .logo-icon img {
        margin-right: 200px !important;
    }

    .side-style {
        height: 97px !important;
        padding: 31px 20px !important;
    }

    .user-style {
        padding: 42px 0 !important;
    }

    .main-logo {
        height: 76px !important;
        margin-top: -22px !important;
    }
</style>
<?php $__env->startSection('css'); ?>
<?php $__env->stopSection(); ?>
<?php
    $company = \App\Models\Admin\Company::where('status', 1)->first();
    $logoUrl = $company ? asset($company->logo) : 'https://www.netrootstech.com/wp-content/uploads/2022/08/Netroots-logo-tm-transparent.png';
?>

<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<div class="sticky">
    <aside class="app-sidebar sidebar-scroll">
        <div class="main-sidebar-header active side-style">
            <a class="desktop-logo logo-light active circular-logo" href="<?php echo e(route('dashboard')); ?>">
                <img src="<?php echo e($logoUrl); ?>" style="height: 100px; margin-top: -35px;" class="main-logo" alt="logo">
            </a>
            <a class="desktop-logo logo-dark" href="<?php echo e(route('dashboard')); ?>">
                <img src="<?php echo e($logoUrl); ?>" style="height: 100px; margin-top: -35px;" class="main-logo" alt="logo">
            </a>
            <a class="logo-icon mobile-logo icon-light active circular-logo" href="<?php echo e(route('dashboard')); ?>">
                <img src="<?php echo e(asset('logos/CSS_logo_mobile.png')); ?>" alt="logo">
            </a>
            <a class="logo-icon mobile-logo icon-dark circular-logo" href="<?php echo e(route('dashboard')); ?>">
                <img src="<?php echo e(asset('logos/CSS_logo_mobile.png')); ?>" alt="logo">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="app-sidebar__user clearfix">
                <div class="dropdown user-pro-body user-style">
                    <div>
                        <img alt="user-img" class="avatar avatar-xl brround"
                            src="<?php echo e(asset('dist/assets/img/faces/6.png')); ?>">
                        <span class="avatar-status profile-status bg-green"></span>
                    </div>
                    <div class="user-info">
                        <h4 class="fw-semibold mt-3 mb-0"><?php echo e(auth()->user()->name ?? ''); ?></h4>
                        <span class="mb-0 text-muted"><?php echo e(auth()->user()->getRoleNames()[0] ?? ''); ?></span>
                    </div>
                </div>
            </div>
            <div class="slide-left disabled" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>
            <ul class="side-menu">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Dashboard')): ?>
                    <li class="side-item side-item-category">Main</li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo e(request()->is('dashboard*') ? 'active' : ''); ?>"
                            href="<?php echo e(route('dashboard')); ?>">
                            <i class="fa fa-tachometer icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Company', 'Branches', 'Category', 'Departments', 'FinancialYears', 'Designations', 'SignatoryAuthorities'])): ?>
                    <li class="side-item side-item-category">Academic</li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Company')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('admin/company*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-building icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Company</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: <?php echo e(request()->is('admin/company*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('admin/company') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.company.index')); ?>">Company</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Branches')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('admin/branches*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-code-branch icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Branches</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: <?php echo e(request()->is('admin/branches*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->routeIs('admin.branches.index') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.branches.index')); ?>">Branches</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Category')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('admin/category*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-list icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Category</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: <?php echo e(request()->is('admin/category*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('admin/category*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.category.index')); ?>">Category</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Departments')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('admin/departments*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-briefcase icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Departments</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('admin/departments*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('admin/departments*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.departments.index')); ?>">Departments</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('FinancialYears')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('admin/financial-years*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-calendar-minus-o icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Financial Years</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('admin/financial-years*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('admin/financial-years*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.financial-years.index')); ?>">Financial Years</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Designations')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('hr/designation*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-briefcase icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Designations</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: <?php echo e(request()->is('hr/designation*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('hr/designation*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.designations.index')); ?>">Designations</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('SignatoryAuthorities')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('admin/signatory-authorities*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-briefcase icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Signatory Authorities</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('admin/signatory-authorities*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('admin/signatory-authorities*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.signatory-authorities.index')); ?>">Signatory Authorities</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['AcademicSession', 'SchoolType', 'Class', 'ActiveSessions', 'Section', 'Subjects'])): ?>
                    <li class="side-item side-item-category">Academic Management</li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['AcademicSession', 'SchoolType', 'Class', 'ActiveSessions', 'Section', 'Subjects'])): ?>
                        <li class="slide">
                            <a class="side-menu__item slide-change <?php echo e(request()->is('academic/academic-session*', 'academic/active_sessions*', 'academic/schools*', 'academic/classes*', 'academic/sections*', 'academic/subjects*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-users icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Academic Session</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('academic/academic-session*', 'academic/active_sessions*', 'academic/schools*', 'academic/classes*', 'academic/sections*', 'academic/subjects*') ? 'block' : 'none'); ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('AcademicSession')): ?>
                                    <li><a class="slide-item <?php echo e(request()->is('academic/academic-session*') ? 'active' : ''); ?>"
                                            href="<?php echo e(route('academic.academic-session.index')); ?>">Academic Session</a></li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('SchoolType')): ?>
                                    <li><a class="slide-item <?php echo e(request()->is('academic/schools*') ? 'active' : ''); ?>"
                                            href="<?php echo e(route('academic.schools.index')); ?>">School Type</a></li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Class')): ?>
                                    <li><a class="slide-item <?php echo e(request()->is('academic/classes*') ? 'active' : ''); ?>"
                                            href="<?php echo e(route('academic.classes.index')); ?>">Class</a></li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ActiveSessions')): ?>
                                    <li><a class="slide-item <?php echo e(request()->is('academic/active_sessions*') ? 'active' : ''); ?>"
                                            href="<?php echo e(route('academic.active_sessions.index')); ?>">Active Sessions</a></li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Section')): ?>
                                    <li><a class="slide-item <?php echo e(request()->is('academic/sections*') ? 'active' : ''); ?>"
                                            href="<?php echo e(route('academic.sections.index')); ?>">Section</a></li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Subjects')): ?>
                                    <li><a class="slide-item <?php echo e(request()->is('academic/subjects*') ? 'active' : ''); ?>"
                                            href="<?php echo e(route('academic.subjects.index')); ?>">Subjects</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('AcademicManagement')): ?>
                        <li class="slide">
                            <a class="side-menu__item slide-change <?php echo e(request()->is('academic/student-class_adjustment*', 'academic/studentDataBank*', 'academic/students*', 'academic/students_form*', 'academic/students_details*', 'academic/student-siblings-report*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-graduation-cap icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Admission Management</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('academic/student-class_adjustment*', 'academic/studentDataBank*', 'academic/students*', 'academic/students_form*', 'academic/students_details*', 'academic/student-siblings-report*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('academic/studentDataBank*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.studentDataBank.index')); ?>">Pre-Admission Form</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/students*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.students.index')); ?>">Students</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/student-siblings-report*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.student-siblings-report')); ?>">Student Siblings Report</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/student-class_adjustment*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.student-class-adjustment.create')); ?>">Student Class
                                        Promotion</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('AttendanceManagement')): ?>
                        <li class="slide">
                            <a class="side-menu__item slide-change <?php echo e(request()->is('academic/student_attendance*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-calendar icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Attendance Management</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('academic/student_attendance*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('academic/student_attendance/create') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.student_attendance.create')); ?>">Student Attendance</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/student_attendance') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.student_attendance.index')); ?>">Attendance Report</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('StudentManagement')): ?>
                        <li class="slide">
                            <a class="side-menu__item slide-change <?php echo e(request()->is('academic/student_view*', 'academic/assign_timetable*', 'academic/assign_class*', 'academic/subject-type*', 'academic/class_timetable*', 'academic/teachers*', 'academic/timetables*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-child icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Student Management</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('academic/student_view*', 'academic/assign_timetable*', 'academic/assign_class*', 'academic/subject-type*', 'academic/class_timetable*', 'academic/teachers*', 'academic/timetables*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('academic/assign_class*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.assign_class.index')); ?>">Assign Class & Section</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/student_view*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.student_view.index')); ?>">View Students</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/timetables*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.timetables.index')); ?>">Timetables</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/subject-type*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.subject-type.index')); ?>">Subject Type</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/class_timetable*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.class_timetable.index')); ?>">Class Timetable</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/assign_timetable*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.assign_timetable.index')); ?>">Assign Timetable</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('academic/teachers*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('academic.teachers.index')); ?>">Teachers</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                

                   
                <li class="side-item side-item-category">HR Management</li>
                <li class="slide ">
                    <a class="side-menu__item <?php echo e(request()->is('hr/employee') ? 'active' : ''); ?>" data-bs-toggle="slide"
                        href="javascript:void(0);">
                        <i class="fa fa-user-circle-o icons8 icon-style" aria-hidden="true"></i>
                        <span class="side-menu__label">Employees</span>
                        <i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu" style="display: <?php echo e(request()->is('hr/employee') ? 'block' : 'none'); ?>">
                        <li><a class="slide-item  <?php echo e(request()->is('hr/employee') ? 'active' : ''); ?>"
                                href="<?php echo e(route('hr.employee.index')); ?>">Employees </a></li>
                    </ul>
                </li>
            
            
                <li class="slide ">
                    <a class="side-menu__item <?php echo e(request()->is('hr/work_shifts*') ? 'active' : ''); ?>" data-bs-toggle="slide"
                        href="javascript:void(0);">
                        <i class="fa fa-clock-o icons8 icon-style" aria-hidden="true"></i>
                        <span class="side-menu__label">Work Shift</span>
                        <i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu" style="display: <?php echo e(request()->is('hr/work_shifts*') ? 'block' : 'none'); ?>">
                        <li><a class="slide-item <?php echo e(request()->is('hr/work_shifts*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('hr.work_shifts.index')); ?>">Work Shift</a></li>
                    </ul>
                </li>
            
            
                <li class="slide ">
                    <a class="side-menu__item <?php echo e(request()->is('hr/attendance*') ? 'active' : ''); ?>" data-bs-toggle="slide"
                        href="javascript:void(0);">
                        <i class="fa fa-calendar icons8 icon-style" aria-hidden="true"></i>
                        <span class="side-menu__label">Attendance</span>
                        <i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu" style="display: <?php echo e(request()->is('hr/attendance*') ? 'block' : 'none'); ?>">
                        <li><a class="slide-item <?php echo e(request()->is('hr/attendance') ? 'active' : ''); ?>"
                                href="<?php echo e(route('hr.attendance.index')); ?>">Employee Attendance</a></li>
                        <li><a class="slide-item <?php echo e(request()->is('hr/attendance/dashboard') ? 'active' : ''); ?>"
                                href="<?php echo e(route('hr.attendanceDashboard.dashboard')); ?>">Attendance Dashboard</a>
                        </li>
                        <li>
                            <a class="slide-item <?php echo e(request()->is('hr/attendance/detail') ? 'active' : ''); ?>"
                                href="<?php echo e(route('hr.attendance.detail')); ?>">Attendance Report</a>
                        </li>
                    </ul>
                </li>
            
            
                <li class="slide ">
                    <a class="side-menu__item <?php echo e(request()->is('hr/payroll*', 'hr/salary_slip') ? 'active' : ''); ?>"
                        data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="fa fa-money icons8 icon-style" aria-hidden="true"></i>
                        <span class="side-menu__label">PayRoll</span>
                        <i class="angle fe fe-chevron-down"></i></a>
                    <ul class="slide-menu" style="display: <?php echo e(request()->is('hr/payroll*') ? 'block' : 'none'); ?>">
                        
                            <li><a class="slide-item <?php echo e(request()->is('hr/payroll') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('hr.payroll.index')); ?>">PayRoll</a></li>
                        
                            <li><a class="slide-item <?php echo e(request()->is('hr/payroll_approve') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('hr.payroll.approve')); ?>">PayRoll Approve</a></li>
                        
                            <li><a class="slide-item <?php echo e(request()->is('hr/salary_slip') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('hr.salary_slip.index')); ?>">PayRoll Slip</a></li>
                        
                            <li><a class="slide-item <?php echo e(request()->is('hr/payroll_report') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('hr.payroll.report')); ?>">PayRoll Report</a></li>
                        
                    </ul>
                </li>
            
                <li class="slide">
                    <a class="side-menu__item <?php echo e(request()->is('hr/salary-tax*') ? 'active' : ''); ?>" data-bs-toggle="slide"
                        href="javascript:void(0);">
                        <i class="fa fa-chart-bar icons8 icon-style" aria-hidden="true"></i>
                        <span class="side-menu__label ">Tax Slab</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu" style="display: <?php echo e(request()->is('hr/salary-tax*') ? 'block' : 'none'); ?>">
                        
                        <li><a class="slide-item <?php echo e(request()->is('hr/salary-tax') ? 'active' : ''); ?>"
                                href="<?php echo e(route('hr.salary-tax.index')); ?>">Salary Tax Slab</a></li>
                    </ul>
                </li>
            
            <li class="slide">
                <a class="side-menu__item <?php echo e(request()->is('hr/overtime*') ? 'active' : ''); ?>" data-bs-toggle="slide"
                    href="javascript:void(0);">
                    <i class="fa fa-clock icons8 icon-style" aria-hidden="true"></i>
                    <span class="side-menu__label ">Overtime</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu" style="display: <?php echo e(request()->is('hr/salary-tax*') ? 'block' : 'none'); ?>">
                    
                    <li><a class="slide-item <?php echo e(request()->is('hr/overtime') ? 'active' : ''); ?>"
                            href="<?php echo e(route('hr.overtime.index')); ?>">Overtime</a></li>
                </ul>
            </li>

                
                    <li class="side-item side-item-category">HR Reports</li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo e(request()->is('hr/overtime-report*', 'hr/employeeBenefit/*') ? 'active' : ''); ?>"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fas fa-dollar icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Reports</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: <?php echo e(request()->is('hr/overtime-report*', 'hr/employeeBenefit/*' , 'reports/student/exam/*') ? 'block' : 'none'); ?>">

                           <li>
                                <a class="slide-item <?php echo e(request()->is('reports/student/exam') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('reports.std.exam.index')); ?>">
                                    Exam Report
                                </a>
                            </li>


                            <li><a class="slide-item <?php echo e(request()->is('hr/overtime-report-view') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('hr.overtime-report-view')); ?>">Overtime</a></li>
                            <li><a class="slide-item <?php echo e(request()->is('hr/employeeBenefit/EOBI') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('hr.employeeBenefit', ['EOBI'])); ?>">EOBI</a></li>
                            <li><a class="slide-item <?php echo e(request()->is('hr/employeeBenefit/PF') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('hr.employeeBenefit', ['PF'])); ?>">Provident Fund</a></li>
                            <li><a class="slide-item <?php echo e(request()->is('hr/employeeBenefit/SS') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('hr.employeeBenefit', ['SS'])); ?>">Social Security</a></li>
                        </ul>
                    </li>
                

               



                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['ExamTerms-list', 'TestTypes-list', 'ExamDetails-list', 'Components-list', 'SubComponents-list', 'Skills-list', 'SkillEvaluationsKey-list', 'SkillEvaluation-list', 'Behaviours-list', 'EffortLevels-list', 'GradingPolicies-list', 'AcademicEvaluationsKey-list', 'SkillGroups-list', 'SkillTypes-list', 'ExamSchedules-list', 'MarksInput-list'])): ?>
                    <li class="side-item side-item-category">Exam</li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo e(request()->is('exam/exam_terms*') || request()->is('exam/test_types*') || request()->is('exam/exam_details*') || request()->is('exam/components*') || request()->is('exam/skill_evaluations_key*') || request()->is('exam/skill_evaluation*') || request()->is('exam/academic_evaluations_key*') || request()->is('exam/skill_groups*') || request()->is('exam/class_subjects*') || request()->is('exam/skill_types*') || request()->is('exam/exam_schedules*') || request()->is('exam/sub_components*') || request()->is('exam/skills*') || request()->is('exam/behaviours*') || request()->is('exam/effort_levels*') || request()->is('exam/grading_policies*') || request()->is('exam/marks_input*') ? 'active' : ''); ?>"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-file icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Exam</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: <?php echo e(request()->is('exam/exam_terms*') || request()->is('exam/test_types*') || request()->is('exam/exam_details*') || request()->is('exam/components*') || request()->is('exam/skill_evaluations_key*') || request()->is('exam/skill_evaluation*') || request()->is('exam/academic_evaluations_key*') || request()->is('exam/skill_groups*') || request()->is('exam/class_subjects*') || request()->is('exam/skill_types*') || request()->is('exam/exam_schedules*') || request()->is('exam/sub_components*') || request()->is('exam/skills*') || request()->is('exam/behaviours*') || request()->is('exam/effort_levels*') || request()->is('exam/grading_policies*') || request()->is('exam/marks_input*') ? 'block' : 'none'); ?>">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ExamTerms-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/exam_terms*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.exam_terms.index')); ?>">Exam Term</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('TestTypes-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/test_types*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.test_types.index')); ?>">Test Type</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ExamDetails-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/exam_details*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.exam_details.index')); ?>">Exam Detail</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Components-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/components*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.components.index')); ?>">Components</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('SubComponents-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/sub_components*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.sub_components.index')); ?>">Sub Component</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Skills-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/skills*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.skills.index')); ?>">Skills</a></li>
                            <?php endif; ?>
                              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('SkillGroups-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/skill_groups*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.skill_groups.index')); ?>">Skill Group</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('SkillTypes-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/skill_types*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.skill_types.index')); ?>">Skill Type</a></li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('SkillEvaluationsKey-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/skill_evaluations_key*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.skill_evaluations_key.index')); ?>">Skill Evaluation Key</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('SkillEvaluation-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/skill_evaluation*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.skill_evaluation.index')); ?>">Skill Evaluation</a></li>
                            <?php endif; ?>
                            
                             <li><a class="slide-item <?php echo e(request()->is('exam/class_subjects*') ? 'active' : ''); ?>"
                            href="<?php echo e(route('exam.class_subjects.index')); ?>">Class Subjects</a></li>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('EffortLevels-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/effort_levels*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.effort_levels.index')); ?>">Effort Levels</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('GradingPolicies-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/grading_policies*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.grading_policies.index')); ?>">Grading Policies</a></li>
                            <?php endif; ?>
                            
                              

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ExamSchedules-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/exam_schedules*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.exam_schedules.index')); ?>">Exam Schedules</a></li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('MarksInput-list')): ?>
                                <li><a class="slide-item <?php echo e(request()->is('exam/marks_input*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('exam.marks_input.index')); ?>">Marks Input</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                
                <li class="side-item side-item-category">Fee Management</li>
                <li class="slide">
                    <a class="side-menu__item <?php echo e(request()->is('admin/fee-management*') ? 'active' : ''); ?>"
                        data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="fa fa-money icons8 icon-style" aria-hidden="true"></i>
                        <span class="side-menu__label">Fee Management</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu" style="display: <?php echo e(request()->is('admin/fee-management*') ? 'block' : 'none'); ?>">
                        <li><a class="slide-item <?php echo e(request()->is('admin/fee-management') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.fee-management.index')); ?>">Dashboard</a></li>
                        <li><a class="slide-item <?php echo e(request()->is('admin/fee-management/categories*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.fee-management.categories')); ?>">Fee Categories</a></li>
                        <li><a class="slide-item <?php echo e(request()->is('admin/fee-management/structures*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.fee-management.structures')); ?>">Fee Structures</a></li>
                        <li><a class="slide-item <?php echo e(request()->is('admin/fee-management/collections*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.fee-management.collections')); ?>">Fee Collections</a></li>
                        <li><a class="slide-item <?php echo e(request()->is('admin/fee-management/discounts*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.fee-management.discounts')); ?>">Fee Discounts</a></li>
                        <li><a class="slide-item <?php echo e(request()->is('admin/fee-management/billing*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.fee-management.billing')); ?>">Fee Billing</a></li>
                        <li><a class="slide-item <?php echo e(request()->is('admin/fee-management/reports*') ? 'active' : ''); ?>"
                                href="<?php echo e(route('admin.fee-management.reports')); ?>">Reports</a></li>
                    </ul>
                </li>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Accounts', 'AccountReports', 'Assets'])): ?>
                    <li class="side-item side-item-category">Accounts Management</li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Accounts')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('admin/ledger_tree*', 'admin/report-center*', 'admin/entries*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-calculator icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Accounts</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('admin/ledger_tree*', 'admin/report-center*', 'admin/entries*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('admin/ledger_tree*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.accounts.chart_of_accounts.ledger_tree')); ?>">Chart of Accounts</a>
                                </li>
                                <li><a class="slide-item <?php echo e(request()->is('admin/report-center*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.accounts.reports')); ?>">Report Center</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('admin/entries*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('admin.entries.index')); ?>">Journal Entry</a></li>
                               
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('AccountReports')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('trial-balance-report*', 'balance-sheet*', 'profit-loss*', 'chart-of-accounts*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-tachometer icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Account Reports</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('trial-balance-report*', 'balance-sheet*', 'profit-loss*', 'chart-of-accounts*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('trial-balance-report') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('trial_balance_report')); ?>">Trial Balance</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('balance-sheet') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('balance_sheet')); ?>">Balance Sheet</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('profit-loss') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('profit_loss')); ?>">Profit & Loss Statement</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Assets')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('hr/asset_type*', 'hr/asset*', 'asset/bulk*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-tachometer icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Assets</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('hr/asset_type*', 'hr/asset*', 'asset/bulk*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('hr/asset_type*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.asset_type.index')); ?>">Assets Type</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('hr/asset*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.asset.index')); ?>">Assets</a></li>
                                <li><a class="slide-item <?php echo e(request()->is('asset/bulk*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('asset-bulk')); ?>">Assets Bulk</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['EmployeeWelfare', 'EOBI', 'ProfitFunds', 'SocialSecurity'])): ?>
                    <li class="side-item side-item-category">Funds</li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('EmployeeWelfare')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('hr/employee-welfare*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-shield icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Employee Welfare</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('hr/employee-welfare*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('hr/employee-welfare*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.employee-welfare.index')); ?>">Employee Welfare</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('EOBI')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('hr/eobis*') ? 'active' : ''); ?>" data-bs-toggle="slide"
                                href="javascript:void(0);">
                                <i class="fa fa-handshake-o icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">EOBI</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: <?php echo e(request()->is('hr/eobis*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('hr/eobis*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.eobis.index')); ?>">EOBI</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ProfitFunds')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('hr/profit-funds*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-sitemap icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Provident Fund</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: <?php echo e(request()->is('hr/profit-funds*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('hr/profit-funds*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.profit-funds.index')); ?>">Provident Fund</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('SocialSecurity')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->is('hr/social-security*') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-id-badge icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Social Security</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->is('hr/social-security*') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->is('hr/social-security*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.social-security.index')); ?>">Social Security</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                
                    <li class="side-item side-item-category ">Leave Management</li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo e(request()->is('hr/qouta_sections*') || request()->is('hr/holidays*') || request()->is('hr/leave_requests*') || request()->is('hr/manage_leaves*') ? 'active' : ''); ?>"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-pencil icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Leave Settings</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu "
                            style="display: <?php echo e(request()->is('hr/qouta_sections*') || request()->is('hr/holidays*') || request()->is('hr/leave_requests*') || request()->is('hr/manage_leaves*') ? 'block' : 'none'); ?>">
                            
                                <li><a class="slide-item <?php echo e(request()->is('hr/qouta_sections*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.qouta_sections.index')); ?>">Permanent Employees Quota</a>
                                </li>
                            
                            
                                <li><a class="slide-item <?php echo e(request()->is('hr/holidays*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.holidays.index')); ?>">Gazetted Holidays</a></li>
                            
                            <li><a class="slide-item <?php echo e(request()->is('hr/leave_requests*') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('hr.leave_requests.index')); ?>">Leave Request</a></li>
                            
                                <li><a class="slide-item <?php echo e(request()->is('hr/manage_leaves*') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('hr.manage_leaves.index')); ?>">Manage Leaves</a></li>
                            
                        </ul>
                    </li>
                

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Budget', 'InventoryCategory', 'Vendor', 'VendorCategory', 'CafeInventory', 'StationeryInventory', 'POS'])): ?>
                    <li class="side-item side-item-category">Inventory Management</li>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Budget')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->routeIs('inventory.budget.index') ? 'active' : ''); ?>"
                                href="<?php echo e(route('inventory.budget.index')); ?>">
                                <i class="fas fa-wallet icons8 icon-style me-3"></i>
                                <span class="side-menu__label">Budget</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('InventoryCategory')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->routeIs('inventory.category.index') ? 'active' : ''); ?>"
                                href="<?php echo e(route('inventory.category.index')); ?>">
                                <i class="fas fa-tags icons8 icon-style me-3"></i>
                                <span class="side-menu__label">Category</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Vendor')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->routeIs('inventory.vendor-management.index') ? 'active' : ''); ?>"
                                href="<?php echo e(route('inventory.vendor-management.index')); ?>">
                                <i class="fas fa-users icons8 icon-style me-3"></i>
                                <span class="side-menu__label">Vendors</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('VendorCategory')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->routeIs('inventory.vendor-category.index') ? 'active' : ''); ?>"
                                href="<?php echo e(route('inventory.vendor-category.index')); ?>">
                                <i class="fas fa-users icons8 icon-style me-3"></i>
                                <span class="side-menu__label">Vendor Categories</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('CafeInventory')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->routeIs('inventory.items.index', 'inventory.suppliers.index', 'inventory.requisitions.index', 'inventory.quotes.index', 'inventory.requisitions.approval', 'inventory.purchase_order.index', 'inventory.inventry.index', 'inventory.product.index', 'inventory.product.productCompleted', 'inventory.school_lunch.school_lunch', 'inventory.school_lunch.view', 'inventory.staff_lunch.emp_index', 'inventory.staff_lunch.emp_view', 'inventory.grn') && in_array(request()->query('type'), ['food']) ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fas fa-box icons8 icon-style me-3"></i>
                                <span class="side-menu__label">Cafe Inventory</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->routeIs('inventory.items.index', 'inventory.suppliers.index', 'inventory.requisitions.index', 'inventory.quotes.index', 'inventory.requisitions.approval', 'inventory.purchase_order.index', 'inventory.inventry.index', 'inventory.product.index', 'inventory.product.productCompleted', 'inventory.school_lunch.school_lunch', 'inventory.school_lunch.view', 'inventory.staff_lunch.emp_index', 'inventory.staff_lunch.emp_view', 'inventory.grn') && in_array(request()->query('type'), ['food']) ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.items.index') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.items.index', ['type' => 'food'])); ?>">Raw Material & Items</a>
                                </li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.suppliers.index') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.suppliers.index', ['type' => 'food'])); ?>">Suppliers</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.requisitions.index') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.requisitions.index', ['type' => 'food'])); ?>">Requisitions</a>
                                </li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.requisitions.approval') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.requisitions.approval', ['type' => 'food'])); ?>">Requisition
                                        Approval</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.quotes.index') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.quotes.index', ['type' => 'food'])); ?>">Quotes</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.purchase_order.index') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.purchase_order.index', ['type' => 'food'])); ?>">Purchase
                                        Order</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.grn') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.grn', ['type' => 'food'])); ?>">GRN</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.inventry.index') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.inventry.index', ['type' => 'food'])); ?>">Inventory</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.product.index') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.product.index', ['type' => 'food'])); ?>">Products</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.product.productCompleted') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.product.productCompleted')); ?>">Completed Goods</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.school_lunch.school_lunch') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.school_lunch.school_lunch')); ?>">Student Meal</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.school_lunch.view', 'inventory.school_lunch.get_assigned_student') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.school_lunch.view')); ?>">Student Meal Assigned</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.staff_lunch.emp_index') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.staff_lunch.emp_index')); ?>">Staff Meal</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.staff_lunch.emp_view') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.staff_lunch.emp_view')); ?>">Staff Meal Assigned</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('StationeryInventory')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->routeIs('inventory.items.index', 'inventory.suppliers.index', 'inventory.requisitions.index', 'inventory.quotes.index', 'inventory.requisitions.approval', 'inventory.purchase_order.index', 'inventory.inventry.index', 'inventory.product.index', 'inventory.grn') && in_array(request()->query('type'), ['stationary']) ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fas fa-warehouse icons8 icon-style me-3"></i>
                                <span class="side-menu__label">Stationery Inventory</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->routeIs('inventory.items.index', 'inventory.suppliers.index', 'inventory.requisitions.index', 'inventory.quotes.index', 'inventory.requisitions.approval', 'inventory.purchase_order.index', 'inventory.inventry.index', 'inventory.product.index', 'inventory.grn') && in_array(request()->query('type'), ['stationary']) ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.items.index') && request()->query('type') === 'stationary' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.items.index', ['type' => 'stationary'])); ?>">Items</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.suppliers.index') && request()->query('type') === 'stationary' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.suppliers.index', ['type' => 'stationary'])); ?>">Suppliers</a>
                                </li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.requisitions.index') && request()->query('type') === 'stationary' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.requisitions.index', ['type' => 'stationary'])); ?>">Requisitions</a>
                                </li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.requisitions.approval') && request()->query('type') === 'stationary' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.requisitions.approval', ['type' => 'stationary'])); ?>">Requisition
                                        Approval</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.quotes.index') && request()->query('type') === 'stationary' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.quotes.index', ['type' => 'stationary'])); ?>">Quotes</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.purchase_order.index') && request()->query('type') === 'stationary' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.purchase_order.index', ['type' => 'stationary'])); ?>">Purchase
                                        Order</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.grn') && request()->query('type') === 'stationary' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.grn', ['type' => 'stationary'])); ?>">GRN</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.inventry.index') && request()->query('type') === 'stationary' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.inventry.index', ['type' => 'stationary'])); ?>">Store
                                        Inventory</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.product.index') && request()->query('type') === 'stationary' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.product.index', ['type' => 'stationary'])); ?>">Bundles</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('POS')): ?>
                        <li class="slide">
                            <a class="side-menu__item <?php echo e(request()->routeIs('inventory.pos.view') ? 'active' : ''); ?>"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fas fa-cart-arrow-down icons8 icon-style me-3"></i>
                                <span class="side-menu__label">POS</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: <?php echo e(request()->routeIs('inventory.pos.view') ? 'block' : 'none'); ?>">
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.pos.view') && request()->query('type') === 'food' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.pos.view', ['type' => 'food'])); ?>">POS (Food)</a></li>
                                <li><a class="slide-item <?php echo e(request()->routeIs('inventory.pos.view') && request()->query('type') === 'uniform' ? 'active' : ''); ?>"
                                        href="<?php echo e(route('inventory.pos.view', ['type' => 'uniform'])); ?>">POS (Uniform)</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('UserManagement')): ?>
                    <li class="side-item side-item-category">User Management</li>
                    <li class="slide">
                        <a class="side-menu__item <?php echo e(request()->is('permissions*', 'roles*', 'users*') ? 'active' : ''); ?>"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-user-circle-o icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">User Management</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: <?php echo e(request()->is('permissions*', 'roles*', 'users*') ? 'block' : 'none'); ?>">
                            <li><a class="slide-item <?php echo e(request()->is('permissions*') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('permissions.index')); ?>">Permissions</a></li>
                            <li><a class="slide-item <?php echo e(request()->is('roles*') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('roles.index')); ?>">Roles</a></li>
                            <li><a class="slide-item <?php echo e(request()->is('users*') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('users.index')); ?>">Users</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </aside>
</div>
<?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/layouts/sidebar.blade.php ENDPATH**/ ?>