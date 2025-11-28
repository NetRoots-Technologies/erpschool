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
@section('css')
@endsection
@php
    $company = \App\Models\Admin\Company::where('status', 1)->first();
    $logoUrl = !empty($company->logo)
        ? asset($company->logo)
        : 'https://www.netrootstech.com/wp-content/uploads/2022/08/Netroots-logo-tm-transparent.png';
@endphp

<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
<div class="sticky">
    <aside class="app-sidebar sidebar-scroll">
        <div class="main-sidebar-header active side-style">
            <a class="desktop-logo logo-light active circular-logo" href="{{ route('dashboard') }}">
                <img src="{{ $logoUrl }}" style="height: 100px; margin-top: -35px;" class="main-logo" alt="logo">
            </a>
            <a class="desktop-logo logo-dark" href="{{ route('dashboard') }}">
                <img src="{{ $logoUrl }}" style="height: 100px; margin-top: -35px;" class="main-logo"
                    alt="logo">
            </a>
            <a class="logo-icon mobile-logo icon-light active circular-logo" href="{{ route('dashboard') }}">
                <img src="{{ asset('logos/CSS_logo_mobile.png') }}" alt="logo">
            </a>
            <a class="logo-icon mobile-logo icon-dark circular-logo" href="{{ route('dashboard') }}">
                <img src="{{ asset('logos/CSS_logo_mobile.png') }}" alt="logo">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="app-sidebar__user clearfix">
                <div class="dropdown user-pro-body user-style">
                    <div>
                        <img alt="user-img" class="avatar avatar-xl brround"
                            src="{{ asset('dist/assets/img/faces/6.png') }}">
                        <span class="avatar-status profile-status bg-green"></span>
                    </div>
                    <div class="user-info">
                        <h4 class="fw-semibold mt-3 mb-0">{{ auth()->user()->name ?? '' }}</h4>
                        <span class="mb-0 text-muted">{{ auth()->user()->getRoleNames()->first() ?? '' }}</span>
                    </div>
                </div>
            </div>
            <div class="slide-left disabled" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>
            <ul class="side-menu">
                {{-- @can('Dashboard') --}}
                <li class="side-item side-item-category">Main</li>
                <li class="slide">
                    <a class="side-menu__item {{ request()->is('dashboard*') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="fa fa-tachometer icons8 icon-style" aria-hidden="true"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                {{-- @endcan --}}

                @canany(['Company', 'Branches', 'Category', 'Departments', 'FinancialYears', 'Designations',
                    'SignatoryAuthorities'])
                    <li class="side-item side-item-category">Academic</li>
                    @can('Company')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('admin/company*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-building icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Company</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: {{ request()->is('admin/company*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('admin/company') ? 'active' : '' }}"
                                        href="{{ route('admin.company.index') }}">Company</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('Branches')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('admin/branches*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-code-branch icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Branches</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: {{ request()->is('admin/branches*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->routeIs('admin.branches.index') ? 'active' : '' }}"
                                        href="{{ route('admin.branches.index') }}">Branches</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('Category')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('admin/category*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-list icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Category</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: {{ request()->is('admin/category*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('admin/category*') ? 'active' : '' }}"
                                        href="{{ route('admin.category.index') }}">Category</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('Departments')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('admin/departments*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-briefcase icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Departments</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: {{ request()->is('admin/departments*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('admin/departments*') ? 'active' : '' }}"
                                        href="{{ route('admin.departments.index') }}">Departments</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('FinancialYears')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('admin/financial-years*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-calendar-minus-o icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Financial Years</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: {{ request()->is('admin/financial-years*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('admin/financial-years*') ? 'active' : '' }}"
                                        href="{{ route('admin.financial-years.index') }}">Financial Years</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('Designations')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('hr/designation*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-briefcase icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Designations</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: {{ request()->is('hr/designation*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('hr/designation*') ? 'active' : '' }}"
                                        href="{{ route('hr.designations.index') }}">Designations</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('SignatoryAuthorities')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('admin/signatory-authorities*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-briefcase icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Signatory Authorities</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: {{ request()->is('admin/signatory-authorities*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('admin/signatory-authorities*') ? 'active' : '' }}"
                                        href="{{ route('admin.signatory-authorities.index') }}">Signatory Authorities</a></li>
                            </ul>
                        </li>
                    @endcan
                @endcanany

                @canany(['AcademicSession', 'SchoolType', 'Class', 'ActiveSessions', 'Section', 'Subjects'])
                    <li class="side-item side-item-category">Academic Management</li>
                    @canany(['AcademicSession', 'SchoolType', 'Class', 'ActiveSessions', 'Section', 'Subjects'])
                        <li class="slide">
                            <a class="side-menu__item slide-change {{ request()->is('academic/academic-session*', 'academic/active_sessions*', 'academic/schools*', 'academic/classes*', 'academic/sections*', 'academic/subjects*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-users icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Academic Session</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: {{ request()->is('academic/academic-session*', 'academic/active_sessions*', 'academic/schools*', 'academic/classes*', 'academic/sections*', 'academic/subjects*') ? 'block' : 'none' }}">
                                @can('AcademicSession')
                                    <li><a class="slide-item {{ request()->is('academic/academic-session*') ? 'active' : '' }}"
                                            href="{{ route('academic.academic-session.index') }}">Academic Session</a></li>
                                @endcan
                                @can('SchoolType')
                                    <li><a class="slide-item {{ request()->is('academic/schools*') ? 'active' : '' }}"
                                            href="{{ route('academic.schools.index') }}">School Type</a></li>
                                @endcan
                                @can('Class')
                                    <li><a class="slide-item {{ request()->is('academic/classes*') ? 'active' : '' }}"
                                            href="{{ route('academic.classes.index') }}">Class</a></li>
                                @endcan
                                @can('ActiveSessions')
                                    <li><a class="slide-item {{ request()->is('academic/active_sessions*') ? 'active' : '' }}"
                                            href="{{ route('academic.active_sessions.index') }}">Active Sessions</a></li>
                                @endcan
                                @can('Section')
                                    <li><a class="slide-item {{ request()->is('academic/sections*') ? 'active' : '' }}"
                                            href="{{ route('academic.sections.index') }}">Section</a></li>
                                @endcan
                                @can('Subjects')
                                    <li><a class="slide-item {{ request()->is('academic/subjects*') ? 'active' : '' }}"
                                            href="{{ route('academic.subjects.index') }}">Subjects</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endcanany
                @if (Gate::allows('AcademicManagement') || Gate::allows('AttendanceManagement') || Gate::allows('StudentManagement'))
                    <li class="slide">
                        <a class="side-menu__item slide-change {{ request()->is('academic/student-class_adjustment*', 'academic/studentDataBank*', 'academic/students*', 'academic/students_form*', 'academic/students_details*', 'academic/student-siblings-report*', 'academic/student/leave/aprove') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-graduation-cap icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Admission Management</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('academic/student-class_adjustment*', 'academic/studentDataBank*', 'academic/students*', 'academic/students_form*', 'academic/students_details*', 'academic/student-siblings-report*', 'academic/student/leave/aprove') ? 'block' : 'none' }}">
                            @if (Gate::allows('PreAdmissionForm-list'))
                                <li><a class="slide-item {{ request()->is('academic/studentDataBank*') ? 'active' : '' }}"
                                        href="{{ route('academic.studentDataBank.index') }}">Pre-Admission Form</a>
                                </li>
                            @endif

                            @if (Gate::allows('ViewStudents-list'))
                                <li><a class="slide-item {{ request()->is('academic/students*') ? 'active' : '' }}"
                                        href="{{ route('academic.students.index') }}">Students</a></li>
                            @endif

                            @can('Student Leave Approve')
                                <li><a class="slide-item {{ request()->is('academic/student/leave/aprove') ? 'active' : '' }}"
                                        href="{{ route('academic.students.leave.aprove') }}">Leave Students Aproval</a>
                                </li>
                            @endcan

                            @if (Gate::allows('StudentSiblingsReport-list'))
                                <li><a class="slide-item {{ request()->is('academic/student-siblings-report*') ? 'active' : '' }}"
                                        href="{{ route('academic.student-siblings-report') }}">Student Siblings
                                        Report</a></li>
                            @endif

                            @if (Gate::allows('StudentClassPromotion-list'))
                                <li><a class="slide-item {{ request()->is('academic/student-class_adjustment*') ? 'active' : '' }}"
                                        href="{{ route('academic.student-class-adjustment.create') }}">Student Class
                                        Promotion</a></li>
                            @endif




                        </ul>
                    </li>
                @endif
                @can('AttendanceManagement')
                    <li class="slide">
                        <a class="side-menu__item slide-change {{ request()->is('academic/student_attendance*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-calendar icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Attendance Management</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('academic/student_attendance*') ? 'block' : 'none' }}">
                            <li><a class="slide-item {{ request()->is('academic/student_attendance/create') ? 'active' : '' }}"
                                    href="{{ route('academic.student_attendance.create') }}">Student Attendance</a></li>
                            <li><a class="slide-item {{ request()->is('academic/student_attendance') ? 'active' : '' }}"
                                    href="{{ route('academic.student_attendance.index') }}">Attendance Report</a></li>
                        </ul>
                    </li>
                @endcan
                @can('StudentManagement')
                    <li class="slide">
                        <a class="side-menu__item slide-change {{ request()->is('academic/student_view*', 'academic/assign_timetable*', 'academic/assign_class*', 'academic/subject-type*', 'academic/class_timetable*', 'academic/teachers*', 'academic/timetables*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-child icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Student Management</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('academic/student_view*', 'academic/assign_timetable*', 'academic/assign_class*', 'academic/subject-type*', 'academic/class_timetable*', 'academic/teachers*', 'academic/timetables*') ? 'block' : 'none' }}">
                            <li><a class="slide-item {{ request()->is('academic/assign_class*') ? 'active' : '' }}"
                                    href="{{ route('academic.assign_class.index') }}">Assign Class & Section</a></li>
                            <li><a class="slide-item {{ request()->is('academic/student_view*') ? 'active' : '' }}"
                                    href="{{ route('academic.student_view.index') }}">View Students</a></li>
                            <li><a class="slide-item {{ request()->is('academic/timetables*') ? 'active' : '' }}"
                                    href="{{ route('academic.timetables.index') }}">Timetables</a></li>
                            <li><a class="slide-item {{ request()->is('academic/subject-type*') ? 'active' : '' }}"
                                    href="{{ route('academic.subject-type.index') }}">Subject Type</a></li>
                            <li><a class="slide-item {{ request()->is('academic/class_timetable*') ? 'active' : '' }}"
                                    href="{{ route('academic.class_timetable.index') }}">Class Timetable</a></li>
                            <li><a class="slide-item {{ request()->is('academic/assign_timetable*') ? 'active' : '' }}"
                                    href="{{ route('academic.assign_timetable.index') }}">Assign Timetable</a></li>
                            <li><a class="slide-item {{ request()->is('academic/teachers*') ? 'active' : '' }}"
                                    href="{{ route('academic.teachers.index') }}">Teachers</a></li>
                        </ul>
                    </li>
                @endcan

                {{-- Acedemic Reports --}}
                @can('Acedemic Reprorts')
                    @php
                        // Check if any of the academic report routes are active
                        $academicRoutes = [
                            'academic.report.student-status',
                            'academic.report.strength-summary-current',
                            'academic.report.student-leave',
                            'academic.reports.students.month',
                            'academic.reports.students.year',
                            'academic.reports.students.term',
                            // add other academic report route names here if needed
                        ];
                        $isAcademicActive = false;
                        foreach ($academicRoutes as $r) {
                            if (request()->routeIs($r)) {
                                $isAcademicActive = true;
                                break;
                            }
                        }
                    @endphp

                    <li class="slide {{ $isAcademicActive ? 'is-expanded' : '' }}">
                        <a class="side-menu__item slide-change {{ $isAcademicActive ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-child icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Academic Reports</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>

                        <ul class="slide-menu" style="display: {{ $isAcademicActive ? 'block' : 'none' }}">
                            @can('Student Status Report list')
                                <li>
                                    <a class="slide-item {{ request()->routeIs('academic.report.student-status') ? 'active' : '' }}"
                                        href="{{ route('academic.report.student-status') }}">
                                        Student Status List
                                    </a>
                                </li>
                            @endcan


                            @can('Student Leave Report list')
                                <li><a class="slide-item {{ request()->is('academic/report/student-leave') ? 'active' : '' }}"
                                        href="{{ route('academic.report.student-leave') }}">Student leave List</a></li>
                            @endcan

                              @can('Monthly Student Report list')
                                <li><a class="slide-item {{ request()->is('academic/student/consolidated/month') ? 'active' : '' }}"
                                        href="{{ route('academic.reports.students.month') }}">Monthly Consolidated</a></li>
                            @endcan

                               @can('Yearly Student Report list')
                                <li><a class="slide-item {{ request()->is('academic/student/consolidated/year') ? 'active' : '' }}"
                                        href="{{ route('academic.reports.students.year') }}">Yearly Consolidated</a></li>
                            @endcan

                              @can('Term-wise Student Report list')
                                <li><a class="slide-item {{ request()->is('academic/student/consolidated/term') ? 'active' : '' }}"
                                        href="{{ route('academic.reports.students.term') }}">Term-wise Consolidated </a></li>
                            @endcan

                            @can('Strength Summary Report list')
                                <li>
                                    <a class="slide-item {{ request()->routeIs('academic.report.strength-summary-current') ? 'active' : '' }}"
                                        href="{{ route('academic.report.strength-summary-current') }}">
                                        Strength Summary Current Report
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan




                @if (Gate::allows('Employees'))
                    <li class="side-item side-item-category">HR Management</li>
                    <li class="slide ">

                        <a class="side-menu__item {{ request()->is('hr/employee') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-user-circle-o icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Employees</span>
                            <i class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('hr/employee') ? 'block' : 'none' }}">
                            <li><a class="slide-item  {{ request()->is('hr/employee') ? 'active' : '' }}"
                                    href="{{ route('hr.employee.index') }}">Employees </a></li>
                        </ul>
                    </li>
                @endif

                @if (Gate::allows('WorkShift'))
                    <li class="slide ">

                        <a class="side-menu__item {{ request()->is('hr/work_shifts*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-clock-o icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Work Shift</span>
                            <i class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('hr/work_shifts*') ? 'block' : 'none' }}">
                            <li><a class="slide-item {{ request()->is('hr/work_shifts*') ? 'active' : '' }}"
                                    href="{{ route('hr.work_shifts.index') }}">Work Shift</a></li>
                        </ul>
                    </li>
                @endif

                @if (Gate::allows('Attendance'))
                    <li class="slide ">
                        <a class="side-menu__item {{ request()->is('hr/attendance*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-calendar icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Attendance</span>
                            <i class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('hr/attendance*') ? 'block' : 'none' }}">
                            @can('EmployeeAttendance-list')
                                <li><a class="slide-item {{ request()->is('hr/attendance') ? 'active' : '' }}"
                                        href="{{ route('hr.attendance.index') }}">Employee Attendance</a></li>
                            @endcan

                            @can('AttendanceDashboard-list')
                                <li><a class="slide-item {{ request()->is('hr/attendance/dashboard') ? 'active' : '' }}"
                                        href="{{ route('hr.attendanceDashboard.dashboard') }}">Attendance Dashboard</a>
                                </li>
                            @endcan
                            @can('AttendanceReport-list')
                                <li>
                                    <a class="slide-item {{ request()->is('hr/attendance/detail') ? 'active' : '' }}"
                                        href="{{ route('hr.attendance.detail') }}">Attendance Report</a>
                                </li>
                            @endcan


                        </ul>
                    </li>
                @endif
                @if (Gate::allows('Payroll'))
                    <li class="slide ">
                        <a class="side-menu__item {{ request()->is('hr/payroll*', 'hr/salary_slip') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-money icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">PayRoll</span>
                            <i class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('hr/payroll*') ? 'block' : 'none' }}">
                            @if (Gate::allows('Payroll'))
                                <li><a class="slide-item {{ request()->is('hr/payroll') ? 'active' : '' }}"
                                        href="{{ route('hr.payroll.index') }}">PayRoll</a></li>
                            @endif
                            @if (Gate::allows('Payroll-approve'))
                                <li><a class="slide-item {{ request()->is('hr/payroll_approve') ? 'active' : '' }}"
                                        href="{{ route('hr.payroll.approve') }}">PayRoll Approve</a></li>
                            @endif
                            @if (Gate::allows('Payroll-slip'))
                                <li><a class="slide-item {{ request()->is('hr/salary_slip') ? 'active' : '' }}"
                                        href="{{ route('hr.salary_slip.index') }}">PayRoll Slip</a></li>
                            @endif
                            @if (Gate::allows('Payroll-report'))
                                <li><a class="slide-item {{ request()->is('hr/payroll_report') ? 'active' : '' }}"
                                        href="{{ route('hr.payroll.report') }}">PayRoll Report</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (Gate::allows('salary-tax'))
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('hr/salary-tax*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-chart-bar icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label ">Tax Slab</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('hr/salary-tax*') ? 'block' : 'none' }}">
                            {{-- <li><a class="slide-item" href="{{route('hr.tax-slabs.index')}}">Rental Tax
                                Slab</a></li> --}}
                            <li><a class="slide-item {{ request()->is('hr/salary-tax') ? 'active' : '' }}"
                                    href="{{ route('hr.salary-tax.index') }}">Salary Tax Slab</a></li>
                        </ul>
                    </li>
                @endif

                @if (Gate::allows('Overtime'))
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('hr/overtime*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-clock icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label ">Overtime</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('hr/salary-tax*') ? 'block' : 'none' }}">
                            {{-- <li><a class="slide-item" href="{{route('hr.tax-slabs.index')}}">Rental Tax
                            Slab</a></li> --}}
                            <li><a class="slide-item {{ request()->is('hr/overtime') ? 'active' : '' }}"
                                    href="{{ route('hr.overtime.index') }}">Overtime</a></li>
                        </ul>
                    </li>
                @endif

                @if (Gate::allows('Reports'))
                    <li class="side-item side-item-category">HR Reports</li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('hr/overtime-report*', 'hr/employeeBenefit/*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fas fa-dollar icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Reports</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('hr/overtime-report*', 'hr/employeeBenefit/*') ? 'block' : 'none' }}">
                            @can('OvertimeReport-list')
                                <li><a class="slide-item {{ request()->is('hr/overtime-report-view') ? 'active' : '' }}"
                                        href="{{ route('hr.overtime-report-view') }}">Overtime</a></li>
                            @endcan

                            @can('EOBIReport-list')
                                <li><a class="slide-item {{ request()->is('hr/employeeBenefit/EOBI') ? 'active' : '' }}"
                                        href="{{ route('hr.employeeBenefit', ['EOBI']) }}">EOBI</a></li>
                            @endcan

                            @can('PFReport-list')
                                <li><a class="slide-item {{ request()->is('hr/employeeBenefit/PF') ? 'active' : '' }}"
                                        href="{{ route('hr.employeeBenefit', ['PF']) }}">Provident Fund</a></li>
                            @endcan

                            @can('SSReport-list')
                                <li><a class="slide-item {{ request()->is('hr/employeeBenefit/SS') ? 'active' : '' }}"
                                        href="{{ route('hr.employeeBenefit', ['SS']) }}">Social Security</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif





                @canany(['ExamTerms-list', 'TestTypes-list', 'ExamDetails-list', 'Components-list',
                    'SubComponents-list', 'Skills-list', 'SkillEvaluationsKey-list', 'SkillEvaluation-list',
                    'EffortLevels-list', 'GradingPolicies-list', 'SkillGroups-list', 'SkillTypes-list',
                    'ExamSchedules-list', 'MarksInput-list'])
                    <li class="side-item side-item-category">Exam</li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('exam/exam_terms*') || request()->is('exam/test_types*') || request()->is('exam/exam_details*') || request()->is('exam/components*') || request()->is('exam/skill_evaluations_key*') || request()->is('exam/skill_evaluation*') || request()->is('exam/academic_evaluations_key*') || request()->is('exam/skill_groups*') || request()->is('exam/class_subjects*') || request()->is('exam/skill_types*') || request()->is('exam/exam_schedules*') || request()->is('exam/sub_components*') || request()->is('exam/skills*') || request()->is('exam/behaviours*') || request()->is('exam/effort_levels*') || request()->is('exam/grading_policies*') || request()->is('exam/marks_input*') || request()->is('reports/student/exam') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-file icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Exam</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('exam/exam_terms*') || request()->is('exam/test_types*') || request()->is('exam/exam_details*') || request()->is('exam/components*') || request()->is('exam/skill_evaluations_key*') || request()->is('exam/skill_evaluation*') || request()->is('exam/academic_evaluations_key*') || request()->is('exam/skill_groups*') || request()->is('exam/class_subjects*') || request()->is('exam/skill_types*') || request()->is('exam/exam_schedules*') || request()->is('exam/sub_components*') || request()->is('exam/skills*') || request()->is('exam/behaviours*') || request()->is('exam/effort_levels*') || request()->is('exam/grading_policies*') || request()->is('exam/marks_input*') || request()->is('reports/student/exam') ? 'block' : 'none' }}">
                            @can('ExamTerms-list')
                                <li><a class="slide-item {{ request()->is('exam/exam_terms*') ? 'active' : '' }}"
                                        href="{{ route('exam.exam_terms.index') }}">Exam Term</a></li>
                            @endcan
                            @can('TestTypes-list')
                                <li><a class="slide-item {{ request()->is('exam/test_types*') ? 'active' : '' }}"
                                        href="{{ route('exam.test_types.index') }}">Test Type</a></li>
                            @endcan
                            @can('ExamDetails-list')
                                <li><a class="slide-item {{ request()->is('exam/exam_details*') ? 'active' : '' }}"
                                        href="{{ route('exam.exam_details.index') }}">Exam Detail</a></li>
                            @endcan
                            @can('Components-list')
                                <li><a class="slide-item {{ request()->is('exam/components*') ? 'active' : '' }}"
                                        href="{{ route('exam.components.index') }}">Components</a></li>
                            @endcan
                            @can('SubComponents-list')
                                <li><a class="slide-item {{ request()->is('exam/sub_components*') ? 'active' : '' }}"
                                        href="{{ route('exam.sub_components.index') }}">Sub Component</a></li>
                            @endcan
                            @can('Skills-list')
                                <li><a class="slide-item {{ request()->is('exam/skills*') ? 'active' : '' }}"
                                        href="{{ route('exam.skills.index') }}">Skills</a></li>
                            @endcan
                            @can('SkillGroups-list')
                                <li><a class="slide-item {{ request()->is('exam/skill_groups*') ? 'active' : '' }}"
                                        href="{{ route('exam.skill_groups.index') }}">Skill Group</a></li>
                            @endcan
                            @can('SkillTypes-list')
                                <li><a class="slide-item {{ request()->is('exam/skill_types*') ? 'active' : '' }}"
                                        href="{{ route('exam.skill_types.index') }}">Skill Type</a></li>
                            @endcan

                            @can('SkillEvaluationsKey-list')
                                <li><a class="slide-item {{ request()->is('exam/skill_evaluations_key*') ? 'active' : '' }}"
                                        href="{{ route('exam.skill_evaluations_key.index') }}">Skill Evaluation Key</a>
                                </li>
                            @endcan
                            @can('SkillEvaluation-list')
                                <li><a class="slide-item {{ request()->is('exam/skill_evaluation*') ? 'active' : '' }}"
                                        href="{{ route('exam.skill_evaluation.index') }}">Skill Evaluation</a></li>
                            @endcan
                            {{-- @can('Behaviours-list')
                                <li><a class="slide-item {{ request()->is('exam/behaviours*') ? 'active' : '' }}"
                                        href="{{ route('exam.behaviours.index') }}">Behaviours</a></li>
                            @endcan --}}
                            @can('class-subjects-list')
                                <li><a class="slide-item {{ request()->is('exam/class_subjects*') ? 'active' : '' }}"
                                        href="{{ route('exam.class_subjects.index') }}">Class Subjects</a></li>
                            @endcan

                            @can('EffortLevels-list')
                                <li><a class="slide-item {{ request()->is('exam/effort_levels*') ? 'active' : '' }}"
                                        href="{{ route('exam.effort_levels.index') }}">Effort Levels</a></li>
                            @endcan
                            @can('GradingPolicies-list')
                                <li><a class="slide-item {{ request()->is('exam/grading_policies*') ? 'active' : '' }}"
                                        href="{{ route('exam.grading_policies.index') }}">Grading Policies</a></li>
                            @endcan
                            {{-- @can('AcademicEvaluationsKey-list')
                                <li><a class="slide-item {{ request()->is('exam/academic_evaluations_key*') ? 'active' : '' }}"
                                        href="{{ route('exam.academic_evaluations_key.index') }}">Academic Evaluation Key</a>
                                </li>
                            @endcan --}}


                            @can('ExamSchedules-list')
                                <li><a class="slide-item {{ request()->is('exam/exam_schedules*') ? 'active' : '' }}"
                                        href="{{ route('exam.exam_schedules.index') }}">Exam Schedules</a></li>
                            @endcan
                            @can('MarksInput-list')
                                <li><a class="slide-item {{ request()->is('exam/marks_input*') ? 'active' : '' }}"
                                        href="{{ route('exam.marks_input.index') }}">Marks Input</a></li>
                            @endcan
                            @can('Student Exam Report')
                                <li>
                                    <a class="slide-item {{ request()->is('reports/student/exam') ? 'active' : '' }}"
                                        href="{{ route('reports.std.exam.index') }}">
                                        Exam Report
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcanany

                @if (Gate::allows('Fee Management') || Gate::allows('Fee Reports'))
                    {{-- Fee Management Section --}}
                    <li class="side-item side-item-category">Fee Management</li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('admin/fee-management*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-money icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Fee Management</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('admin/fee-management*') ? 'block' : 'none' }}">
                            @can('fee-dashboard')
                                <li><a class="slide-item {{ request()->is('admin/fee-management') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.index') }}">Dashboard</a></li>
                            @endcan

                            @can('fee-Categories-list')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/categories*') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.categories') }}">Fee Categories</a></li>
                            @endcan

                            @can('fee-structures-list')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/structures*') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.structures') }}">Students Structures</a>
                                </li>
                            @endcan

                            @can('fee-collections-list')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/collections*') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.collections') }}">Fee Collections</a></li>
                            @endcan

                            @can('fee-discount-list')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/discounts*') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.discounts') }}">Fee Discounts</a></li>
                            @endcan

                            @can('fee-billing-list')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/billing*') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.billing') }}">Fee Billing</a></li>
                            @endcan

                            @can('fee-report-list')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/reports') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.reports') }}">Student Ledger Reports</a>
                                </li>
                            @endcan

                            @can('Fee Bills Status Report')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/reports/fee-bills') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.reports.fee-bills') }}">Fee Bills Status
                                        Report</a></li>
                            @endcan

                            @can('Fee Bills By Class Report')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/reports/fee-bills-by-class') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.reports.fee-bills-by-class') }}">Fee Bills
                                        By Class Report</a></li>
                            @endcan

                            @can('Fee Bills By Amount Report')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/reports/fee-bills-by-account') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.reports.fee-bills-by-account') }}">Fee Bills
                                        By Account</a></li>
                            @endcan

                            @can('Fee Bills By Fee Category')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/reports/category-bills') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.reports.category') }}">Fee Bills By
                                        Category</a></li>
                            @endcan

                            @can('All Bills in one folder')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/reports/fee-bills-by-month-all-students') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.reports.fee-bills-by-month-all-students') }}">Fee
                                        Bills in One Folder</a></li>
                            @endcan

                            @can('Fee Bills By Fee Month')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/reports/fee-bills-by-month/pdf') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.reports.fee-bills-by-month-pdf') }}">Fee
                                        Bills By Month</a></li>
                            @endcan

                            @can('Outstanding By Family profile')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/reports/family-profile') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.reports.family-profile') }}">Family
                                        Profile Report</a></li>
                            @endcan

                            @can('Fee Bills By Financial Assistance')
                                <li><a class="slide-item {{ request()->is('admin/fee-management/reports/fee-bills-by-financial-aid') ? 'active' : '' }}"
                                        href="{{ route('admin.fee-management.reports.fee-bills-by-financial-aid') }}">Fee
                                        Bills- By Financial Assistance</a></li>
                            @endcan

                        </ul>
                    </li>
                @endif


                {{-- Fleet Management Section --}}
                @if (Gate::allows('Fleet'))
                    <li class="side-item side-item-category">Fleet Management</li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('fleet*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-bus icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Fleet Management</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu" style="display: {{ request()->is('fleet*') ? 'block' : 'none' }}">
                            @if (Gate::allows('fleet-dashboard'))
                                <li><a class="slide-item {{ request()->is('fleet/dashboard') ? 'active' : '' }}"
                                        href="{{ route('fleet.dashboard') }}">Dashboard</a></li>
                            @endif

                            @if (Gate::allows('vahicals-list'))
                                <li><a class="slide-item {{ request()->is('fleet/vehicles*') ? 'active' : '' }}"
                                        href="{{ route('fleet.vehicles.index') }}">Vehicles</a></li>
                            @endif

                            @if (Gate::allows('drivers-list'))
                                <li><a class="slide-item {{ request()->is('fleet/drivers*') ? 'active' : '' }}"
                                        href="{{ route('fleet.drivers.index') }}">Drivers</a></li>
                            @endif

                            @if (Gate::allows('routes-list'))
                                <li><a class="slide-item {{ request()->is('fleet/routes*') ? 'active' : '' }}"
                                        href="{{ route('fleet.routes.index') }}">Routes</a></li>
                            @endif
                            @if (Gate::allows('Fleet-maintenance-list'))
                                <li><a class="slide-item {{ request()->is('fleet/maintenance*') ? 'active' : '' }}"
                                        href="{{ route('fleet.maintenance.index') }}">Maintenance</a></li>
                            @endif

                            @if (Gate::allows('Fule-record-list'))
                                <li><a class="slide-item {{ request()->is('fleet/fuel*') ? 'active' : '' }}"
                                        href="{{ route('fleet.fuel.index') }}">Fuel Records</a></li>
                            @endif

                            @if (Gate::allows('Fleet-expense-list'))
                                <li><a class="slide-item {{ request()->is('fleet/expenses*') ? 'active' : '' }}"
                                        href="{{ route('fleet.expenses.index') }}">Expenses</a></li>
                            @endif

                            @if (Gate::allows('students-transport-list'))
                                <li><a class="slide-item {{ request()->is('fleet/transportation*') ? 'active' : '' }}"
                                        href="{{ route('fleet.transportation.index') }}">Student
                                        Transportation</a></li>
                            @endif

                        </ul>
                    </li>
                @endif


                {{-- Accounts & Finance Section --}}
                @can('Accounts')
                    <li class="side-item side-item-category">Accounts & Finance</li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('accounts*') || request()->is('admin*') || request()->is('hr/asset_type*') || request()->is('hr/asset', 'hr/asset/*') || request()->is('asset', 'asset/bulk') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-calculator icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Accounts & Finance</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('accounts*') || request()->is('admin*') || request()->is('hr/asset_type*') || request()->is('hr/asset', 'hr/asset/*') || request()->is('asset', 'asset/bulk') ? 'block' : 'none' }}">
                            <li><a class="slide-item {{ request()->is('accounts/dashboard') ? 'active' : '' }}"
                                    href="{{ route('accounts.dashboard') }}">Dashboard</a></li>

                            {{-- Account Groups & Chart of Accounts --}}
                            <li><a class="slide-item {{ request()->is('accounts/groups*') ? 'active' : '' }}"
                                    href="{{ route('accounts.groups.index') }}">Account Groups</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/chart-of-accounts*') ? 'active' : '' }}"
                                    href="{{ route('accounts.coa.index') }}">Chart of Accounts</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/journal-entries*') ? 'active' : '' }}"
                                    href="{{ route('accounts.journal.index') }}">Journal Entries</a></li>

                            {{-- Payables --}}
                            <li><a class="slide-item {{ request()->is('accounts/payables') ? 'active' : '' }}"
                                    href="{{ route('accounts.payables.index') }}">Accounts Payable</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/payables/vendors*') ? 'active' : '' }}"
                                    href="{{ route('accounts.payables.vendors.index') }}">Vendors</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/payables/bills*') ? 'active' : '' }}"
                                    href="{{ route('accounts.payables.bills.index') }}">Bills</a></li>

                            {{-- Receivables --}}
                            <li><a class="slide-item {{ request()->is('accounts/receivables') ? 'active' : '' }}"
                                    href="{{ route('accounts.receivables.index') }}">Accounts Receivable</a></li>

                            <li><a class="slide-item {{ request()->is('accounts/receivables/customers*') ? 'active' : '' }}"
                                    href="{{ route('accounts.receivables.customers.index') }}"
                                    style="display: none;">Customers</a></li>

                            {{-- Vendor Payments --}}

                            <li><a class="slide-item {{ request()->is('accounts/payables/vendor-payments*') ? 'active' : '' }}"
                                    href="{{ route('accounts.payables.vendorPayments.index') }}">Vendor Payments</a></li>

                            <li><a class="slide-item {{ request()->is('accounts/receivables/invoices*') ? 'active' : '' }}"
                                    href="{{ route('accounts.receivables.invoices.index') }}">Invoices</a></li>

                            {{-- Cost & Profit Centers --}}
                            <li><a class="slide-item {{ request()->is('accounts/cost-centers*') ? 'active' : '' }}"
                                    href="{{ route('accounts.cost_centers.index') }}">Cost Centers</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/profit-centers*') ? 'active' : '' }}"
                                    href="{{ route('accounts.profit_centers.index') }}">Profit Centers</a></li>

                            {{-- Reports --}}
                            <li><a class="slide-item {{ request()->is('accounts/reports/trial-balance*') ? 'active' : '' }}"
                                    href="{{ route('accounts.reports.trial_balance') }}">Trial Balance</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/reports/balance-sheet*') ? 'active' : '' }}"
                                    href="{{ route('accounts.reports.balance_sheet') }}">Balance Sheet</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/reports/income-statement*') ? 'active' : '' }}"
                                    href="{{ route('accounts.reports.income_statement') }}">Income Statement</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/reports/cash-flow*') ? 'active' : '' }}"
                                    href="{{ route('accounts.reports.cash_flow') }}">Cash Flow</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/reports/aged-payables*') ? 'active' : '' }}"
                                    href="{{ route('accounts.reports.aged_payables') }}">Aged Payables</a></li>
                            <li><a class="slide-item {{ request()->is('accounts/reports/aged-receivables*') ? 'active' : '' }}"
                                    href="{{ route('accounts.reports.aged_receivables') }}">Aged Receivables</a></li>

                            {{-- Assets --}}


                            <li>
                                <a class="slide-item {{ request()->is('hr/asset_type*') ? 'active' : '' }}"
                                    href="{{ route('hr.asset_type.index') }}">Assets Type</a>
                            </li>
                            <li>
                                <a class="slide-item {{ request()->is('hr/asset', 'hr/asset/*') ? 'active' : '' }}"
                                    href="{{ route('hr.asset.index') }}">Assets</a>
                            </li>
                            <li>
                                <a class="slide-item {{ request()->is('asset', 'asset/bulk') ? 'active' : '' }}"
                                    href="{{ route('asset-bulk') }}">Assets Bulk</a>
                            </li>

                            {{-- End Assets --}}
                            <li><a class="slide-item {{ request()->is('admin/banks') || request()->is('admin/banks/*') ? 'active' : '' }}"
                                    href="{{ route('admin.banks.index') }}">Bank</a></li>

                            <li><a class="slide-item {{ request()->is('admin/banks_branches') || request()->is('admin/banks_branches/*') ? 'active' : '' }}"
                                    href="{{ route('admin.banks_branches.index') }}">Bank Branch</a></li>

                            <li><a class="slide-item {{ request()->is('admin/bank_accounts*') ? 'active' : '' }}"
                                    href="{{ route('admin.bank_accounts.index') }}">Bank Account</a></li>
                        @endcan
                    </ul>
                </li>

                @canany(['EmployeeWelfare', 'EOBI', 'ProfitFunds', 'SocialSecurity'])
                    <li class="side-item side-item-category">Funds</li>
                    @can('EmployeeWelfare')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('hr/employee-welfare*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-shield icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Employee Welfare</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: {{ request()->is('hr/employee-welfare*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('hr/employee-welfare*') ? 'active' : '' }}"
                                        href="{{ route('hr.employee-welfare.index') }}">Employee Welfare</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('EOBI')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('hr/eobis*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-handshake-o icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">EOBI</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu" style="display: {{ request()->is('hr/eobis*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('hr/eobis*') ? 'active' : '' }}"
                                        href="{{ route('hr.eobis.index') }}">EOBI</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('ProfitFunds')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('hr/profit-funds*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-sitemap icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Provident Fund</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: {{ request()->is('hr/profit-funds*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('hr/profit-funds*') ? 'active' : '' }}"
                                        href="{{ route('hr.profit-funds.index') }}">Provident Fund</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('SocialSecurity')
                        <li class="slide">
                            <a class="side-menu__item {{ request()->is('hr/social-security*') ? 'active' : '' }}"
                                data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fa fa-id-badge icons8 icon-style" aria-hidden="true"></i>
                                <span class="side-menu__label">Social Security</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>
                            <ul class="slide-menu"
                                style="display: {{ request()->is('hr/social-security*') ? 'block' : 'none' }}">
                                <li><a class="slide-item {{ request()->is('hr/social-security*') ? 'active' : '' }}"
                                        href="{{ route('hr.social-security.index') }}">Social Security</a></li>
                            </ul>
                        </li>
                    @endcan
                @endcanany

                @if (Gate::allows('Leave Settings'))
                    <li class="side-item side-item-category ">Leave Management</li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('hr/qouta_sections*') || request()->is('hr/holidays*') || request()->is('hr/leave_requests*') || request()->is('hr/manage_leaves*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-pencil icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">Leave Settings</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu "
                            style="display: {{ request()->is('hr/qouta_sections*') || request()->is('hr/holidays*') || request()->is('hr/leave_requests*') || request()->is('hr/manage_leaves*') ? 'block' : 'none' }}">
                            @if (Gate::allows('Quota list'))
                                <li><a class="slide-item {{ request()->is('hr/qouta_sections*') ? 'active' : '' }}"
                                        href="{{ route('hr.qouta_sections.index') }}">Permanent Employees
                                        Quota</a>
                                </li>
                            @endif
                            @if (Gate::allows('Holiday list'))
                                <li><a class="slide-item {{ request()->is('hr/holidays*') ? 'active' : '' }}"
                                        href="{{ route('hr.holidays.index') }}">Gazetted Holidays</a></li>
                            @endif
                            @if (Gate::allows('Leave Request List'))
                                <li><a class="slide-item {{ request()->is('hr/leave_requests*') ? 'active' : '' }}"
                                        href="{{ route('hr.leave_requests.index') }}">Leave Request</a></li>
                            @endif

                            @if (Gate::allows('Manage Leave List'))
                                <li><a class="slide-item {{ request()->is('hr/manage_leaves*') ? 'active' : '' }}"
                                        href="{{ route('hr.manage_leaves.index') }}">Manage Leaves</a></li>
                            @endif
                        </ul>
                    </li>
                @endif


                {{-- ================= BUDGET MANAGEMENT ================= --}}
                @canany(['Budget', 'supplementory', 'expense', 'BudgetCategory'])

                    @php
                        // Any of these routes should keep the Budget Management group open
                        $isBudgetOpen = request()->routeIs(
                            'inventory.budget.*',
                            'inventory.List.ofAssignDepartment',
                            'inventory.category.*',
                            'inventory.expense.*',
                            'inventory.supplementory.*',
                            'inventory.supplimentary.requests.list',
                            'inventory.supplimentory.budget.report',
                        );
                    @endphp


                    <li class="side-item side-item-category">Budegt Management</li>
                    <li class="slide">
                        <a class="side-menu__item {{ $isBudgetOpen ? 'active' : '' }}" data-bs-toggle="slide"
                            href="javascript:void(0);">
                            <i class="fas fa-wallet icons8 icon-style me-3"></i>
                            <span class="side-menu__label">Budget Management</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>

                        {{-- @dd(auth()->user()->getAllPermissions()->pluck('name'));</li> --}}
                        {{-- @dd(auth()->user()->hasPermissionTo('Budget')); --}}
                        <ul class="slide-menu" style="display: {{ $isBudgetOpen ? 'block' : 'none' }}">

                            @can('Budget-list')
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.budget.index') ? 'active' : '' }}"
                                        href="{{ route('inventory.budget.index') }}">
                                        Budget
                                    </a>
                                </li>
                            @endcan

                            {{-- Visible without the Budget permission per your current code --}}
                            @can('Budget-list')
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.List.ofAssignDepartment') ? 'active' : '' }}"
                                        href="{{ route('inventory.List.ofAssignDepartment') }}">
                                        Assign Department List
                                    </a>
                                </li>
                            @endcan


                            @can('BudgetCategory')
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.category.index') ? 'active' : '' }}"
                                        href="{{ route('inventory.category.index') }}">
                                        Category
                                    </a>
                                </li>
                            @endcan

                            @can('expense')
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.expense.index') ? 'active' : '' }}"
                                        href="{{ route('inventory.expense.index') }}">
                                        Budget Expense
                                    </a>
                                </li>
                            @endcan
                            @can('supplementory list')
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.supplementory.index') ? 'active' : '' }}"
                                        href="{{ route('inventory.supplementory.index') }}">
                                        Supplementary Budget
                                    </a>
                                </li>
                            @endcan

                            @can('supplementory request')
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.supplimentary.requests.list') ? 'active' : '' }}"
                                        href="{{ route('inventory.supplimentary.requests.list') }}">
                                        Request List
                                    </a>
                                </li>
                            @endcan

                            @can('supplementory report')
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.supplimentory.budget.report') ? 'active' : '' }}"
                                        href="{{ route('inventory.supplimentory.budget.report') }}">
                                        Supplementary Report
                                    </a>
                                </li>
                            @endcan


                        </ul>
                    </li>

                @endcanany



                {{-- auth()->user()->hasPermissionTo('Budget') --}}
                {{-- @dd(auth()->user()->getAllPermissions()->pluck('name')); --}}
                {{-- Maintenance Management Section --}}
                <li class="side-item side-item-category" style="display: none;">Maintenance Management</li>
                <li class="slide" style="display: none;">
                    <a class="side-menu__item {{ request()->is('maintainer*') ? 'active' : '' }}"
                        data-bs-toggle="slide" href="javascript:void(0);">
                        <i class="fa fa-money icons8 icon-style" aria-hidden="true"></i>
                        <span class="side-menu__label">Maintenance</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>

                    <ul class="slide-menu"
                        style="display: {{ request()->is('maintenance-request*') || request()->is('maintainer*') ? 'block' : 'none' }}">
                        {{-- Maintainer Section --}}
                        <li>
                            <a class="slide-item {{ request()->is('maintainer/type*') ? 'active' : '' }}"
                                href="{{ route('maintainer.type.index') }}">
                                Type
                            </a>
                        </li>
                        <li>
                            <a class="slide-item {{ request()->is('maintainer/maintainer*') ? 'active' : '' }}"
                                href="{{ route('maintainer.maintainer.index') }}">
                                Maintainer
                            </a>
                        </li>
                        <li>
                            <a class="slide-item {{ request()->is('maintainer/building*') ? 'active' : '' }}"
                                href="{{ route('maintainer.building.index') }}">
                                Building
                            </a>
                        </li>

                        {{-- Maintenance Request Section --}}
                        <li>
                            <a class="slide-item {{ request()->is('maintenance-request') || request()->is('maintenance-request/index') ? 'active' : '' }}"
                                href="{{ route('maintenance-request.index') }}">
                                All Requests
                            </a>
                        </li>
                        <li>
                            <a class="slide-item {{ request()->is('maintenance-request/pending') ? 'active' : '' }}"
                                href="{{ route('maintenance-request.pending') }}">
                                Pending Requests
                            </a>
                        </li>
                        <li>
                            <a class="slide-item {{ request()->is('maintenance-request/in-progress') ? 'active' : '' }}"
                                href="{{ route('maintenance-request.inprogress') }}">
                                In Progress Requests
                            </a>
                        </li>
                        <li>
                            <a class="slide-item {{ request()->is('maintenance-request/approval') ? 'active' : '' }}"
                                href="{{ route('maintenance-request.approval') }}">
                                Maintenance Approval
                            </a>
                        </li>
                    </ul>

                </li>



                {{-- ================= INVENTORY MANAGEMENT ================= --}}
                @canany(['Vendor', 'VendorCategory', 'CafeInventory', 'StationeryInventory', 'UniformInventory', 'POS'])
                    @php
                        $paramType = request()->route('type');
                        $queryType = request('type');
                        $type = $paramType ?? $queryType;

                        // OPEN STATES
                        $isCafeOpen =
                            $type === 'food' ||
                            request()->routeIs(
                                'inventory.school_lunch.*',
                                'inventory.staff_lunch.*',
                                'inventory.product.productCompleted',
                            );

                        // Keep Stationery open logic simple & reliable
                        $isStationeryOpen = $type === 'stationary';

                        // Uniform open logic
                        $isUniformOpen = $type === 'uniform';

                        $isPOSOpen = request()->routeIs('inventory.pos.view');
                    @endphp

                    <li class="side-item side-item-category">Inventory Management</li>

                    {{-- ================= CAFE INVENTORY ================= --}}
                    @can('CafeInventory')
                        <li class="slide">
                            <a class="side-menu__item {{ $isCafeOpen ? 'active' : '' }}" data-bs-toggle="slide"
                                href="javascript:void(0);">
                                <i class="fas fa-box icons8 icon-style me-3"></i>
                                <span class="side-menu__label">Cafe Inventory</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>

                            <ul class="slide-menu" style="display: {{ $isCafeOpen ? 'block' : 'none' }}">
                                @can('RawMaterialItems-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.items.index') && $type === 'food' ? 'active' : '' }}"
                                            href="{{ route('inventory.items.index', ['type' => 'food']) }}">
                                            Raw Material & Items
                                        </a>
                                    </li>
                                @endcan

                                @can('Supplier-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.suppliers.index') && $type === 'food' ? 'active' : '' }}"
                                            href="{{ route('inventory.suppliers.index', ['type' => 'food']) }}">
                                            Suppliers
                                        </a>
                                    </li>
                                @endcan

                                @can('Requisitions-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.requisitions.index') && $type === 'food' ? 'active' : '' }}"
                                            href="{{ route('inventory.requisitions.index', ['type' => 'food']) }}">
                                            Requisitions
                                        </a>
                                    </li>
                                @endcan

                                @can('RequisitionApproval-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.requisitions.approval') && $type === 'food' ? 'active' : '' }}"
                                            href="{{ route('inventory.requisitions.approval', ['type' => 'food']) }}">
                                            Requisition Approval
                                        </a>
                                    </li>
                                @endcan

                                @can('Quotes-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.quotes.index') && $type === 'food' ? 'active' : '' }}"
                                            href="{{ route('inventory.quotes.index', ['type' => 'food']) }}">
                                            Quotes
                                        </a>
                                    </li>
                                @endcan

                                @can('PurchaseOrders-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.purchase_order.index') && $type === 'food' ? 'active' : '' }}"
                                            href="{{ route('inventory.purchase_order.index', ['type' => 'food']) }}">
                                            Purchase Order
                                        </a>
                                    </li>
                                @endcan

                                @can('GRN-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.grn') && $type === 'food' ? 'active' : '' }}"
                                            href="{{ route('inventory.grn', ['type' => 'food']) }}">
                                            GRN
                                        </a>
                                    </li>
                                @endcan

                                @can('inventory-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.inventry.index') && $type === 'food' ? 'active' : '' }}"
                                            href="{{ route('inventory.inventry.index', ['type' => 'food']) }}">
                                            Inventory
                                        </a>
                                    </li>
                                @endcan

                                @can('Products-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.product.index') && $type === 'food' ? 'active' : '' }}"
                                            href="{{ route('inventory.product.index', ['type' => 'food']) }}">
                                            Products
                                        </a>
                                    </li>
                                @endcan

                                @can('CompletedProducts-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.product.productCompleted') ? 'active' : '' }}"
                                            href="{{ route('inventory.product.productCompleted') }}">
                                            Completed Goods
                                        </a>
                                    </li>
                                @endcan

                                @can('StudentMeal-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.school_lunch.school_lunch') ? 'active' : '' }}"
                                            href="{{ route('inventory.school_lunch.school_lunch') }}">
                                            Student Meal
                                        </a>
                                    </li>
                                @endcan

                                @can('StudentMealAssigned-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.school_lunch.view', 'inventory.school_lunch.get_assigned_student') ? 'active' : '' }}"
                                            href="{{ route('inventory.school_lunch.view') }}">
                                            Student Meal Assigned
                                        </a>
                                    </li>
                                @endcan

                                @can('StaffMeal-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.staff_lunch.emp_index') ? 'active' : '' }}"
                                            href="{{ route('inventory.staff_lunch.emp_index') }}">
                                            Staff Meal
                                        </a>
                                    </li>
                                @endcan

                                @can('StaffMealAssigned-list')
                                    <li>
                                        <a class="slide-item {{ request()->routeIs('inventory.staff_lunch.emp_view') ? 'active' : '' }}"
                                            href="{{ route('inventory.staff_lunch.emp_view') }}">
                                            Staff Meal Assigned
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan

                    {{-- ================= STATIONERY INVENTORY ================= --}}
                    @canany(['StationeryInventory', 'CafeInventory'])
                        {{-- show if either perm exists --}}
                        <li class="slide">
                            <a class="side-menu__item {{ $isStationeryOpen ? 'active' : '' }}" data-bs-toggle="slide"
                                href="javascript:void(0);">
                                <i class="fas fa-warehouse icons8 icon-style me-3"></i>
                                <span class="side-menu__label">Stationery Inventory</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>

                            <ul class="slide-menu" style="display: {{ $isStationeryOpen ? 'block' : 'none' }}">
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.items.index') && $type === 'stationary' ? 'active' : '' }}"
                                        href="{{ route('inventory.items.index', ['type' => 'stationary']) }}">
                                        Items
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.suppliers.index') && $type === 'stationary' ? 'active' : '' }}"
                                        href="{{ route('inventory.suppliers.index', ['type' => 'stationary']) }}">
                                        Suppliers
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.requisitions.index') && $type === 'stationary' ? 'active' : '' }}"
                                        href="{{ route('inventory.requisitions.index', ['type' => 'stationary']) }}">
                                        Requisitions
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.requisitions.approval') && $type === 'stationary' ? 'active' : '' }}"
                                        href="{{ route('inventory.requisitions.approval', ['type' => 'stationary']) }}">
                                        Requisition Approval
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.quotes.index') && $type === 'stationary' ? 'active' : '' }}"
                                        href="{{ route('inventory.quotes.index', ['type' => 'stationary']) }}">
                                        Quotes
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.purchase_order.index') && $type === 'stationary' ? 'active' : '' }}"
                                        href="{{ route('inventory.purchase_order.index', ['type' => 'stationary']) }}">
                                        Purchase Order
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.grn') && $type === 'stationary' ? 'active' : '' }}"
                                        href="{{ route('inventory.grn', ['type' => 'stationary']) }}">
                                        GRN
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.inventry.index') && $type === 'stationary' ? 'active' : '' }}"
                                        href="{{ route('inventory.inventry.index', ['type' => 'stationary']) }}">
                                        Store Inventory
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.product.index') && $type === 'stationary' ? 'active' : '' }}"
                                        href="{{ route('inventory.product.index', ['type' => 'stationary']) }}">
                                        Bundles
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcanany

                    {{-- ================= UNIFORM INVENTORY ================= --}}
                    @canany(['UniformInventory', 'CafeInventory', 'StationeryInventory'])
                        {{-- flexible: show for any of these perms --}}
                        <li class="slide">
                            <a class="side-menu__item {{ $isUniformOpen ? 'active' : '' }}" data-bs-toggle="slide"
                                href="javascript:void(0);">
                                <i class="fas fa-tshirt icons8 icon-style me-3"></i>
                                <span class="side-menu__label">Uniform Inventory</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>

                            <ul class="slide-menu" style="display: {{ $isUniformOpen ? 'block' : 'none' }}">
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.items.index') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.items.index', ['type' => 'uniform']) }}">
                                        Items
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.suppliers.index') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.suppliers.index', ['type' => 'uniform']) }}">
                                        Suppliers
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.requisitions.index') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.requisitions.index', ['type' => 'uniform']) }}">
                                        Requisitions
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.requisitions.approval') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.requisitions.approval', ['type' => 'uniform']) }}">
                                        Requisition Approval
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.quotes.index') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.quotes.index', ['type' => 'uniform']) }}">
                                        Quotes
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.purchase_order.index') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.purchase_order.index', ['type' => 'uniform']) }}">
                                        Purchase Order
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.grn') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.grn', ['type' => 'uniform']) }}">
                                        GRN
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.inventry.index') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.inventry.index', ['type' => 'uniform']) }}">
                                        Store Inventory
                                    </a>
                                </li>
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.product.index') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.product.index', ['type' => 'uniform']) }}">
                                        Bundles
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcanany

                    {{-- ================= POINT OF SALE ================= --}}
                    @canany(['POS', 'CafeInventory'])
                        <li class="slide">
                            <a class="side-menu__item {{ $isPOSOpen ? 'active' : '' }}" data-bs-toggle="slide"
                                href="javascript:void(0);">
                                <i class="fas fa-cart-arrow-down icons8 icon-style me-3"></i>
                                <span class="side-menu__label">POS</span>
                                <i class="angle fe fe-chevron-down"></i>
                            </a>

                            <ul class="slide-menu" style="display: {{ $isPOSOpen ? 'block' : 'none' }}">
                                {{-- <li>
                    <a class="slide-item {{ request()->routeIs('inventory.pos.view') && $type === 'food' ? 'active' : '' }}"
                       href="{{ route('inventory.pos.view', ['type' => 'food']) }}">
                        POS (Food)
                    </a>
                </li> --}}
                                <li>
                                    <a class="slide-item {{ request()->routeIs('inventory.pos.view') && $type === 'uniform' ? 'active' : '' }}"
                                        href="{{ route('inventory.pos.view', ['type' => 'uniform']) }}">
                                        POS (Uniform)
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcanany


                    {{-- ================= REPORTS (NEW MAIN MENU) ================= --}}
                    <li class="slide">
                        <a class="side-menu__item {{ request()->routeIs('inventory.reports.*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fas fa-file-alt icons8 icon-style me-3"></i>
                            <span class="side-menu__label">Reports</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>

                        <ul class="slide-menu"
                            style="display: {{ request()->routeIs('inventory.reports.*') ? 'block' : 'none' }}">

                            {{-- Supplier Ledger --}}
                            <li>
                                <a class="slide-item {{ request()->routeIs('inventory.reports.supplier.ledger') ? 'active' : '' }}"
                                    href="{{ route('inventory.reports.supplier.ledger') }}">
                                    Supplier Ledger
                                </a>
                            </li>

                        </ul>
                    </li>
                @endcanany



                @can('UserManagement')
                    <li class="side-item side-item-category">User Management</li>
                    <li class="slide">
                        <a class="side-menu__item {{ request()->is('permissions*', 'roles*', 'users*') ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="fa fa-user-circle-o icons8 icon-style" aria-hidden="true"></i>
                            <span class="side-menu__label">User Management</span>
                            <i class="angle fe fe-chevron-down"></i>
                        </a>
                        <ul class="slide-menu"
                            style="display: {{ request()->is('permissions*', 'roles*', 'users*') ? 'block' : 'none' }}">
                            <li><a class="slide-item {{ request()->is('permissions*') ? 'active' : '' }}"
                                    href="{{ route('permissions.index') }}">Permissions</a></li>
                            <li><a class="slide-item {{ request()->is('roles*') ? 'active' : '' }}"
                                    href="{{ route('roles.index') }}">Roles</a></li>
                            <li><a class="slide-item {{ request()->is('users*') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}">Users</a></li>
                        </ul>
                    </li>
                @endcan
            </ul>
            <div class="slide-right" id="slide-right">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </aside>
</div>
