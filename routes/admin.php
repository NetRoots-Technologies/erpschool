<?php

use App\Http\Controllers\Admin\BudgetController;
use App\Http\Controllers\Reports\LedgerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\FinancialzkController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\FeeHeadController;
use App\Http\Controllers\Admin\FeeTermController;
use App\Http\Controllers\Admin\LedgersController;
use App\Http\Controllers\Admin\BankFileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DataBankController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\FeeFactorController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\FeeSectionController;
use App\Http\Controllers\Admin\StudentFeeController;
use App\Http\Controllers\Admin\AccountHeadController;
use App\Http\Controllers\Admin\FeeCategoryController;
use App\Http\Controllers\Admin\VideoUploadController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\BankBranchesController;
use App\Http\Controllers\Admin\BanksAccountController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\FeeCollectionController;
use App\Http\Controllers\Admin\BillGenerationController;
use App\Http\Controllers\Admin\MarketingVideoController;
use App\Http\Controllers\Admin\MarketingBannerAdController;
use App\Http\Controllers\Admin\PromotionalMessageController;
use App\Http\Controllers\Admin\MarketingNotificationController;
use App\Http\Controllers\Admin\SignatoryAuthorityController;

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::get('/signatory-authorities', [SignatoryAuthorityController::class, 'index'])->name('signatory-authorities.index');
    Route::get('get-signatory-authorities', [SignatoryAuthorityController::class, 'getData'])->name('signatory-authorities.get');
    Route::get('add-signatory-authorities', [SignatoryAuthorityController::class, 'create'])->name('signatory-authorities.add');
    Route::post('/signatory-authorities', [SignatoryAuthorityController::class, 'store'])->name('signatory-authorities.store');
    Route::get('signatory-authorities/{id}/edit', [SignatoryAuthorityController::class, 'edit'])->name('signatory-authorities.edit');
    Route::put('signatory-authorities/{id}', [SignatoryAuthorityController::class, 'update'])->name('signatory-authorities.update');
    Route::delete('signatory-authorities/{id}', [SignatoryAuthorityController::class, 'destroy'])->name('signatory-authorities.destroy');

    Route::resource('coursetype', \App\Http\Controllers\Admin\CourseTypeController::class);
    //    Route::resource('course', \App\Http\Controllers\Admin\CourseController::class);
    Route::resource('session', \App\Http\Controllers\Admin\SessionController::class);
    Route::get('states', '\App\Http\Controllers\Student\StudentController@states')->name('states');
    Route::get('cities', '\App\Http\Controllers\Student\StudentController@cities')->name('cities');
    Route::resource('student_databank', \App\Http\Controllers\Admin\DataBankController::class);
    Route::get("walk_in_student_create", [DataBankController::class, 'walk_in_student_view'])->name('walk_in_student_view');
    Route::post("walk_in_student", [DataBankController::class, 'walk_in_student'])->name('walk_in_student');
    //    Route::post("walk_in_student_delete", [DataBankController::class, 'walk_in_student_delete'])->name('walk_in_student_delete');

    Route::get("walk_in_student", [DataBankController::class, 'walk_in_student_get'])->name('walk_in_student.get');
    Route::post("view_data_bank_courses1/{id}", [DataBankController::class, 'view_data_bank_courses'])->name('view_data_bank_courses');
    //    Route::resource('student_fee', \App\Http\Controllers\Fee\StudentFeeController::class);
//    Route::get('student_fee/create/{id}', [\App\Http\Controllers\Fee\StudentFeeController::class, 'create1'])->name('student_fee.create1');
//    Route::resource('paid_student_fee', \App\Http\Controllers\Fee\PaidStudentFeeController::class);
////    Route::resource('student_fee', \App\Http\Controllers\Fee\PaidStudentFeeController::class);
//    Route::get('fee', [StudentFeeController::class, 'get_fee'])->name('fee');
//    Route::get('course/session/get', [StudentFeeController::class, 'get_sessions'])->name('session.get');
//
//    Route::get('fee_paid_date/{id}', [StudentFeeController::class, 'update_fee_paid_date'])->name('fee_paid_date');
//    Route::get('student/feedetail/{id}', [StudentFeeController::class, 'student_paid_fee_detail'])->name('fee_paid_detail');
//
//    Route::get('student/discount_on_instalment/{id}', [StudentFeeController::class, 'discount_on_instalment'])->name('discount_on_instalment');
//    Route::post('student/discount_on_instalment_post/{id}', [StudentFeeController::class, 'discount_on_instalment_post'])->name('discount_on_instalment_post');
//
//    Route::get('student/make_defaulter/{id}', [StudentFeeController::class, 'make_defaulter'])->name('make_defaulter');
//    Route::get('student/make_defaulter_reactive/{id}', [StudentFeeController::class, 'make_defaulter_reactive'])->name('make_defaulter_reactive');
//
//    Route::delete('student/feedetail/delete/{id}', [StudentFeeController::class, 'fee_paid_detail_delete'])->name('fee_paid_detail_delete');
//
//    Route::get('student/feedetail/edit/{id}', [StudentFeeController::class, 'fee_paid_detail_edit'])->name('fee_paid_detail_edit');
//    Route::post('student/feedetail/edit/{id}', [StudentFeeController::class, 'fee_paid_detail_edit_post'])->name('fee_paid_detail_edit_post');
//
//    Route::get('student/remaining_fee/{id}', [StudentFeeController::class, 'remaining_fee'])->name('remaining_fee.get');
//    Route::post('student/remaining_fee/{id}', [StudentFeeController::class, 'remaining_fee_post'])->name('remaining_fee.post');
//    Route::post('student_fee_paid/{id}', [StudentFeeController::class, 'student_fee_paid'])->name('student_fee_paid');
//    Route::get('student_fee_voucher/{id}', [StudentFeeController::class, 'student_fee_voucher'])->name('student_fee_voucher');
//
//    Route::resource('video', \App\Http\Controllers\Admin\VideoUploadController::class);
//    Route::resource('video_category', \App\Http\Controllers\Admin\VideoCategoryController::class);
//    Route::get('session_videos/{id}', [VideoUploadController::class, 'session_videos'])->name('session_videos');
//
//    //fee filters
//    Route::get('get_course_session/', [StudentFeeController::class, 'get_course_session'])->name('get_course_session');
//    Route::get('feepaidmorethan30k', [StudentFeeController::class, 'feepaidmorethan30k'])->name('feepaidmorethan30k');
//
//    //fee filters end
//
//    Route::resource('tools', \App\Http\Controllers\Admin\ToolsController::class);
//    Route::get('assign_tools/{student_id}', [ToolsController::class, 'assign_tools_get'])->name('assign_tools_get');
//    Route::post('assign_tools', [ToolsController::class, 'assign_tools_post'])->name('assign_tools_post');
//    Route::delete('assign_tools_delete/{id}', [ToolsController::class, 'assign_tools_delete'])->name('assign_tools_delete');
//
//    Route::get('assign_certificate/{id}', [StudentFeeController::class, 'assign_certificate'])->name('assign_certificate');
//

    //    Marketing Banner AD
//    Route::resource('marketing_banner_ad', \App\Http\Controllers\Admin\MarketingBannerAdController::class);
//    Route::get('marketing_banner_ad/{id}', [\App\Http\Controllers\Admin\MarketingBannerAdController::class, 'getData'])->name('ad_getData');


    Route::resource('marketing_banner_ad', \App\Http\Controllers\Admin\MarketingBannerAdController::class);
    Route::post('getdata/marketing_banner_ad', [MarketingBannerAdController::class, 'getdata'])->name('marketing_banner_ad_getdata');


    //    Marketing Notifications
//    Route::resource('marketing_notification', \App\Http\Controllers\Admin\MarketingNotificationController::class);
////    Route::get('marketing_notification/{id}', [\App\Http\Controllers\Admin\MarketingNotificationController::class, 'session_videos'])->name('session_videos');
//

    Route::resource('marketing_notification', \App\Http\Controllers\Admin\MarketingNotificationController::class);
    Route::post('getdata/marketing_notification', [MarketingNotificationController::class, 'getdata'])->name('marketing_notification_getdata');


    Route::resource('marketing_video', MarketingVideoController::class);
    Route::post('getdata/marketing_video', [MarketingVideoController::class, 'getdata'])->name('marketing_video_getdata');

    Route::get('promotional_messages', [PromotionalMessageController::class, 'promotional_messages_index'])->name('promotional_messages');
    //    Route::post('promotional_messages_send', [PromotionalMessageController::class,'promotional_messages_send'])->name('promotional_messages_send');
    Route::post('get_student_with_course', [PromotionalMessageController::class, 'get_student_with_course'])->name('get_student_with_course');
    Route::post('get_student', [PromotionalMessageController::class, 'get_student'])->name('get_student');


    Route::get('website_leads_view', [DataBankController::class, 'website_leads_view'])->name('website_leads_view');

    Route::get('web_form_edit', [SettingsController::class, 'web_form_edit'])->name('web_form_edit');
    Route::post('web_form_edit_post', [SettingsController::class, 'web_form_edit_post'])->name('web_form_edit_post');

    Route::get('bv_form_view', [DataBankController::class, 'bv_form_view'])->name('bv_form_view');
    Route::get('seminar_form_view', [DataBankController::class, 'seminar_form_view'])->name('seminar_form_view');
    Route::get('onezcamp_form_view', [DataBankController::class, 'onezcamp_form_view'])->name('onezcamp_form_view');
    //    Route::get('crone', [StudentFeeController::class, 'crone'])->name('website_leads_view');
    Route::post('departments/company/get', [DepartmentController::class, 'company'])->name('departments.company.get');
    Route::post('departments/branch/get', [DepartmentController::class, 'getdepartments'])->name('departments.branch.get');

    Route::resource('category', CategoryController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('departments', DepartmentController::class);


    Route::resource('academic_years', AcademicYearController::class);
    Route::resource('banks_file', BankFileController::class);

    //    fee cruds

    Route::resource('fee-sections', FeeSectionController::class);
    Route::resource('fee-heads', FeeHeadController::class);
    Route::resource('fee-structure', FeeStructureController::class);
    Route::resource('fee-terms', FeeTermController::class);
    Route::resource('account-head', AccountHeadController::class);
    Route::resource('fee-category', FeeCategoryController::class);
    Route::resource('student-regular-fee', StudentFeeController::class);
    Route::resource('fee-collection', FeeCollectionController::class);
    Route::resource('bill-generation', BillGenerationController::class);
    Route::resource('fee-factor', FeeFactorController::class);
    Route::resource('financial-years', FinancialController::class);
    Route::resource('banks', BankController::class);
    Route::resource('banks_branches', BankBranchesController::class);
    Route::resource('banks_accounts', BanksAccountController::class);

    Route::post('/admin/financial-years/change-status', [FinancialController::class, 'changeStatus'])->name('financial-years.change-status');
    Route::post('import-bankFile', [BankFileController::class, 'import'])->name('import.bankFile');

    //fee status
    Route::post('fee-section-status', [FeeSectionController::class, 'changeStatus'])->name('fee-sections.change-status');
    Route::post('fee-heads-status', [FeeHeadController::class, 'changeStatus'])->name('fee-heads.change-status');
    Route::post('account-head-status', [AccountHeadController::class, 'changeStatus'])->name('account-head.change-status');
    Route::post('fee-category-status', [FeeCategoryController::class, 'changeStatus'])->name('fee-category.change-status');
    //    Route::post('account-head-status', [AccountHeadController::class, 'changeStatus'])->name('account-head.change-status');


    Route::post('/account-head-bulk-action', [AccountHeadController::class, 'handleBulkAction'])->name('account-head-bulk');
    Route::post('/fee-term-bulk-action', [FeeTermController::class, 'handleBulkAction'])->name('fee-terms-bulk');
    Route::post('/fee-student-fee-action', [StudentFeeController::class, 'handleBulkAction'])->name('student-fee-bulk');
    Route::post('/bill-generation-bulk-action', [BillGenerationController::class, 'handleBulkAction'])->name('bill-generation-bulk');
    Route::post('/bankBulk-action', [BankController::class, 'handleBulkAction'])->name('bank-bulk');
    Route::post('/banksAcount-action', [BanksAccountController::class, 'handleBulkAction'])->name('banks_account-bulk');

    Route::post('fee-structure-data', [FeeStructureController::class, 'feeStructureData'])->name('feeStructure.data');
    Route::post('fee-term-data', [FeeTermController::class, 'feeTermData'])->name('feeTerm.data');
    Route::post('fee-student-Regular-Fee', [StudentFeeController::class, 'studentRegularFee'])->name('studentRegularFee.data');
    Route::post('fee-collection-Regular-Fee', [FeeCollectionController::class, 'searchFeeCollection'])->name('fee_collection.search');

    Route::post('fee-factor-status', [FeeFactorController::class, 'changeStatus'])->name('fee-factor.change-status');
    Route::post('bank-branch-change-status', [BankBranchesController::class, 'changeStatus'])->name('bank-branch.change-status');

    Route::post('bill-generation-status', [BillGenerationController::class, 'changeStatus'])->name('bill-generation.change-status');
    Route::post('bank-status', [BankController::class, 'changeStatus'])->name('bank.change-status');
    Route::get('fee-collection-view/{id}', [FeeCollectionController::class, 'view'])->name('fee-collection-view');
    Route::post('fee-collection-make-installments', [FeeCollectionController::class, 'make_installments'])->name('fee-collection-make-installments');
    Route::get('fee-collection-print/{id}', [FeeCollectionController::class, 'print_voucher'])->name('fee-collection-print');


    Route::get('fetch-bankBranch', [BankBranchesController::class, 'fetchBankBranch'])->name('fetch.bankBranches');

    //notification controller get-count, markNotificationAsRead

    Route::get('notification/read', [NotificationController::class, 'getNotifications'])->name('notification');
    Route::get('notification/unread', [NotificationController::class, 'getUnreadCount'])->name('getUnreadCount');
    Route::post('notifications/approve', [NotificationController::class, 'approve'])->name('notifications.approve');
    Route::post('notifications/pending', [NotificationController::class, 'pending'])->name('notifications.pending');

    Route::post('listing', [LedgerController::class, 'coaListing'])->name('listing');
    Route::post('/coa/{id}/toggle-status', [LedgerController::class, 'toggleStatus'])
        ->name('coa.toggleStatus');
    //Inventory Routes
    // Route::resource('/inventory/budget/',BudgetController::class);
    // Route::get('/inventory',function()
    // {
    //     return 'hello';
    // });
});
