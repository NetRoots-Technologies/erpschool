<?php

use App\Http\Controllers\Academic\ActiveSessionController;
use App\Http\Controllers\Academic\AssignClassController;
use App\Http\Controllers\Academic\AssignTimeTableController;
use App\Http\Controllers\Academic\AttendanceController;
use App\Http\Controllers\Academic\ClassController;
use App\Http\Controllers\Academic\ClassTimeTableController;
use App\Http\Controllers\Academic\SchoolTypeController;
use App\Http\Controllers\Academic\SectionController;
use App\Http\Controllers\Academic\StudentViewController;
use App\Http\Controllers\Academic\TeacherController;
use App\Http\Controllers\Academic\TimeTableController;
use App\Http\Controllers\Admin\AccountHeadController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseTypeController;
use App\Http\Controllers\Admin\LedgersController;
use App\Http\Controllers\Exam\ComponentController;
use App\Http\Controllers\Exam\ExamScheduleController;
use App\Http\Controllers\Student\AcademicSessionController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\StudentDataBankController;
use App\Http\Controllers\Student\StudentClassAdjustmentController;

use App\Models\Academic\StudentAttendance;
use Illuminate\Support\Facades\Route;

use App\Exports\StudentSampleExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Admin\Company;
use App\Http\Controllers\Admin\CompanyController;

use App\Models\Admin\Branch;
use App\Http\Controllers\Admin\BranchController;

use App\Models\Employee;
use App\Http\Controllers\HR\EmployeeController;

use App\Models\HR\Designation;
use App\Http\Controllers\HR\DesignationController;

use App\Models\Admin\Department;
use App\Http\Controllers\Admin\DepartmentController;

use  App\Models\HR\Asset;
use App\Http\Controllers\HR\AssetController;
use App\Http\Controllers\HR\AssetTypeController;


Route::group(['middleware' => ['auth'], 'prefix' => 'academic', 'as' => 'academic.'], function () {

    //    cruds route
    Route::resource('students', StudentController::class);
    Route::resource('studentDataBank', StudentDataBankController::class);
    Route::resource('academic-session', AcademicSessionController::class);
    Route::resource('schools', SchoolTypeController::class);
    Route::resource('classes', ClassController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('timetables', TimeTableController::class);
    Route::resource('subject-type', CourseTypeController::class);
    Route::resource('subjects', CourseController::class);
    Route::resource('class_timetable', ClassTimeTableController::class);
    Route::resource('assign_timetable', AssignTimeTableController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('active_sessions', ActiveSessionController::class);
    Route::resource('assign_class', AssignClassController::class);
    Route::resource('student_view', StudentViewController::class);
    Route::resource('student_attendance', AttendanceController::class);
    Route::post('montly-list',[ AttendanceController::class,'monthyList'])->name('montly-list');

    Route::get('student-siblings-report', [StudentController::class, 'report'])->name('student-siblings-report');

    //Student Class Adjustment

    Route::resource('student-class-adjustment',StudentClassAdjustmentController::class);


    Route::get('add-student/{id?}', [StudentDataBankController::class, 'addStudent'])->name('add-student');

    //for status

    Route::post('academicSession-status', [AcademicSessionController::class, 'changeStatus'])->name('academicSession.change-status');
    Route::post('schoolType-status', [SchoolTypeController::class, 'changeStatus'])->name('school.change-status');
    Route::post('class-status', [ClassController::class, 'changeStatus'])->name('class.change-status');
    Route::post('section-status', [SectionController::class, 'changeStatus'])->name('section.change-status');
    Route::post('courseType-status', [CourseTypeController::class, 'changeStatus'])->name('course-type.change-status');
    Route::post('courses-status', [CourseController::class, 'changeStatus'])->name('courses.change-status');


    //    for bulk delete
    Route::post('/academicSession-action', [AcademicSessionController::class, 'handleBulkAction'])->name('academic-session-bulk');
    Route::post('/school-action', [SchoolTypeController::class, 'handleBulkAction'])->name('school-type-bulk');
    Route::post('/class-action', [ClassController::class, 'handleBulkAction'])->name('classes-bulk');
    Route::post('/section-action', [SectionController::class, 'handleBulkAction'])->name('section-bulk');
    Route::post('/course_type-action', [CourseTypeController::class, 'handleBulkAction'])->name('courseType-bulk');
    Route::post('/student_bulk-action', [StudentController::class, 'handleBulkAction'])->name('student-bulk');
    Route::post('/studentDataBank-bulk-action', [StudentDataBankController::class, 'handleBulkAction'])->name('studentDataBank-bulk');
    Route::post('/academic-studentAttendance-action', [AttendanceController::class, 'handleBulkAction'])->name('studentAttendance-bulk');

    //    for fetch data
    Route::get('fetch-academic-branches', [ClassTimeTableController::class, 'fetchAcademicBranch'])->name('fetchAcademicBranch');
    Route::get('fetch-academic-sections', [ClassTimeTableController::class, 'fetchAcademicSection'])->name('fetchSections');
    Route::get('fetch-academic-courses', [ClassTimeTableController::class, 'fetchAcademicCourse'])->name('fetchCourse');
    Route::get('fetch-academic-class', [CourseController::class, 'fetchAcademicClass'])->name('fetchClass');
    Route::get('fetch-academic-classes', [CourseController::class, 'fetchAcademicClass'])->name('fetchClass');
    Route::get('fetch-academic-subject', [CourseController::class, 'fetchSubject'])->name('fetchSubject');
    Route::get('fetch-academic-timetable', [ClassTimeTableController::class, 'fetchAcademicTime'])->name('fetchTimetable');
    Route::get('fetch-academic-session', [ClassController::class, 'fetchAcademicSession'])->name('fetch.sessions');
    Route::get('fetch-academic-courseTimetable', [AssignTimeTableController::class, 'fetch_courseTimeTable'])->name('fetchCourse.Timetable');
    Route::get('fetch-academic-activeSessions', [ActiveSessionController::class, 'fetch_activeSession'])->name('fetch.active_sessions');
    Route::get('fetch-academic-schoolTypes', [ActiveSessionController::class, 'fetch_schoolType'])->name('fetch.schools');
    Route::get('fetch-academic-siblingClass', [StudentController::class, 'fetch_siblingClass'])->name('fetch.siblingClass');
    Route::get('fetch-academic-siblingDob', [StudentController::class, 'fetch_siblingDob'])->name('fetch_siblingDob');
    Route::get('fetch-academic-studentId', [StudentController::class, 'fetchStudentData'])->name('fetch_studentId');
    Route::get('fetch-academic-rollNo', [StudentController::class, 'StudentRollNo'])->name('fetchRollNo');
    Route::get('fetch-academic-empRoll', [StudentController::class, 'EmpCode'])->name('fetchEmpNo');
    Route::get('fetch-academic-student', [AttendanceController::class, 'fetchStudent'])->name('fetchSectionStudent');
    Route::get('fetch-academic-examTerm', [ExamScheduleController::class, 'fetchExamTerm'])->name('fetchExamTerm');
    Route::get('fetch-academic-examSubject', [ExamScheduleController::class, 'fetch_class_subject'])->name('fetchSubjects');

        Route::get('fetch-academic-examSubject', [ExamScheduleController::class, 'fetch_class_subjects'])->name('fetchSubject');

    Route::get('fetch-exam-componentSubject', [ComponentController::class, 'fetchComponentSubject'])->name('fetchComponentSubject');



    Route::post('fetch-student-data', [AttendanceController::class, 'StudentData'])->name('student.data');



    Route::get('student-attendance/{id}/pdf', [AttendanceController::class, 'generatePdf'])->name('student_attendance.pdf');
    Route::get('student_attendance/monthly/pdf', [AttendanceController::class, 'generateMonthlyPdf'])
    ->name('student_attendance.monthly.pdf');

    Route::post('/import-studentAttendance', [AttendanceController::class, 'import'])->name('import.studentAttendance');

    Route::get('fetch-cnic-student', [StudentController::class, 'fetchCnicStudentData'])->name('fetchCnicStundent');


    //For Studnet Export File

    Route::get('/export-file-student',[StudentController::class,'exportbulkfile'])->name('student.export-file');
    Route::post('/import-file-student',[StudentController::class,'importBulkFile'])->name('student.import-file');


    Route::get('/export-file-class',[ClassController::class,'exportbulkfile'])->name('class.export-file');
    Route::post('/import-file-class',[ClassController::class,'importBulkFile'])->name('class.import-file');

    Route::get('/export-file-section',[SectionController::class,'exportbulkfile'])->name('section.export-file');
    Route::post('/import-file-section',[SectionController::class,'importBulkFile'])->name('section.import-file');

    // for Academic  Company
    Route::get('/export-file-company',[CompanyController::class,'exportbulkfile'])->name('company.export-file');
    Route::post('/import-file-company',[CompanyController::class,'importBulkFile'])->name('company.import-file');


    // for Academic  branch
    Route::get('/export-file-branch',[BranchController::class,'exportbulkfile'])->name('branch.export-file');
    Route::post('/import-file-branch',[BranchController::class,'importBulkFile'])->name('branch.import-file');

    // for Academic  employee
    Route::get('/export-file-employee',[EmployeeController::class,'exportbulkfile'])->name('employee.export-file');
    Route::post('/import-file-employee',[EmployeeController::class,'importBulkFile'])->name('employee.import-file');

    // for Academic  designation
    Route::get('/export-file-designation',[DesignationController::class,'exportbulkfile'])->name('designation.export-file');
    Route::post('/import-file-designation',[designationController::class,'importBulkFile'])->name('designation.import-file');

     // for Academic  Department
    Route::get('/export-file-department',[DepartmentController::class,'exportbulkfile'])->name('department.export-file');
    Route::post('/import-file-department',[DepartmentController::class,'importBulkFile'])->name('department.import-file');

       // for Academic  Subject
    Route::get('/export-file-subjects',[CourseController::class,'exportbulkfile'])->name('subjects.export-file');
    Route::post('/import-file-subjects',[CourseController::class,'importBulkFile'])->name('subjects.import-file');


    // for acadeic pre admission form

    Route::get('/export-file-pre-admission',[StudentDataBankController::class,'exportbulkfile'])->name('pre-admission.export-file');
    Route::post('/import-file-pre-admission',[StudentDataBankController::class,'importBulkFile'])->name('pre-admission.import-file');
          // for Academic  Asset
    Route::get('/export-file-asset',[AssetController::class,'exportbulkfile'])->name('asset.export-file');
    Route::post('/import-file-asset',[AssetController::class,'importBulkFile'])->name('asset.import-file');


    //foor Academic Session
    Route::get('/export-file-session',[AcademicSessionController::class,'exportBulkFile'])->name('session.export-file');
    Route::post('/import-file-session',[AcademicSessionController::class,'importBulkFile'])->name('session.import-file');

    Route::post('courses/clone', [CourseController::class, 'clone'])->name('courses.clone');
});


