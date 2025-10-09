<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reports\StudentExamReport;






Route::group(['middleware' => ['auth'], 'prefix' => 'reports', 'as' => 'reports.'], function () {
    Route::get('/student/exam', [StudentExamReport::class, 'index'])->name('std.exam.index');
    Route::post('/get-classes',  [StudentExamReport::class, 'getClasses'])->name('exam.getClasses');
    Route::post('/get-sections', [StudentExamReport::class, 'getSections'])->name('exam.getSections');
    Route::post('/get-AcademicSession', [StudentExamReport::class, 'getAcademicSession'])->name('exam.getAcademics');
    Route::get('/exam-report/students', [StudentExamReport::class, 'studentsTable'])->name('exam.studentsTable');
    Route::get('/exam-report/students', [StudentExamReport::class, 'studentsTable'])->name('exam.studentsTable');
    Route::get('/exam-report/view/{student_id}', [StudentExamReport::class, 'viewReport'])->name('exam.view');
    Route::get('/exam-report/print/{student_id}', [StudentExamReport::class, 'printReport'])->name('exam.print');
});

    Route::post('/students/{student_id}/progress-remarks', [\App\Http\Controllers\StudentProgressController::class, 'storeRemarks'])
    ->name('students.progress.remarks.store')->middleware('auth');