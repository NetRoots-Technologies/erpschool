<?php

use Mpdf\Tag\B;
use App\Models\Inventry;
use App\Models\Supplier;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealBatchController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\Admin\BudgetController;
use App\Http\Controllers\admin\VendorController;
use App\Http\Controllers\Inventory\ItemController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Inventory\QuoteController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\InventryController;
use App\Http\Controllers\Inventory\SupplierController;
use App\Http\Controllers\Admin\BudgetExpenseController;
use App\Http\Controllers\Admin\BudgetCategoryController;
use App\Http\Controllers\Admin\VendorCategoryController;
use App\Http\Controllers\Inventory\StaffLunchController;
use App\Http\Controllers\Admin\InventoryCategoryController;
use App\Http\Controllers\Inventory\PurchaseOrderController;
use App\Http\Controllers\Admin\SuppliementaryBudgetController;




Route::group(['middleware' => ['auth'], 'prefix' => 'inventory', 'as' => 'inventory.'], function () {

    Route::get('/items/change-status/{item?}', [ItemController::class, 'changeStatus'])->name('items.change.status');
    Route::get('/items/{type}', [ItemController::class, 'index'])->name('items.index');
    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/destroy/{item?}', [ItemController::class, 'destroy'])->name('items.destroy');

    // suppliers
    Route::get('/suppliers/{type}', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/change-status/{supplier?}', [SupplierController::class, 'changeStatus'])->name('suppliers.change.status');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/destroy/{supplier?}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // requisitions
    Route::get('/requisitions/approval/{type}', [RequisitionController::class, 'approval'])->name('requisitions.approval');
    Route::get('/requisitions/{type}', [RequisitionController::class, 'index'])->name('requisitions.index');
    Route::get('/requisitions/change-status/{requisition?}', [RequisitionController::class, 'changeStatus'])->name('requisitions.change.status');
    Route::post('/requisitions/store', [RequisitionController::class, 'store'])->name('requisitions.store');
    Route::get('/requisitions/destroy/{requisition?}', [RequisitionController::class, 'destroy'])->name('requisitions.destroy');

    // quotes
    Route::get('/quotes/approval', [QuoteController::class, 'approval'])->name('quotes.approval');
    Route::get('/quotes/{type}', [QuoteController::class, 'index'])->name('quotes.index');
    Route::get('/quotes/change-status/{quote?}', [QuoteController::class, 'changeStatus'])->name('quotes.change.status');
    Route::post('/quotes/store', [QuoteController::class, 'store'])->name('quotes.store');
    Route::get('/quotes/destroy/{quote?}', [QuoteController::class, 'destroy'])->name('quotes.destroy');
    Route::post('/get/quotes/', [QuoteController::class, 'getQuote'])->name('get.quote');
    Route::get('/quotes/food/{id}', [QuoteController::class, 'show'])->name('quotes.food.show');


    // Purchase Order
    Route::get('/purchase_orders/{type}', [PurchaseOrderController::class, 'index'])->name('purchase_order.index');
    Route::get('/grn/{type}', [PurchaseOrderController::class, 'grn'])->name('grn');
    Route::post('/purchase_orders/uploadPurchase', [PurchaseOrderController::class, 'uploadPurchase'])->name('purchase_order.uploadPurchase');
    Route::get('/grn/Detail/{id?}', [PurchaseOrderController::class, 'grnDetail'])->name('grn.Detail');
    Route::post('/purchase_orders/store', [PurchaseOrderController::class, 'store'])->name('purchase_order.store');
    Route::get('/purchase_orders/destroy/{purchase_order?}', [PurchaseOrderController::class, 'destroy'])->name('purchase_order.destroy');

    Route::post('/purchase_orders/change-status/{purchase_order?}/{status?}', [PurchaseOrderController::class, 'changeStatus'])->name('purchase_order.change.status');
    Route::post('/purchase_orders/change-pStatus/{purchase_order?}/{status?}', [PurchaseOrderController::class, 'changePaymentStatus'])->name('purchase_order.change.pStatus');
    Route::post('/purchase_orders/change-pMethod/{purchase_order?}/{status?}', [PurchaseOrderController::class, 'changePaymentMethod'])->name('purchase_order.change.pMethod');


    Route::get('/purchase_orders/view/{id?}', [PurchaseOrderController::class, 'view'])->name('purchase_order.view');

    // inventry
    Route::get('/inventry/{type}', [InventryController::class, 'index'])->name('inventry.index');
    Route::post('/inventry/store', [InventryController::class, 'store'])->name('inventry.store');
    Route::get('/inventry/stationery/listing', [InventryController::class, 'listing'])->name('inventry.stationery.listing');
    Route::get('/pos/{type?}', [InventryController::class, 'view'])->name('pos.view');
    Route::post('/pos/submit', [InventryController::class, 'save'])->name('pos.save');
    Route::get('/pos/listing', [InventryController::class, 'purchaseHistory'])->name('pos.listing');

    // Products
    Route::get('/products/{type}', [ProductController::class, 'index'])->name('product.index');
    Route::post('/products/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/products/destroy/{product?}', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/products/calculations/{product?}', [ProductController::class, 'calculate'])->name('product.calculate');
    Route::post('/products/inventory', [ProductController::class, 'productInventory'])->name('product.productInventory');

    //completed goods
    Route::get('/goods', [ProductController::class, 'productCompleted'])->name('product.productCompleted');

    //School Lunch
    Route::get('/student_lunch', [MealBatchController::class, 'index'])->name('school_lunch.school_lunch');
    Route::post('/student_lunch/store', [MealBatchController::class, 'store'])->name('school_lunch.store');
    Route::get('/student_lunch/view', [MealBatchController::class, 'view'])->name('school_lunch.view');
    Route::get('student_lunch/view/{id}', [MealBatchController::class, 'get_assigned_student'])->name('school_lunch.get_assigned_student');

    //staff lunch
    Route::get('/staff_lunch', [MealBatchController::class, 'emp_index'])->name('staff_lunch.emp_index');
    Route::post('/staff_lunch/store', [MealBatchController::class, 'emp_store'])->name('staff_lunch.emp_store');
    Route::get('/staff_lunch/view', [MealBatchController::class, 'emp_view'])->name('staff_lunch.emp_view');
    Route::get('/staff_lunch/view/{id}', [MealBatchController::class, 'get_assigned_employee'])->name('staff_lunch.get_assigned_employee');

    //products quantity
    Route::get('/product/details', [MealBatchController::class, 'get_quantityProducts'])->name('products');

    //budget
     //budget
    Route::resource('/budget', BudgetController::class);
    Route::get('/budget/{budget}/assign-department', [BudgetController::class, 'assignDepartment'])->name('budget.assignDepartment');
    Route::post('/budget/{budget}/assign-department', [BudgetController::class, 'storeDepartmentBudget'])->name('budget.storeDepartment');
    Route::get('/assign-to-department', [BudgetController::class, 'listOfAssignDepartment'])->name('List.ofAssignDepartment');
    Route::get('/get-subcategories/{parentId}', [BudgetController::class, 'getSubcategories'])->name('get.subcategories');

    // Budget Export and Import
     Route::get('/download-budget-template', [BudgetController::class, 'downloadTemplate'])->name('budget.template.download');
     Route::post('/budget/import', [BudgetController::class, 'import'])->name('budget.import');

    


    Route::resource('/expense', BudgetExpenseController::class);
    Route::get('/get-categories-by-budget', [BudgetExpenseController::class, 'getCategories'])->name('get.categories.by.budget');
    Route::get('/get-subcategories-by-category', [BudgetExpenseController::class, 'getSubcategories'])->name('get.subcategories.by.category');
    Route::get('/get-allowed-amount', [BudgetExpenseController::class, 'getAllowedAmount'])->name('get.allowed.amount');

    Route::resource('/supplementory', SuppliementaryBudgetController::class);
    Route::get('/supplementory/requests/list', [SuppliementaryBudgetController::class , 'requestList'])->name('supplimentary.requests.list');
    Route::post('/supplementory/approved/{id}', [SuppliementaryBudgetController::class , 'approvedStatus'])->name('supplimentary.approved.status');
    Route::post('/supplementory/reject/{id}', [SuppliementaryBudgetController::class , 'rejectedStatus'])->name('supplimentary.reject.status');
//  supplimentory Reports

    Route::get('/supplimentory/budget/report', [SuppliementaryBudgetController::class, 'varianceReport'])->name('supplimentory.budget.report');
    Route::get('/reports/supplementory/details', [SuppliementaryBudgetController::class, 'supplementoryDetails'])->name('reports.suppliementory.details');




    Route::resource('/category', BudgetCategoryController::class);
    //Inventory
    Route::resource('/inventory-center', InventoryCategoryController::class);
    Route::resource('/inventory-management', InventoryController::class);
    
    //vendor
    Route::resource('/vendor-category', VendorCategoryController::class);
    Route::resource('/vendor-management', VendorController::class);
    Route::get('/getCities', [VendorController::class, 'getCities'])->name('inventory.vendor-management.getCities');
    Route::get('/getDataIndex', [VendorController::class, 'getDataIndex']);
    Route::post('/vendor-management/{id}/toggle-status', [VendorController::class, 'toggleStatus'])
        ->name('inventory.vendor-management.toggleStatus');

    Route::get('/getDataIndex', [InventoryController::class, 'getDataIndex']);

    //Inventory
    Route::resource('/inventory-center', InventoryCategoryController::class);
    Route::get('/getCities', [VendorController::class, 'getCities'])->name('inventory.vendor-management.getCities');
    Route::get('/getDataIndex', [VendorController::class, 'getDataIndex']);
    Route::post('/vendor-management/{id}/toggle-status', [VendorController::class, 'toggleStatus'])
        ->name('inventory.vendor-management.toggleStatus');


});
