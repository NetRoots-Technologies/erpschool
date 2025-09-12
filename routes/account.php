<?php

use App\Http\Controllers\Admin\BankAccountDetailsController;
use App\Http\Controllers\Admin\BanksBranchesController;
use App\Http\Controllers\Admin\CurrenciesController;
use App\Http\Controllers\Admin\EntriesController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\LedgersController;
use App\Http\Controllers\Reports\LedgerController as reportLedgerController;
use App\Http\Controllers\Admin\AccountReportsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::get('ledger_report', [reportLedgerController::class, 'index'])->name('ledger_report');

    Route::get('fee_collection', [LedgersController::class, 'fee_collection_index'])->name('fee_collection_index');

    Route::resource('/groups', GroupController::class);

    Route::get('/fetch_coa/{id}',[GroupController::class,'fetch_coa'])->name('fetch_coa');
    
    Route::resource('ledgers', LedgersController::class);

    Route::post('ledgers_mass_destroy', [LedgersController::class, 'restore'])->name('ledgers.mass_destroy');

    Route::get('ledgers/group/income', [LedgersController::class, 'income'])->name('ledgers.income');

    Route::get('ledgers/group/receivable', [LedgersController::class, 'receivable'])->name('ledgers.receivable');

    Route::get('currency', [CurrenciesController::class, 'index'])->name('currencies.index');

    Route::view("currency/add", "admin.currency.create");

    Route::post("currency/add", [CurrenciesController::class, 'add'])->name('currencies.add');

    Route::get('currency/delete/{id}', [CurrenciesController::class, 'delete'])->name('currencies.delete');

    Route::get('currency/edit/{id}', [CurrenciesController::class, 'edit'])->name('currencies.edit');

    Route::post("currency/edit", [CurrenciesController::class, 'update'])->name('currencies.update');

    Route::get('ledger_tree', [LedgersController::class, 'ledger_tree'])->name('accounts.chart_of_accounts.ledger_tree');

    Route::get('get_ledger_tree/{PID?}', [LedgersController::class, 'get_ledger_tree'])->name('get_ledger_tree');

    Route::get('ledgers_child_detail', [LedgersController::class, 'ledgerChild'])->name('ledgers.child.detail');

    Route::resource('entries', EntriesController::class);

    Route::get('entries/{entry}/entry', [EntriesController::class, 'entry'])->name('entries.entry');

    Route::post('entries/activate/{entry}', [EntriesController::class, 'active'])->name('entries.active');

    Route::post('entries/inactive/{entry}', [EntriesController::class, 'inactive'])->name('entries.inactive');

    Route::post('entries/edit/{entry}', [EntriesController::class, 'edit'])->name('entries.edit');

    Route::post('entries/destroy/{entry}', [EntriesController::class, 'destroy'])->name('entries.destroy');

    Route::get('entries/downloadPDF/{id}', [EntriesController::class, 'downloadPDF'])->name('entries.downloadPDF');

    Route::group(['prefix' => 'voucher', 'as' => 'voucher.'], function () {

        // Cash Search Voucher Routes

        Route::get('cash/search', [EntriesController::class, 'cash_search'])->name('cash_search');

        // Bank Search Voucher Routes

        Route::get('bank/search', [EntriesController::class, 'bank_search'])->name('bank_search');

        // Cash & Bank Search Voucher Routes

        Route::get('cashbank/search', [EntriesController::class, 'cashbank_search'])->name('cashbank_search');


        // Journal Voucher Routes abc

        Route::get('gjv/create', [EntriesController::class, 'gjv_create'])->name('gjv_create');


        Route::post('gjv/store', [EntriesController::class, 'gjv_store'])->name('gjv_store');


        Route::get('gjv/search', [EntriesController::class, 'gjv_search'])->name('gjv_search');

        //voucher inv and gnr list

        Route::get('gjv/get_invList/{ledger_id}/', [EntriesController::class, 'get_invList'])->name('get_invList');

        Route::get('cpv/get_voucher_balance/{ledgerID}', [EntriesController::class, 'get_voucher_balance'])->name('get_voucher_balance');


        // Cash Receipt Voucher Routes

        Route::get('crv/create', [EntriesController::class, 'crv_create'])->name('crv_create');

        Route::post('crv/store', [EntriesController::class, 'crv_store'])->name('crv_store');

        Route::get('crv/search', [EntriesController::class, 'crv_search'])->name('crv_search');

        // Cash Payment Voucher Routes

        Route::get('cpv/create', [EntriesController::class, 'cpv_create'])->name('cpv_create');

        Route::post('cpv/store', [EntriesController::class, 'cpv_store'])->name('cpv_store');

        Route::get('cpv/search', [EntriesController::class, 'cpv_search'])->name('cpv_search');

        Route::get('brv/create', [EntriesController::class, 'brv_create'])->name('brv_create');

        Route::post('brv/store', [EntriesController::class, 'brv_store'])->name('brv_store');

        Route::get('brv/search', [EntriesController::class, 'brv_search'])->name('brv_search');

        // Bank Payment Voucher Routes

        Route::get('bpv/create', [EntriesController::class, 'bpv_create'])->name('bpv_create');

        Route::post('bpv/store', [EntriesController::class, 'bpv_store'])->name('bpv_stores');

        Route::get('bpv/search', [EntriesController::class, 'bpv_search'])->name('bpv_search');

        //Gold payment voucher

        Route::get('gpv/create', [EntriesController::class, 'gpv_create'])->name('gpv_create');

        Route::post('gpv/store', [EntriesController::class, 'gpv_store'])->name('gpv_store');

    });

    Route::get('/trialBalance', [AccountReportsController::class, 'downloadPDF'])->name('trialBalance');
    Route::get('/balanceSheet', [AccountReportsController::class, 'downloadPDF'])->name('entries.downloadPDF');
    Route::get('/profitLossStatement', [AccountReportsController::class, 'downloadPDF'])->name('entries.downloadPDF');


    //reports
    Route::view('/report-center', 'admin.accounts.reports.index')->name('accounts.reports');
    Route::get('/general-ledger',[LedgersController::class,'getGeneralLedger'])->name('accounts.reports.general-ledger');
    Route::get('/subsidiary -ledger',[LedgersController::class,'getSubsidiaryLedger'])->name('accounts.reports.subsidiary-ledger');

    //get general ledger result
    Route::post('/general-ledger-result',[LedgersController::class,'generalLedgerResult'])->name('accounts.general-ledger');
    // Route::post('/subsidiary-ledger-result',[LedgersController::class,'subsidiaryLedgerResult'])->name('accounts.subsidiary-ledger');

});


Route::resource('banksBranches', BanksBranchesController::class);

Route::resource('BankAccountDetail', BankAccountDetailsController::class);

Route::get('entries/getData', [EntriesController::class, 'getData'])->name('entries.getData');

Route::post('get_ledger_rep', [reportLedgerController::class, 'get_ledger_rep'])->name('get_ledger_rep');

Route::get('expense_summary', ['uses' => 'App\Http\Controllers\Admin\AccountReportsController@expense_summary', 'as' => 'expense_summary']);

Route::post('expense_summary_report', ['uses' => 'App\Http\Controllers\Admin\AccountReportsController@expense_summary_report', 'as' => 'expense_summary_report']);

Route::get('profit-loss', [AccountReportsController::class, 'profit_loss'])->name('profit_loss');
Route::post('profit-loss-report', [AccountReportsController::class, 'profit_loss_report'])->name('profit_loss_report');
Route::get('trial-balance-report', [AccountReportsController::class, 'trial_balance'])->name('trial_balance_report');
Route::get('chart-of-accounts', [AccountReportsController::class, 'accounts_chart'])->name('accounts_chart');
// Route::get('trial-balance-report-print', [AccountReportsController::class, 'trial_balance_report'])->name('trial-balance-report-print');
Route::get('trial-balance-report-print', [AccountReportsController::class, 'trial_balance_group'])->name('trial-balance-report-print');
Route::get('balance-sheet', [AccountReportsController::class, 'balance_sheet'])->name('balance_sheet');
Route::post('balance-sheet-report', [AccountReportsController::class, 'balance_sheet_report'])->name('balance_sheet_report');