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
use App\Http\Controllers\Admin\LedgersController;
use App\Http\Controllers\Admin\BankFileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DataBankController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\VideoUploadController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\BankBranchesController;
use App\Http\Controllers\Admin\BanksAccountController;
use App\Http\Controllers\Admin\MarketingVideoController;
use App\Http\Controllers\Admin\MarketingBannerAdController;
use App\Http\Controllers\Admin\PromotionalMessageController;
use App\Http\Controllers\Admin\MarketingNotificationController;
use App\Http\Controllers\Admin\SignatoryAuthorityController;
use App\Http\Controllers\Fee\FeeCategoryController;
use App\Http\Controllers\Fee\FeeHeadController;
use App\Http\Controllers\Fee\FeeStructureController;
use App\Http\Controllers\Fee\FeeCollectionController;
use App\Http\Controllers\Fee\FeeDiscountController;
use App\Http\Controllers\Fee\FeeTermController;
use App\Http\Controllers\Fee\FeeSectionController;
use App\Http\Controllers\Fee\ChallanController;

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

    Route::resource('financial-years', FinancialController::class);
    Route::resource('banks', BankController::class);
    Route::resource('banks_branches', BankBranchesController::class);
    Route::resource('banks_accounts', BanksAccountController::class);

    Route::post('/admin/financial-years/change-status', [FinancialController::class, 'changeStatus'])->name('financial-years.change-status');
    Route::post('import-bankFile', [BankFileController::class, 'import'])->name('import.bankFile');

    Route::post('/bankBulk-action', [BankController::class, 'handleBulkAction'])->name('bank-bulk');
    Route::post('/banksAcount-action', [BanksAccountController::class, 'handleBulkAction'])->name('banks_account-bulk');

    Route::post('bank-branch-change-status', [BankBranchesController::class, 'changeStatus'])->name('bank-branch.change-status');
    Route::post('bank-status', [BankController::class, 'changeStatus'])->name('bank.change-status');

    Route::get('fetch-bankBranch', [BankBranchesController::class, 'fetchBankBranch'])->name('fetch.bankBranches');

    //notification controller get-count, markNotificationAsRead

    Route::get('notification/read', [NotificationController::class, 'getNotifications'])->name('notification');
    Route::get('notification/unread', [NotificationController::class, 'getUnreadCount'])->name('getUnreadCount');
    Route::post('notifications/approve', [NotificationController::class, 'approve'])->name('notifications.approve');
    Route::post('notifications/pending', [NotificationController::class, 'pending'])->name('notifications.pending');

    Route::post('listing', [LedgerController::class, 'coaListing'])->name('listing');
    Route::post('/coa/{id}/toggle-status', [LedgerController::class, 'toggleStatus'])
        ->name('coa.toggleStatus');

    // Fee Management Routes
    Route::group(['prefix' => 'fee', 'as' => 'fee.'], function () {
        // Fee Categories
        Route::resource('fee-categories', FeeCategoryController::class);
        Route::get('fee-categories-data', [FeeCategoryController::class, 'getdata'])->name('fee-categories.data');
        Route::post('fee-categories-change-status', [FeeCategoryController::class, 'changeStatus'])->name('fee-categories.change-status');
        Route::post('fee-categories-bulk-action', [FeeCategoryController::class, 'handleBulkAction'])->name('fee-categories.bulk-action');

        // Fee Sections
        Route::resource('fee-sections', FeeSectionController::class);
        Route::get('fee-sections-data', [FeeSectionController::class, 'getdata'])->name('fee-sections.data');
        Route::post('fee-sections-change-status', [FeeSectionController::class, 'changeStatus'])->name('fee-sections.change-status');
        Route::post('fee-sections-bulk-action', [FeeSectionController::class, 'handleBulkAction'])->name('fee-sections.bulk-action');

        // Fee Heads
        Route::resource('fee-heads', FeeHeadController::class);
        Route::get('fee-heads-data', [FeeHeadController::class, 'getdata'])->name('fee-heads.data');
        Route::post('fee-heads-change-status', [FeeHeadController::class, 'changeStatus'])->name('fee-heads.change-status');
        Route::post('fee-heads-bulk-action', [FeeHeadController::class, 'handleBulkAction'])->name('fee-heads.bulk-action');

        // Fee Structures
        Route::resource('fee-structures', FeeStructureController::class);
        Route::get('fee-structures-data', [FeeStructureController::class, 'getdata'])->name('fee-structures.data');
        Route::post('fee-structures-change-status', [FeeStructureController::class, 'changeStatus'])->name('fee-structures.change-status');
        Route::post('fee-structures-bulk-action', [FeeStructureController::class, 'handleBulkAction'])->name('fee-structures.bulk-action');

        // Fee Collections
        Route::resource('fee-collections', FeeCollectionController::class);
        Route::get('fee-collections-data', [FeeCollectionController::class, 'getdata'])->name('fee-collections.data');
        Route::post('fee-collections-change-status', [FeeCollectionController::class, 'changeStatus'])->name('fee-collections.change-status');
        Route::get('fee-collections-student-details/{student_id}', [FeeCollectionController::class, 'getStudentFeeDetails'])->name('fee-collections.student-details');
        Route::get('fee-collections-generate-receipt/{id}', [FeeCollectionController::class, 'generateReceipt'])->name('fee-collections.generate-receipt');

        // Fee Discounts
        Route::resource('fee-discounts', FeeDiscountController::class);
        Route::get('fee-discounts-data', [FeeDiscountController::class, 'getdata'])->name('fee-discounts.data');
        Route::post('fee-discounts-change-status', [FeeDiscountController::class, 'changeStatus'])->name('fee-discounts.change-status');
        Route::post('fee-discounts-bulk-discount', [FeeDiscountController::class, 'applyBulkDiscount'])->name('fee-discounts.bulk-discount');

        // Fee Terms
        Route::resource('fee-terms', FeeTermController::class);
        Route::get('fee-terms-data', [FeeTermController::class, 'getdata'])->name('fee-terms.data');
        Route::post('fee-terms-change-status', [FeeTermController::class, 'changeStatus'])->name('fee-terms.change-status');
        Route::post('fee-terms-bulk-action', [FeeTermController::class, 'handleBulkAction'])->name('fee-terms.bulk-action');
        Route::post('fee-terms-bulk-delete', [FeeTermController::class, 'bulkDelete'])->name('fee-terms.bulk-delete');
        Route::post('fee-terms-bulk-status', [FeeTermController::class, 'bulkStatus'])->name('fee-terms.bulk-status');
        
        // Challan Routes
        Route::get('challans', [ChallanController::class, 'index'])->name('challans.index');
        Route::get('challans/data', [ChallanController::class, 'getdata'])->name('challans.data');
        Route::get('challans/student-collections/{student_id}', [ChallanController::class, 'getStudentFeeCollections'])->name('challans.student-collections');
        Route::post('challans/generate', [ChallanController::class, 'generateChallan'])->name('challans.generate');
        Route::get('challans/{voucher}/print', [ChallanController::class, 'printChallan'])->name('challans.print');
        Route::get('challans/{voucher}/download', [ChallanController::class, 'downloadChallan'])->name('challans.download');
        Route::post('challans/bulk-generate', [ChallanController::class, 'bulkGenerate'])->name('challans.bulk-generate');
    });

    //Inventory Routes
    // Route::resource('/inventory/budget/',BudgetController::class);
    // Route::get('/inventory',function()
    // {
    //     return 'hello';
    // });
});
