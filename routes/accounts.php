<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Accounts & Finance Routes
|--------------------------------------------------------------------------
|
| Professional Accounts and Finance Module Routes
| Organized by Milestones (M1-M6)
|
*/

Route::group(['middleware' => ['auth'], 'prefix' => 'accounts', 'as' => 'accounts.'], function () {
    
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Accounts\DashboardController::class, 'index'])->name('dashboard');
    
    // M1: Chart of Accounts & Journal Entries
    // Account Groups Management
    Route::prefix('groups')->name('groups.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Accounts\AccountGroupController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Accounts\AccountGroupController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Accounts\AccountGroupController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Accounts\AccountGroupController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Accounts\AccountGroupController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Accounts\AccountGroupController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('chart-of-accounts')->name('coa.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'destroy'])->name('destroy');
        Route::get('/tree', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'tree'])->name('tree');
        Route::get('/get-child-groups', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'getChildGroups'])
              ->name('getChildGroups');
        Route::get('/generate-account-code', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'generateAccountCode'])
             ->name('generateAccountCode');
        Route::get('/getthirdchild', [\App\Http\Controllers\Accounts\ChartOfAccountsController::class, 'getthirdchild'])
             ->name('getthirdchild');

    });
    
    Route::prefix('journal-entries')->name('journal.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Accounts\JournalEntryController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Accounts\JournalEntryController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Accounts\JournalEntryController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Accounts\JournalEntryController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Accounts\JournalEntryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Accounts\JournalEntryController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Accounts\JournalEntryController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [\App\Http\Controllers\Accounts\JournalEntryController::class, 'approve'])->name('approve');
    });
    
    // M2: Accounts Payables
    Route::prefix('payables')->name('payables.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Accounts\PayablesController::class, 'index'])->name('index');
        Route::get('/vendors', [\App\Http\Controllers\Accounts\VendorController::class, 'index'])->name('vendors.index');
        Route::get('/vendors/create', [\App\Http\Controllers\Accounts\VendorController::class, 'create'])->name('vendors.create');
        Route::post('/vendors/store', [\App\Http\Controllers\Accounts\VendorController::class, 'store'])->name('vendors.store');
        Route::get('/vendors/{id}/edit', [\App\Http\Controllers\Accounts\VendorController::class, 'edit'])->name('vendors.edit');
        Route::put('/vendors/{id}', [\App\Http\Controllers\Accounts\VendorController::class, 'update'])->name('vendors.update');
        
        Route::get('/bills', [\App\Http\Controllers\Accounts\BillController::class, 'index'])->name('bills.index');
        Route::get('/bills/create', [\App\Http\Controllers\Accounts\BillController::class, 'create'])->name('bills.create');
        Route::post('/bills/store', [\App\Http\Controllers\Accounts\BillController::class, 'store'])->name('bills.store');
        Route::get('/bills/{id}', [\App\Http\Controllers\Accounts\BillController::class, 'show'])->name('bills.show');
        Route::post('/bills/{id}/pay', [\App\Http\Controllers\Accounts\BillController::class, 'pay'])->name('bills.pay');

        Route::prefix('vendor-payments')->name('vendorPayments.')->group(function () {

        Route::get('/', [\App\Http\Controllers\Accounts\VendorPaymentController::class, 'index'])->name('index');

        Route::get('/create', [\App\Http\Controllers\Accounts\VendorPaymentController::class, 'create'])->name('create');

        Route::post('/store', [\App\Http\Controllers\Accounts\VendorPaymentController::class, 'store'])->name('store');

        Route::get('/{id}/edit', [\App\Http\Controllers\Accounts\VendorPaymentController::class, 'edit'])->name('edit');

        Route::put('/{id}', [\App\Http\Controllers\Accounts\VendorPaymentController::class, 'update'])->name('update');

        Route::delete('/{id}', [\App\Http\Controllers\Accounts\VendorPaymentController::class, 'destroy'])->name('delete');

        Route::get('/{id}/print', [\App\Http\Controllers\Accounts\VendorPaymentController::class, 'print'])->name('print');

        Route::get('/{vendor_id}/pending-invoices', [\App\Http\Controllers\Accounts\VendorPaymentController::class, 'getPendingInvoices'])->name('getPendingInvoices');

    });

    });
    
    // M3: Accounts Receivables
    Route::prefix('receivables')->name('receivables.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Accounts\ReceivablesController::class, 'index'])->name('index');
        Route::get('/customers', [\App\Http\Controllers\Accounts\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [\App\Http\Controllers\Accounts\CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers/store', [\App\Http\Controllers\Accounts\CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{id}/edit', [\App\Http\Controllers\Accounts\CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{id}', [\App\Http\Controllers\Accounts\CustomerController::class, 'update'])->name('customers.update');
        
        Route::get('/invoices', [\App\Http\Controllers\Accounts\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [\App\Http\Controllers\Accounts\InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices/store', [\App\Http\Controllers\Accounts\InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{id}', [\App\Http\Controllers\Accounts\InvoiceController::class, 'show'])->name('invoices.show');
        Route::post('/invoices/{id}/receive', [\App\Http\Controllers\Accounts\InvoiceController::class, 'receive'])->name('invoices.receive');
    });
    
    // M4: Cost Centers
    Route::prefix('cost-centers')->name('cost_centers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Accounts\CostCenterController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Accounts\CostCenterController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Accounts\CostCenterController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Accounts\CostCenterController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Accounts\CostCenterController::class, 'update'])->name('update');
        Route::get('/reports', [\App\Http\Controllers\Accounts\CostCenterController::class, 'reports'])->name('reports');
    });
    
    // M5: Profit Centers
    Route::prefix('profit-centers')->name('profit_centers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Accounts\ProfitCenterController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Accounts\ProfitCenterController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Accounts\ProfitCenterController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Accounts\ProfitCenterController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Accounts\ProfitCenterController::class, 'update'])->name('update');
        Route::get('/reports', [\App\Http\Controllers\Accounts\ProfitCenterController::class, 'reports'])->name('reports');
    });
    
    // M6: Financial Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/trial-balance', [\App\Http\Controllers\Accounts\ReportsController::class, 'trialBalance'])->name('trial_balance');
        Route::get('/balance-sheet', [\App\Http\Controllers\Accounts\ReportsController::class, 'balanceSheet'])->name('balance_sheet');
        Route::get('/income-statement', [\App\Http\Controllers\Accounts\ReportsController::class, 'incomeStatement'])->name('income_statement');
        Route::get('/cash-flow', [\App\Http\Controllers\Accounts\ReportsController::class, 'cashFlow'])->name('cash_flow');
        Route::get('/general-ledger', [\App\Http\Controllers\Accounts\ReportsController::class, 'generalLedger'])->name('general_ledger');
        Route::get('/aged-payables', [\App\Http\Controllers\Accounts\ReportsController::class, 'agedPayables'])->name('aged_payables');
        Route::get('/aged-receivables', [\App\Http\Controllers\Accounts\ReportsController::class, 'agedReceivables'])->name('aged_receivables');
        Route::get('/budget-analysis', [\App\Http\Controllers\Accounts\ReportsController::class, 'budgetAnalysis'])->name('budget_analysis');
    });
    
    // Integration APIs
    Route::prefix('integration')->name('integration.')->group(function () {
        Route::post('/hr-salary', [\App\Http\Controllers\Accounts\IntegrationController::class, 'recordHRSalary'])->name('hr_salary');
        Route::post('/inventory-purchase', [\App\Http\Controllers\Accounts\IntegrationController::class, 'recordInventoryPurchase'])->name('inventory_purchase');
        Route::post('/academic-fee', [\App\Http\Controllers\Accounts\IntegrationController::class, 'recordAcademicFee'])->name('academic_fee');
        Route::post('/fleet-expense', [\App\Http\Controllers\Accounts\IntegrationController::class, 'recordFleetExpense'])->name('fleet_expense');
    });
    // Inside the main 'accounts' route group with middleware

});
