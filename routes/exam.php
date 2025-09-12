<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Exam\SkillsController;
use App\Http\Controllers\Exam\ExamTermController;
use App\Http\Controllers\Exam\TestTypeController;
use App\Http\Controllers\Exam\ComponentController;
use App\Http\Controllers\Exam\SkillTypeController;
use App\Http\Controllers\Exam\BehavioursController;
use App\Http\Controllers\Exam\ExamDetailController;
use App\Http\Controllers\Exam\MarksInputController;
use App\Http\Controllers\Exam\SkillGroupController;
use App\Http\Controllers\Exam\ClassSubjectController;
use App\Http\Controllers\Exam\EffortLevelsController;
use App\Http\Controllers\Exam\ExamScheduleController;
use App\Http\Controllers\Exam\SubComponentController;
use App\Http\Controllers\Exam\GradingPoliciesController;
use App\Http\Controllers\Exam\SkillEvaluationController;
use App\Http\Controllers\Exam\AcademicEvaluationController;
use App\Http\Controllers\Exam\SkillEvaluationKeyController;

Route::group(['middleware' => ['auth'], 'prefix' => 'exam', 'as' => 'exam.'], function () {

    Route::resource('exam_terms', ExamTermController::class);
    Route::get('/exam-terms/generate-term-id', [ExamTermController::class, 'generateTermId'])->name('exam_terms.generate_term_id');

    Route::resource('test_types', TestTypeController::class);
    Route::resource('exam_details', ExamDetailController::class);
    Route::resource('components', ComponentController::class);
    Route::resource('skills', SkillsController::class);
    Route::resource('skill_evaluation', SkillEvaluationController::class);
    Route::resource('skill_evaluations_key', SkillEvaluationKeyController::class);
    Route::resource('behaviours', BehavioursController::class);
    Route::resource('effort_levels', EffortLevelsController::class);
    Route::resource('grading_policies', GradingPoliciesController::class);
    Route::resource('academic_evaluations_key', AcademicEvaluationController::class);
    Route::resource('class_subjects', ClassSubjectController::class);
    Route::resource('skill_groups', SkillGroupController::class);
    Route::resource('skill_types', SkillTypeController::class);
    Route::resource('exam_schedules', ExamScheduleController::class);
    Route::get('/get-data-on-edit/{exam_schedule_id}',[ExamScheduleController::class,'getDataOnEdit'])->name('getDataOnEdit');
    Route::resource('sub_components', SubComponentController::class);
    Route::resource('marks_input', MarksInputController::class);



    //    for bulk delete

    Route::post('/exam-term-bulk-action', [ExamTermController::class, 'handleBulkAction'])->name('exam-term-bulk');
    Route::post('/marks-input-bulk-action', [MarksInputController::class, 'handleBulkAction'])->name('marks-input-bulk');
    Route::post('/test-type-bulk-action', [TestTypeController::class, 'handleBulkAction'])->name('test-type-bulk');
    Route::post('/exam-detail-bulk-action', [ExamDetailController::class, 'handleBulkAction'])->name('exam-details-bulk');
    Route::post('/exam-detail-change-status', [ExamDetailController::class, 'changeStatus'])->name('change-status');
    Route::post('/exam-component-bulk-action', [ComponentController::class, 'handleBulkAction'])->name('component-bulk');
    Route::post('/skills-bulk-action', [SkillsController::class, 'handleBulkAction'])->name('skills-bulk');
    Route::post('/exam-skill-evaluation-bulk-action', [SkillEvaluationController::class, 'handleBulkAction'])->name('skill-evaluation-bulk');
    Route::post('/exam-skill-evaluation-key-bulk-action', [SkillEvaluationKeyController::class, 'handleBulkAction'])->name('skill-evaluation-key-bulk');
    Route::post('/behaviours-bulk-action', [BehavioursController::class, 'handleBulkAction'])->name('behaviours-bulk');
    Route::post('/effort-levels-bulk-action', [EffortLevelsController::class, 'handleBulkAction'])->name('effort_levels_bulk');
    Route::post('/grading-policies-bulk-action', [GradingPoliciesController::class, 'handleBulkAction'])->name('grading_policies_bulk');
    Route::post('/exam-academic-evaluation-bulk-action', [AcademicEvaluationController::class, 'handleBulkAction'])->name('academic-evaluation-bulk');
    Route::post('/exam-class-subject-bulk-action', [ClassSubjectController::class, 'handleBulkAction'])->name('class-subject-bulk');
    Route::post('/exam-skill-group-bulk-action', [SkillGroupController::class, 'handleBulkAction'])->name('skill-group-bulk');
    Route::post('/exam-skill-types-bulk-action', [SkillTypeController::class, 'handleBulkAction'])->name('skill-type-bulk');
    Route::post('/exam-component-bulk-action', [ComponentController::class, 'handleBulkAction'])->name('components-bulk');
    Route::post('/exam-sub-component-bulk-action', [SubComponentController::class, 'handleBulkAction'])->name('sub-components-bulk');

    //    for status
    Route::post('test-type-status', [TestTypeController::class, 'changeStatus'])->name('test-type.change-status');
    Route::post('component-status', [ComponentController::class, 'changeStatus'])->name('components.change-status');
    Route::post('skills-status', [SkillsController::class, 'changeStatus'])->name('skills.change-status');
    Route::post('skillEvaluation-status', [SkillEvaluationController::class, 'changeStatus'])->name('skillEvaluation.change-status');
    Route::post('skillEvaluationKey-status', [SkillEvaluationKeyController::class, 'changeStatus'])->name('skillEvaluationKey.change-status');
    Route::post('behaviours-status', [BehavioursController::class, 'changeStatus'])->name('behaviours.change-status');
    Route::post('effort-levels-status', [EffortLevelsController::class, 'changeStatus'])->name('effort_levels.change-status');
    Route::post('grading-policies-status', [GradingPoliciesController::class, 'changeStatus'])->name('grading_policies.change-status');
    Route::post('academicEvaluation-status', [AcademicEvaluationController::class, 'changeStatus'])->name('academicEvaluation.change-status');
    Route::post('skillGroup-status', [SkillGroupController::class, 'changeStatus'])->name('skillGroup.change-status');


    //    for subjects

    Route::get('fetch-exam-subjects', [SkillTypeController::class, 'fetchExamSubject'])->name('fetchSubjects');
    Route::get('fetch-component-data', [SubComponentController::class, 'fetchSubComponent'])->name('fetchSubComponent');
    Route::get('fetch-mark-input', [SubComponentController::class, 'fetchMarks'])->name('fetchMarks');


    Route::post('classSubjectData', [ExamScheduleController::class, 'classSubjectData'])->name('classSubject.data');
    Route::post('component_data', [ExamScheduleController::class, 'component_data'])->name('component.data');
    Route::get('studentSubjectsWithEvaluation', [SkillEvaluationController::class, 'studentSubjectsWithEvaluation'])->name('studentSubjectsWithEvaluation');
    Route::post('sub-component_data', [SubComponentController::class, 'sub_component_data'])->name('sub-component.data');

    Route::post('/exam/exam-schedules/bulk-delete', [ExamScheduleController::class, 'bulkDelete'])->name('exam_schedules.bulk_delete');
    Route::post('exam/components', [ComponentController::class, 'clone'])->name('component.clone');
    Route::post('exam/sub_components', [SubComponentController::class, 'clone'])->name('sub-component.clone');

});

