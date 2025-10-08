<?php

use App\Http\Controllers\Dashbboard\DashboardController;
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
use App\Http\Controllers\Accounts\ChartOfAccountsController;
use App\Http\Controllers\Admin\BankFileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DataBankController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\VideoUploadController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\BankBranchesController;
// BanksAccountController removed - now using Accounts\ChartOfAccountsController
use App\Http\Controllers\Admin\MarketingVideoController;
use App\Http\Controllers\Admin\MarketingBannerAdController;
use App\Http\Controllers\Admin\PromotionalMessageController;
use App\Http\Controllers\Admin\MarketingNotificationController;
use App\Http\Controllers\Admin\SignatoryAuthorityController;
use App\Http\Controllers\Admin\FeeManagementController;

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
//
//    Route::resource('video', \App\Http\Controllers\Admin\VideoUploadController::class);
//    Route::resource('video_category', \App\Http\Controllers\Admin\VideoCategoryController::class);
//    Route::get('session_videos/{id}', [VideoUploadController::class, 'session_videos'])->name('session_videos');
//
////
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
    // Bank accounts now managed in Accounts & Finance module
    Route::redirect('banks_accounts', '/accounts/chart-of-accounts')->name('banks_accounts.index');
    Route::redirect('banks_accounts/create', '/accounts/chart-of-accounts/create')->name('banks_accounts.create');

    Route::post('/admin/financial-years/change-status', [FinancialController::class, 'changeStatus'])->name('financial-years.change-status');
    Route::post('import-bankFile', [BankFileController::class, 'import'])->name('import.bankFile');

    Route::post('/bankBulk-action', [BankController::class, 'handleBulkAction'])->name('bank-bulk');

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


    //Inventory Routes
    // Route::resource('/inventory/budget/',BudgetController::class);
    // Route::get('/inventory',function()
    // {
    //     return 'hello';
    // });


    // Fee Management Routes
    Route::group(['prefix' => 'fee-management', 'as' => 'fee-management.'], function () {
        // Dashboard
        Route::get('/', [FeeManagementController::class, 'index'])->name('index');
        
        // Categories
        Route::get('/categories', [FeeManagementController::class, 'categories'])->name('categories');
        Route::get('/categories/data', [FeeManagementController::class, 'getCategoriesData'])->name('categories.data');
        Route::get('/categories/create', [FeeManagementController::class, 'createCategory'])->name('categories.create');
        Route::post('/categories', [FeeManagementController::class, 'storeCategory'])->name('categories.store');
        Route::get('/categories/{id}/edit', [FeeManagementController::class, 'editCategory'])->name('categories.edit');
        Route::put('/categories/{id}', [FeeManagementController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [FeeManagementController::class, 'deleteCategory'])->name('categories.delete');
        
        // Structures
        Route::get('/structures', [FeeManagementController::class, 'structures'])->name('structures');
        Route::get('/structures/data', [FeeManagementController::class, 'getStructuresData'])->name('structures.data');
        Route::get('/structures/create', [FeeManagementController::class, 'createStructure'])->name('structures.create');
        Route::post('/structures', [FeeManagementController::class, 'storeStructure'])->name('structures.store');
        Route::get('/structures/{id}/edit', [FeeManagementController::class, 'editStructure'])->name('structures.edit');
        Route::put('/structures/{id}', [FeeManagementController::class, 'updateStructure'])->name('structures.update');
        Route::delete('/structures/{id}', [FeeManagementController::class, 'deleteStructure'])->name('structures.delete');
        
        // Collections
        Route::get('/collections', [FeeManagementController::class, 'collections'])->name('collections');
        Route::get('/collections/data', [FeeManagementController::class, 'getCollectionsData'])->name('collections.data');
        Route::get('/collections/create', [FeeManagementController::class, 'createCollection'])->name('collections.create');
        Route::get('/collections/students-by-class/{classId}', [FeeManagementController::class, 'getStudentsByClass'])->name('collections.students-by-class');
        Route::get('/collections/sessions-by-class/{classId}', [FeeManagementController::class, 'getSessionsByClass'])->name('collections.sessions-by-class');
        Route::post('/collections', [FeeManagementController::class, 'storeCollection'])->name('collections.store');
        Route::get('/collections/pay-challan', [FeeManagementController::class, 'payChallan'])->name('collections.pay-challan');
        Route::get('/collections/challans-by-student/{studentId}', [FeeManagementController::class, 'getChallansByStudent'])->name('collections.challans-by-student');
        Route::get('/collections/challan-discounts/{challanId}', [FeeManagementController::class, 'getChallanDiscounts'])->name('collections.challan-discounts');
        Route::get('/collections/student-transport-fees/{studentId}', [FeeManagementController::class, 'getStudentTransportFees'])->name('collections.student-transport-fees');
        Route::post('/collections/store-challan-payment', [FeeManagementController::class, 'storeChallanPayment'])->name('collections.store-challan-payment');
        Route::get('/collections/{id}', [FeeManagementController::class, 'showCollection'])->name('collections.show');
        Route::get('/collections/{id}/edit', [FeeManagementController::class, 'editCollection'])->name('collections.edit');
        Route::put('/collections/{id}/update-challan', [FeeManagementController::class, 'updateChallanCollection'])->name('collections.update-challan');
        Route::put('/collections/{id}', [FeeManagementController::class, 'updateCollection'])->name('collections.update');
        
        // Discounts
        Route::get('/discounts', [FeeManagementController::class, 'discounts'])->name('discounts');
        Route::get('/discounts/data', [FeeManagementController::class, 'getDiscountsData'])->name('discounts.data');
        Route::get('/discounts/create', [FeeManagementController::class, 'createDiscount'])->name('discounts.create');
        Route::post('/discounts', [FeeManagementController::class, 'storeDiscount'])->name('discounts.store');
        Route::get('/discounts/{id}/edit', [FeeManagementController::class, 'editDiscount'])->name('discounts.edit');
        Route::put('/discounts/{id}', [FeeManagementController::class, 'updateDiscount'])->name('discounts.update');
        Route::delete('/discounts/{id}', [FeeManagementController::class, 'deleteDiscount'])->name('discounts.delete');
        
        // Billing
        Route::get('/billing', [FeeManagementController::class, 'billing'])->name('billing');
        Route::get('/billing/data', [FeeManagementController::class, 'getBillingData'])->name('billing.data');
        Route::post('/billing/generate', [FeeManagementController::class, 'generateBilling'])->name('billing.generate');
        Route::get('/billing/{id}/print', [FeeManagementController::class, 'printBilling'])->name('billing.print');
        
        // Reports
        Route::get('/reports', [FeeManagementController::class, 'reports'])->name('reports');
        Route::get('/reports/income', [FeeManagementController::class, 'incomeReport'])->name('reports.income');
        Route::get('/reports/outstanding', [FeeManagementController::class, 'outstandingReport'])->name('reports.outstanding');
        Route::get('/reports/student-ledger/{studentId}', [FeeManagementController::class, 'studentLedger'])->name('reports.student-ledger');
    });
    
    // Dashboard Route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
