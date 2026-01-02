<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\EobiController;
use App\Http\Controllers\HR\AgentController;
use App\Http\Controllers\HR\AssetController;
use App\Http\Controllers\HR\QuottaController;
use App\Http\Controllers\HR\AdvanceController;
use App\Http\Controllers\HR\HolidayController;
use App\Http\Controllers\HR\PayrollController;
use App\Http\Controllers\HR\PaySlipController;
use App\Http\Controllers\HR\SettingController;
use App\Http\Controllers\HR\TaxSlabController;
use App\Http\Controllers\HR\TeacherController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\OvertimeController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\HR\AgentTypeController;
use App\Http\Controllers\HR\AllowanceController;
use App\Http\Controllers\HR\AssetTypeController;
use App\Http\Controllers\HR\AttendanceDashboard;
use App\Http\Controllers\HR\SalaryTaxController;
use App\Http\Controllers\HR\workShiftController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\DepartmentController;
use App\Http\Controllers\HR\ProfitFundController;
use App\Http\Controllers\HR\DesignationController;
use App\Http\Controllers\HR\ManageLeaveController;
use App\Http\Controllers\EmployeeBenefitController;
use App\Http\Controllers\HR\ChildBenefitController;
use App\Http\Controllers\HR\EmployeeTypeController;
use App\Http\Controllers\HR\LeaveRequestController;
use App\Http\Controllers\HR\PayrollReportController;
use App\Http\Controllers\HR\EmployeeLeavesController;
use App\Http\Controllers\HR\SocialSecurityController;
use App\Http\Controllers\HR\AdvanceApprovalController;
use App\Http\Controllers\HR\AgentCommissionController;
use App\Http\Controllers\HR\EmployeeWelfareController;
use App\Http\Controllers\HR\PayrollApprovalController;
use App\Http\Controllers\HR\EmployeeAllowanceController;
use App\Http\Controllers\HR\CalculateComissionController;
use App\Http\Controllers\HR\StudentAssignSessionController;
use App\Http\Controllers\HR\TeacherAssignSessionController;
use App\Http\Controllers\HR\ApproveLeaveController;

Route::group(['middleware' => ['auth'], 'prefix' => 'hr', 'as' => 'hr.'], function () {
    //    for attendance detail
    Route::get('attendance/dashboard', [AttendanceDashboard::class, 'index'])->name('attendanceDashboard.dashboard');
    Route::get('/attendance/detail/{employeeId?}', [AttendanceDashboard::class, 'attendanceDetail'])->name('attendance.detail');

    Route::resource('agent', AgentController::class);
    Route::resource('department', DepartmentController::class);
    Route::resource('agent_type', AgentTypeController::class);
    Route::resource('teacher', TeacherController::class);
    Route::resource('teacher_assign_session', TeacherAssignSessionController::class);
    Route::resource('student_assign_session', StudentAssignSessionController::class);
    Route::resource('employee_type', EmployeeTypeController::class);
    Route::resource('employee', EmployeeController::class);
    Route::get('add-edit-employee-bankdetails/{id}', [EmployeeController::class, 'addBankDetails'])->name('addEditBankDetail');
    Route::patch('save-employee-bankdetails/{id}', [EmployeeController::class, 'saveBankDetails'])->name('employee.bank.save');

    //New Route
    

    
    Route::resource('payroll', PayrollController::class);
    Route::resource('salary_slip', PaySlipController::class);

    Route::get('/payroll-form', [PayrollController::class, 'GeneratePayroll'])->name('payroll.data');
    Route::get('/payroll-filter-data', [PayrollController::class, 'GeneratePayrollFilterData'])->name('payroll.filter_data');
    Route::post('/save-salary-data', [PayrollController::class, 'store'])->name('salary.data');
    Route::get('/holiday-employees', [HolidayController::class, 'holidayEmployee'])->name('holiday.employees');
    Route::get('/holiday-departments', [HolidayController::class, 'holidayDepartment'])->name('holiday.departments');

    //    for branches
    Route::get('/fetch-branches', [EmployeeController::class, 'fetchBranches'])->name('fetch.branches');

    Route::get('/fetch-departments', [EmployeeController::class, 'fetchDepartment'])->name('fetch.departments');
    Route::get('/fetch-designation', [EmployeeController::class, 'fetchDesignation'])->name('fetch.designations');

    Route::post('/sync_employee_attendance', [EmployeeController::class, 'sync_employee_attendance'])->name('sync_employee_attendance');

    Route::resource('attendance', AttendanceController::class);
    Route::resource('agent_comission', AgentCommissionController::class);
    Route::resource('employee_leaves', EmployeeLeavesController::class);

    Route::resource('calculate_comission', CalculateComissionController::class);

    Route::get('new_incentive', [CalculateComissionController::class, 'new_incentive'])->name('new_incentive');
    Route::get('new_incentive_index', [CalculateComissionController::class, 'new_incentive_index'])->name('new_incentive_index');
    Route::post('new_incentive_post', [CalculateComissionController::class, 'new_incentive_post'])->name('new_incentive_store');
    Route::post('fetch_agent_student', [CalculateComissionController::class, 'get_agents_student'])->name('fetch_agent_student');
    Route::get('new_incentive_print/{id}', [CalculateComissionController::class, 'new_incentive_print'])->name('new_incentive_print');

    Route::get('new_sale_recovery_index', [CalculateComissionController::class, 'new_sale_recovery_index'])->name('new_sale_recovery_index');
    Route::get('new_sale_recovery_create', [CalculateComissionController::class, 'new_sale_recovery_create'])->name('new_sale_recovery_create');
    Route::post('new_sale_recovery_store', [CalculateComissionController::class, 'new_sale_recovery_store'])->name('new_sale_recovery_store');
    Route::post('fetch_agent_student_recovery', [CalculateComissionController::class, 'get_agents_student_recovery'])->name('fetch_agent_student_recovery');
    Route::post('recovery_incentive_post', [CalculateComissionController::class, 'recovery_incentive_post'])->name('recovery_incentive_post');
    Route::get('new_recovery_print/{id}', [CalculateComissionController::class, 'new_recovery_print'])->name('new_recovery_print');

    //for setting
    Route::post('/settings/update', [SettingController::class, 'updateSetting'])->name('update.setting');

    Route::resource('holidays', HolidayController::class);
    Route::resource('designations', DesignationController::class);
    Route::resource('qouta_sections', QuottaController::class);
    Route::resource('work_shifts', workShiftController::class);
    Route::resource('leave_requests', LeaveRequestController::class);
    Route::resource('manage_leaves', ManageLeaveController::class);
    Route::resource('tax-slabs', TaxSlabController::class);
    Route::resource('salary-tax', SalaryTaxController::class);
    Route::resource('eobis', EobiController::class);
    Route::resource('profit-funds', ProfitFundController::class);
    Route::resource('settings', SettingController::class);
    Route::resource('child-benefits', ChildBenefitController::class);
    Route::resource('allowances', AllowanceController::class);
    Route::resource('employee-allowances', EmployeeAllowanceController::class);
    Route::resource('social-security', SocialSecurityController::class);
    Route::resource('advances', AdvanceController::class);
    Route::resource('advances_approval', AdvanceApprovalController::class);
    Route::resource('employee-welfare', EmployeeWelfareController::class);
    Route::resource('overtime', OvertimeController::class);
    Route::resource('asset_type', AssetTypeController::class);
    Route::resource('asset', AssetController::class);

    // employeeBenefit
    Route::get('/employeeBenefit/{type}', [EmployeeBenefitController::class,'index'])->name('employeeBenefit');
    Route::post('/employeeBenefit/show', [EmployeeBenefitController::class,'show'])->name('employeeBenefit.show');


    Route::get('overtime-report/view', [OvertimeController::class, 'reportView'])->name('overtime-report-view');
    Route::get('overtime-report', [OvertimeController::class, 'report'])->name('overtime-report');
    
    //   for status
    Route::post('designation-status', [DesignationController::class, 'changeStatus'])->name('designation.change-status');
    Route::post('company-status', [CompanyController::class, 'changeStatus'])->name('company.change-status');
    Route::post('allowance-status', [AllowanceController::class, 'changeStatus'])->name('allowance.change-status');
    Route::post('branch-status', [BranchController::class, 'changeStatus'])->name('branch.change-status');
    Route::post('department-status', [DepartmentController::class, 'changeStatus'])->name('department.change-status');
    Route::post('advance-status', [AdvanceApprovalController::class, 'changeStatus'])->name('advanceApproval.change-status');
    Route::post('employee-status', [EmployeeController::class, 'changeStatus'])->name('employee.change-status');

    Route::get('leave-status/{id}', [ManageLeaveController::class, 'changeStatus'])->name('manage_leaves.status');
    Route::get('leave-detail/{id}', [ManageLeaveController::class, 'detail'])->name('manage_leaves.detail');

    Route::post('/workshift/change-status', [workShiftController::class, 'changeStatus'])->name('WorkShift-years.change-status');
    Route::post('/leave_balance', [ManageLeaveController::class, 'leave_balance'])->name('leave_balance');
    Route::get('/leave-type', [LeaveRequestController::class, 'employee_leave'])->name('leave_type');

    //    for fetch departments
    Route::get('fetch-department', [AttendanceController::class, 'fetchDepartment'])->name('fetchDepartment');
    Route::get('fetch-employee', [AttendanceController::class, 'fetchEmployee'])->name('fetchEmployee');

    Route::post('overtimeData', [OvertimeController::class, 'overtimeData'])->name('overtime.data');

    Route::post('EmployeesAttendance', [AttendanceController::class, 'employeesAttendance'])->name('attendance.EmployeesAttendance');
    Route::post('eobiData', [EobiController::class, 'eobiData'])->name('eobi.data');
    Route::post('socialSecurity', [SocialSecurityController::class, 'socialSecurityData'])->name('social-security.data');
    Route::post('profitData', [ProfitFundController::class, 'profitData'])->name('profitFund.data');
    Route::post('employeeWelfareData', [EmployeeWelfareController::class, 'employeeWelfareData'])->name('employeeWelfare.data');
    //for bulk delete
    Route::post('/bulk-action', [AttendanceController::class, 'handleBulkAction'])->name('bulk-action');
    Route::post('/designationBulk-action', [DesignationController::class, 'handleBulkAction'])->name('designation-bulk');
    Route::post('/branchesBulk-action', [BranchController::class, 'handleBulkAction'])->name('branches-bulk');
    Route::post('/companyBulk-action', [CompanyController::class, 'handleBulkAction'])->name('company-bulk');
    Route::post('/employeeBulk-action', [EmployeeController::class, 'handleBulkAction'])->name('employee-bulk');
    Route::post('/financialBulk-action', [\App\Http\Controllers\FinancialController::class, 'handleBulkAction'])->name('financialYears-bulk');

    //    for sync data

    Route::post('sync-data', [BranchController::class, 'syncData'])->name('branch.sync-data');
    Route::post('employee-data', [EmployeeController::class, 'syncData'])->name('employee.add-data');
    Route::get('add_compensatory_leaves', [ManageLeaveController::class, 'add_compensatory_leaves'])->name('add_compensatory_leaves');
    //for employeefetch
    Route::post('/get-employee-salary', [AdvanceController::class, 'getEmployeeSalary'])->name('employee.salary');


    //    Route::get('/fetch-employees', [EmployeeController::class, 'fetchEmployees'])->name('fetchEmployees');
    Route::get('/fetch-socialEmployees', [SocialSecurityController::class, 'fetchSocialEmployees'])->name('fetchSocialEmployees');

    //    for payroll
    Route::get('payroll_approve', [PayrollApprovalController::class, 'index'])->name('payroll.approve');
    Route::get('payroll_report', [PayrollReportController::class, 'index'])->name('payroll.report');
    Route::post('payroll_report/search', [PayrollReportController::class, 'fetchFilterRecord'])->name('payroll_report.search');

    Route::get('payroll_status/{id}', [PayrollApprovalController::class, 'payrollStatus'])->name('payroll.status');
    Route::get('salarySlip/{id}', [PaySlipController::class, 'salarySlip'])->name('salary.slip');
    Route::get('payroll_status_approve/{id}', [PayrollApprovalController::class, 'payroll_status_approve'])->name('payroll.status.approve');
    Route::get('payroll_status_reject/{id}', [PayrollApprovalController::class, 'payroll_status_reject'])->name('payroll.status.reject');

    //    for salary disburse
    Route::get('salary_disburse', [PayrollApprovalController::class, 'salaryDisburseIndex'])->name('salary.disburse');
    Route::get('salary_disburse_status/{id}', [PayrollApprovalController::class, 'salaryDisburseStatus'])->name('salary.disburse.status');
    Route::get('salary_disburse/{id}', [PayrollApprovalController::class, 'salary_disburse'])->name('salary.disburse.process');

    Route::get('/export/pdf', [AttendanceDashboard::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/pdf1', [AttendanceDashboard::class, 'exportPdf1'])->name('export.pdf1');
    Route::get('/export/excel', [AttendanceController::class, 'exportExcel'])->name('export.excel');

    Route::post('/import-employee', [EmployeeController::class, 'import'])->name('import.employee');

    Route::get('approve_leaves', [ApproveLeaveController::class, 'index'])->name('approve_leaves.index');
    Route::post('approve_leaves/approve/{id}', [ApproveLeaveController::class, 'approve'])->name('approve_leaves.approve');
    Route::post('approve_leaves/reject/{id}', [ApproveLeaveController::class, 'reject'])->name('approve_leaves.reject');

});


