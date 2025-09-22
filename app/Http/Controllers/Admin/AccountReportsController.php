<?php

namespace App\Http\Controllers\Admin;

use DB;
use Config;
use App\Helpers\GroupsTree;
use App\Helpers\LedgersTree;
use App\Models\Admin\Groups;
use Illuminate\Http\Request;
use App\Helpers\AccountsList;
use App\Helpers\CoreAccounts;
use App\Models\Admin\Entries;
use App\Models\Admin\Ledgers;
use App\Models\HRM\Employees;
use App\Models\Admin\Branches;
use App\Helpers\AccountsHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Admin\Currencies;
use App\Models\Admin\EntryItems;
use App\Models\Admin\EntryTypes;
use App\Models\Admin\Departments;
use App\Models\Admin\AccountTypes;

//Excel Export Library
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountReportsController extends Controller
{
    // Variable to hold Excel Data
    protected $sheet;
    protected $accountsHelper;
    // Variable to hold Excel Loop counter
    protected static $excel_iterator;
    // Variable to hold Balance Sheet Loop counter
    protected static $bs_iterator;
    // Variable to hold Profit and Loss counter
    protected static $pandl_iterator;
    // Variable to hold Ledger Statement counter
    protected static $ls_iterator;

    public function __construct(AccountsHelper $accountsHelper)
    {
        $this->accountsHelper = $accountsHelper;
    }

    /**
     * Display a listing of AccountReport.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        return view('admin.account_reports.index');
    }

    /**
     * Display Chart of Accounts.
     *
     * @return \Illuminate\Http\Response
     */
    public function accounts_chart()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        // $accountlist = new AccountsList();
        // $accountlist->only_opening = false;
        // $accountlist->start_date = null;
        // $accountlist->end_date = null;
        // $accountlist->affects_gross = -1;
        // $accountlist->start(0);
        // dd($accountlist);

        $type = $request->type ?? 0;

        $parentGroups = new LedgersTree();
        $parentGroups->current_id = -1;
        $parentGroups->build(0);
        $parentGroups->toList($parentGroups, -1);
        $Ledgers = $parentGroups->ledgerList;
        return view('admin.account_reports.index', compact('Ledgers', 'type'));
    }

    /**
     * Show Trial Balance.
     *
     * @return \Illuminate\Http\Response
     */
    public function trial_balance()
    {
       if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        // Get All branches
        $branches = Branches::pluckAllBranches();
        // Get All Account Types
//        $AccountTypes = AccountTypes::getActiveListDropdown(true);
//
//        // Get All Entry Types
//        $EntryTypes = EntryTypes::pluckActiveOnly();
//        $EntryTypes->prepend('Select an Entry Type', '');
//
//        // Get All Groups
//        $Groups = GroupsTree::buildOptions(GroupsTree::buildTree(Groups::OrderBy('name', 'asc')->get()->toArray()), old('group_id'));

        return view('admin.account_reports.trial_balance.index', compact('branches'));
    }

    /**
     * Load Report of Trial Balance.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function trial_balance_report(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));

        $accountlist = new AccountsList();
        $accountlist->only_opening = false;
        $accountlist->start_date = $start_date;

        $accountlist->end_date = $end_date;
        $accountlist->affects_gross = -1;
        $accountlist->filter = $request->all();

        $group = $request->get('group_id') ?? 0;

        $accountlist->start($group);

        $DefaultCurrency = Currencies::getDefaultCurrencty();
        $ReportData = $accountlist->generateLedgerStatement($accountlist);

        switch ($request->get('medium_type')) {
            case 'web':
                return view('admin.account_reports.trial_balance.report', compact('accountlist', 'ReportData', 'start_date', 'end_date', 'DefaultCurrency'));
            case 'print':
                return view('admin.account_reports.trial_balance.report', compact('accountlist', 'ReportData', 'start_date', 'end_date', 'DefaultCurrency'));
            case 'excel':
                return $this->trialBalanceExcel($accountlist, $DefaultCurrency, $start_date, $end_date);
            case 'pdf':
                $content = view::make('admin.account_reports.trial_balance.report', compact(
                    'accountlist',
                    'ReportData',
                    'start_date',
                    'end_date',
                    'DefaultCurrency'
                ))->render();
                $pdf = Pdf::loadHTML($content);
                $pdf->set_option('isHtml5ParserEnabled', true);
                $pdf->set_option('debugPng', true);
                $pdf->set_option('debugLayout', true);
                $pdf->set_option('debugCss', true);
                return $pdf->download('TrialBalanceReport.pdf');

            default:
                return view('admin.account_reports.trial_balance.report', compact('accountlist', 'ReportData', 'start_date', 'end_date', 'DefaultCurrency'));
        }
    }

    public
    function trial_balance_group(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        if ($request->get('start_date') && $request->get('end_date')) {

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

        } else {
            $start_date = null;
            $end_date = null;
        }

        $branch_id = $request->branch_id ?? 0;

        $group_id = 0;
        $topGroups = $this->accountsHelper->getGroupChild($group_id);
        $trialHash = [];
        $array = $this->accountsHelper->buildTrialBalance($topGroups, $trialHash, $start_date, $end_date, $branch_id);
        $data = $this->accountsHelper->calculateSums($array);
        $ReportData = $this->accountsHelper->generateHtml($data, $start_date, $end_date);

        $start_date = (new \DateTime($start_date))->format('d M, Y');
        $end_date = (new \DateTime($end_date))->format('d M, Y');

        switch ($request->get('medium_type')) {
            case 'web':
                return view('admin.account_reports.trial_balance.report', compact('ReportData', 'start_date', 'end_date'));
            case 'print':
                return view('admin.account_reports.trial_balance.report', compact('ReportData', 'start_date', 'end_date'));
            case 'excel':
//                return $this->trialBalanceExcel($accountlist, $DefaultCurrency, $start_date, $end_date);
            case 'pdf':
                $content = view::make('admin.account_reports.trial_balance.report', compact(
                    'ReportData',
                    'start_date',
                    'end_date'
                ))->render();
                $pdf = Pdf::loadHTML($content);
                $pdf->set_option('isHtml5ParserEnabled', true);
                $pdf->set_option('debugPng', true);
                $pdf->set_option('debugLayout', true);
                $pdf->set_option('debugCss', true);
                return $pdf->download('TrialBalanceReport.pdf');

            default:
                return view('admin.account_reports.trial_balance.report', compact('ReportData', 'start_date', 'end_date'));
        }
    }

    public function trialBalance_report(Request $request)
    {

if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = '';
        $string = "";
        $type = "";
        if ($request->post('date_range')) {
            $date_range = explode(' - ', $request->get('date_range'));
            $start_date = date('Y-m-d', strtotime($date_range[0]));
            $end_date = date('Y-m-d', strtotime($date_range[1]));
        } else {
            $start_date = null;
            $end_date = null;
        }
        $type = $request->type;
        $currencyID = $request->currency_id;
        if ($request->currency_id) {
            $decimal = Currencies::where('id', $request->currency_id)->first('decimal_fixed_point');
        } else {
            $decimal = Currencies::where('id', 1)->first('decimal_fixed_point');
        }
        $decimal = $decimal->decimal_fixed_point;
        $tob_dr = 0;
        $tob_cr = 0;
        $tdr = 0;
        $tcr = 0;
        $tcb = 0;
        $tcb_dr = 0;
        $tcb_cr = 0;
        $Groups = Groups::OrderBy('code')->get();

        foreach ($Groups as $Group) {
            if ($Group->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $Group->level * 10;
                $Lmargin = $margin + 20;
                $font = 900 - 100 * $Group->level;
            }

            $data .= '<tr>';
            $data .= '<th colspan="7" style="text-align: left;"> <span style="margin-left:' . $margin . 'px; font-weight:' . $font . '">' . $Group->number . '-' . $Group->name . '</span></th>';
            $data .= '</tr>';
            $Ledgers = Ledgers::where('group_id', $Group->id)->get();
            if ($Group->id == 12) {
                foreach ($Ledgers as $Ledger) {
                    $closing_balance = CoreAccounts::closing_balance_pkr($Ledger->id, '', $start_date, $end_date);

                }
            }

            foreach ($Ledgers as $Ledger) {

                $balance_details = '';
                $sob = 0;
                $sdr = 0;
                $scr = 0;
                $scb = 0;
                $closing_balance = CoreAccounts::closing_balance_pkr($Ledger->id, '', $start_date, $end_date);
                //                dd($closing_balance);
                foreach ($closing_balance as $cb) {
                    $conRate = Currencies::where('code', $cb[0])->first('rate');
                    $balance_details .= $cb[0] . ' ' . CoreAccounts::dr_cr_balance($cb[5], $decimal) . ', ';

                    //sub total
                    $sob = CoreAccounts::ob_pkr($start_date, $Ledger->id);


                    $sdr += $cb[6] * $conRate->rate;
                    $scr += $cb[7] * $conRate->rate;

                    $scb = $sob + $sdr - $scr;

                }
                //   dd($scb);
                $balance_details = rtrim($balance_details, ', ');
                $data .= '<tr>';
                $data .= '<td colspan="1" align="right"> ' . ucfirst($Ledger->name) . ' ' . '</td>';
                $data .= '<td align="center">' . (($sob > 0) ? '' . number_format(abs($sob), $decimal) . '' : '0.00') . '</td>';
                $data .= '<td align="center">' . (($sob < 0) ? '' . number_format(abs($sob), $decimal) . '' : '0.00') . '</td>';
                $data .= '<td align="center">' . number_format($sdr, $decimal) . '</td>';
                $data .= '<td align="center"> ' . number_format($scr, $decimal) . '</td>';
                $data .= '<td align="center">' . (($scb > 0) ? '' . number_format(abs($scb), $decimal) . '' : '0.00') . '</td>';
                $data .= '<td align="center">' . (($scb < 0) ? '' . number_format(abs($scb), $decimal) . '' : '0.00') . '</td>';

                $data .= '</tr>';
                $tdr += $sdr;
                $tcr += $scr;
                $tcb_dr = $tdr;
                $tcb_cr = $tcr;
                if ($sob > 0) {
                    $tob_dr += $sob;
                }
                if ($sob < 0) {
                    $tob_cr += $sob;
                }
            }

        }
        $string .= '
                      <tr class="last-th">
                        <th></th>
                        <th width="10%" align="center">Opening Dr</th>
                        <th width="10%" align="center">Opening Cr</th>
                        <th width="10%" align="center">Debit</th>
                        <th width="10%" align="center">Credit </th>
                        <th width="12%" align="center">Closing Debit </th>
                        <th width="12%" align="center">Closing Credit </th>
                    </tr>
                     ';
        $string .= '<tr>';
        $string .= '<th align="right" colspan="1">Total</th>';
        $string .= '<th style="text-align: center;">' . number_format($tob_dr, $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format(abs($tob_cr), $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format($tdr, $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format($tcr, $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format($tcb_dr, $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format(abs($tcb_cr), $decimal) . '</th>';
        $string .= '</tr>';
        $data .= $string;

        $content = View::make('admin.account_reports.trial_balance.print_report', compact('data', 'start_date', 'end_date'));
        if ($type == 'pdf') {
            $content .= '<style>
                table{width: 100%; border-collapse: collapse;}
                td,th {
                    border: 0.1pt solid #ccc;
                }
                </style>';
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            // Write some HTML code:
            $mpdf->WriteHTML($content);
            // Output a PDF file directly to the browser
            $mpdf->Output('TrialBalanceReport.pdf', 'D');
        } else if ($type == 'excel') {
            self::trial_BalanceExcel($request->all());
        } else {
            return $content;
        }

    }

    protected function trial_BalanceExcel($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        header('Content-Type: application/vnd.ms-excel charset=UTF-8');
        header('Content-disposition: attachment; filename=' . rand() . '.xls');
        // Set Default Attributes
        if ($request['date_range']) {
            $date_range = explode(' - ', $request['date_range']);
            $start_date = date('Y-m-d', strtotime($date_range[0]));
            $end_date = date('Y-m-d', strtotime($date_range[1]));
        } else {
            $start_date = null;
            $end_date = null;
        }
        //set content.....
        $data = '';
        $string = "";
        $data .= '<style>
                table{width: 100%;}
                td,th {
                    border: 0.1pt solid #ccc;
                }
                </style>';
        $data .= excel_header();
        $data .= '<tr><th colspan="6">Trial Balance AS on ' . $start_date . ' </th></tr>';
        $data .= '
                      <tr class="last-th">
                        <th>Account</th>
                        <th align="center">Opening Dr</th>
                        <th align="center">Opening Cr</th>
                        <th align="center">Debit</th>
                        <th align="center">Credit </th>
                        <th align="center">Closing Debit </th>
                        <th align="center">Closing Credit </th>
                    </tr>
                     ';
        $currencyID = $request['currency_id'];
        $decimal = Currencies::where('id', 1)->first('decimal_fixed_point');
        $decimal = $decimal->decimal_fixed_point;
        $tob_dr = 0;
        $tob_cr = 0;
        $tdr = 0;
        $tcr = 0;
        $tcb = 0;
        $tcb_dr = 0;
        $tcb_cr = 0;
        $Groups = Groups::OrderBy('code')->get();
        foreach ($Groups as $Group) {
            if ($Group->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $Group->level * 10;
                $Lmargin = $margin + 20;
                $font = 900 - 100 * $Group->level;
            }

            $data .= '<tr>';
            $data .= '<th colspan="7" style="text-align: left;"> <span style="margin-left:' . $margin . 'px; font-weight:' . $font . '">' . $Group->number . '-' . $Group->name . '</span></th>';
            $data .= '</tr>';
            $Ledgers = Ledgers::where('group_id', $Group->id)->get();
            foreach ($Ledgers as $Ledger) {
                $balance_details = '';
                $sob = 0;
                $sdr = 0;
                $scr = 0;
                $scb = 0;
                $closing_balance = CoreAccounts::closing_balance_pkr($Ledger->id, '', $start_date, $end_date);
                foreach ($closing_balance as $cb) {
                    $conRate = Currencies::where('code', $cb[0])->first('rate');
                    $balance_details .= $cb[0] . ' ' . CoreAccounts::dr_cr_balance($cb[5], $decimal) . ', ';

                    //sub total
                    $sob = CoreAccounts::ob_pkr($start_date, $Ledger->id);
                    $sdr += $cb[6] * $conRate->rate;
                    $scr += $cb[7] * $conRate->rate;
                    //total
                    //=======================
                    $scb = $sob + $sdr - $scr;
                }

                $balance_details = rtrim($balance_details, ', ');
                $data .= '<tr>';
                $data .= '<td colspan="1" align="right"> ' . ucfirst($Ledger->name) . ' ' . '</td>';
                $data .= '<td align="center">' . (($sob > 0) ? '' . number_format(abs($sob), $decimal) . '' : '0.00') . '</td>';
                $data .= '<td align="center">' . (($sob < 0) ? '' . number_format(abs($sob), $decimal) . '' : '0.00') . '</td>';
                $data .= '<td align="center">' . number_format($sdr, $decimal) . '</td>';
                $data .= '<td align="center"> ' . number_format($scr, $decimal) . '</td>';
                $data .= '<td align="center">' . (($scb > 0) ? '' . number_format(abs($scb), $decimal) . '' : '0.00') . '</td>';
                $data .= '<td align="center">' . (($scb < 0) ? '' . number_format(abs($scb), $decimal) . '' : '0.00') . '</td>';

                $data .= '</tr>';
                $tdr += $sdr;
                $tcr += $scr;
                $tcb_dr = $tdr;
                $tcb_cr = $tcr;
                if ($sob > 0) {
                    $tob_dr += $sob;
                }
                if ($sob < 0) {
                    $tob_cr += $sob;
                }
            }

        }
        $string .= '
                      <tr class="last-th">
                        <th></th>
                        <th width="10%" align="center">Opening Dr</th>
                        <th width="10%" align="center">Opening Cr</th>
                        <th width="10%" align="center">Debit</th>
                        <th width="10%" align="center">Credit </th>
                        <th width="12%" align="center">Closing Debit </th>
                        <th width="12%" align="center">Closing Credit </th>
                    </tr>
                     ';
        $string .= '<tr>';
        $string .= '<th align="right" colspan="1">Total</th>';
        $string .= '<th style="text-align: center;">' . number_format($tob_dr, $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format(abs($tob_cr), $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format($tdr, $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format($tcr, $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format($tcb_dr, $decimal) . '</th>';
        $string .= '<th style="text-align: center">' . number_format(abs($tcb_cr), $decimal) . '</th>';
        $string .= '</tr>';
        $data .= $string;
        echo '<body style="border: 0.1pt solid #ccc"><table>' . $data . '</table></body>';

    }
    //==========================End==========================
    /*
     * Function to export Trial Balance to Excel file.
     */
    protected function trialBalanceExcel($accountlist, $DefaultCurrency, $start_date, $end_date)
    {
        $spreadsheet = new Spreadsheet();

        // Set Default Attributes
        $spreadsheet->getProperties()->setCreator("Mustafa Mughal");
        $spreadsheet->getProperties()->setLastModifiedBy("Mustafa Mughal");
        $spreadsheet->getProperties()->setTitle("Trial Balance");
        $spreadsheet->getProperties()->setSubject("Trial Balance Report");
        $spreadsheet->getProperties()->setDescription("Function used to generate trial balance in excel format.");
        $this->sheet = $spreadsheet->getActiveSheet();
        $this->sheet->setCellValue('A1', 'Trial Balance from ' . $start_date . ' to ' . $end_date);

        $this->sheet->setCellValue('A2', 'Account Name');
        $this->sheet->setCellValue('B2', 'Account Type');
        $this->sheet->setCellValue('C2', 'Opening Balance (' . $DefaultCurrency->code . ')');
        $this->sheet->setCellValue('D2', 'Debit (' . $DefaultCurrency->code . ')');
        $this->sheet->setCellValue('E2', 'Credit (' . $DefaultCurrency->code . ')');
        $this->sheet->setCellValue('F2', 'Closing Balance (' . $DefaultCurrency->code . ')');

        // Print All Data into Excel File
        self::$excel_iterator = 3;
        $this->loopTrialBalanceExcel($accountlist, 0);

        // Now Print Footer into sheet
        $this->sheet->setCellValue('A' . self::$excel_iterator, '');
        $this->sheet->setCellValue('B' . self::$excel_iterator, '');
        $this->sheet->setCellValue('C' . self::$excel_iterator, 'Grand Total');
        $this->sheet->setCellValue('D' . self::$excel_iterator, CoreAccounts::toCurrency('d', $accountlist->dr_total));
        $this->sheet->setCellValue('E' . self::$excel_iterator, CoreAccounts::toCurrency('c', $accountlist->cr_total));


        $writer = new Xlsx($spreadsheet);
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        // It will be called file.xls
        header('Content-Disposition: attachment; filename="TrialBalanceReport.xlsx"');
        // Write file to the browser
        $writer->save('php://output');
    }

    /*
     * Function to iterator over N-Dimentional Data.
     */
    private function loopTrialBalanceExcel($account, $c = 0)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $counter = $c;

        /* Print groups */
        if ($account->id != 0) {
            $this->sheet->setCellValue('A' . self::$excel_iterator, html_entity_decode(AccountsList::printSpace($counter)) . AccountsList::toCodeWithName($account->code, $account->name));
            $this->sheet->setCellValue('B' . self::$excel_iterator, 'Group');
            $this->sheet->setCellValue('C' . self::$excel_iterator, CoreAccounts::toCurrency($account->op_total_dc, $account->op_total));
            $this->sheet->setCellValue('D' . self::$excel_iterator, CoreAccounts::toCurrency('d', $account->dr_total));
            $this->sheet->setCellValue('E' . self::$excel_iterator, CoreAccounts::toCurrency('c', $account->cr_total));

            if ($account->cl_total_dc == 'd') {
                $this->sheet->setCellValue('F' . self::$excel_iterator, CoreAccounts::toCurrency('d', $account->cl_total));
            } else {
                $this->sheet->setCellValue('F' . self::$excel_iterator, CoreAccounts::toCurrency('c', $account->cl_total));
            }

            self::$excel_iterator++;
        }

        /* Print child ledgers */
        if (count($account->children_ledgers) > 0) {
            $counter++;
            foreach ($account->children_ledgers as $id => $data) {
                $this->sheet->setCellValue('A' . self::$excel_iterator, html_entity_decode(AccountsList::printSpace($counter)) . AccountsList::toCodeWithName($data['code'], $data['name']));
                $this->sheet->setCellValue('B' . self::$excel_iterator, 'Ledger');
                $this->sheet->setCellValue('C' . self::$excel_iterator, CoreAccounts::toCurrency($data['op_total_dc'], $data['op_total']));
                $this->sheet->setCellValue('D' . self::$excel_iterator, CoreAccounts::toCurrency('d', $data['dr_total']));
                $this->sheet->setCellValue('E' . self::$excel_iterator, CoreAccounts::toCurrency('c', $data['cr_total']));

                if ($account->cl_total_dc == 'd') {
                    $this->sheet->setCellValue('F' . self::$excel_iterator, CoreAccounts::toCurrency('d', $data['cl_total']));
                } else {
                    $this->sheet->setCellValue('F' . self::$excel_iterator, CoreAccounts::toCurrency('c', $data['cl_total']));
                }

                self::$excel_iterator++;
            }
            $counter--;
        }

        /* Print child groups recursively */
        foreach ($account->children_groups as $id => $data) {
            $counter++;
            $this->loopTrialBalanceExcel($data, $counter);
            $counter--;
        }
    }

    /**
     * Load Groups by Account Type.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_groups(Request $request)
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Groups = '';
        if ($request->get('account_type_id')) {
            $Groups = GroupsTree::buildOptions(GroupsTree::buildTree(Groups::where('level', $request->get('account_type_id'))->OrderBy('code', 'asc')->get()->toArray()), 0);
        } else {
            $Groups = GroupsTree::buildOptions(GroupsTree::buildTree(Groups::OrderBy('code', 'asc')->get()->toArray()), 0);
        }

        return response()->json([
            'dropdown' => $Groups,
        ]);
    }

    /**
     * Load Ledgers by Account Type.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_ledgers(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        if ($request->get('account_type_id')) {
            $id = 0;
            $DefaultGroup = Groups::where(['parent_id' => 0, 'account_type_id' => $request->get('account_type_id')])->first();
            if ($DefaultGroup) {
                $id = $DefaultGroup->id;
            }
        } else {
            $id = 0;
        }

        $dropdown = '';

        /* Create list of ledgers to pass to view */
        $parentGroups = new LedgersTree();
        $parentGroups->current_id = -1;
        $parentGroups->restriction_bankcash = 1;
        $parentGroups->build($id);
        $parentGroups->toList($parentGroups, -1);
        $Ledgers = $parentGroups->ledgerList;

        if (count($Ledgers)) {
            foreach ($Ledgers as $id => $data) {
                if ($id == 0) {
                    continue;
                }
                if ($id < 0) {
                    $dropdown .= '<option value="' . $id . '" disabled="disabled">' . $data["name"] . '</option>';
                } else {
                    $dropdown .= '<option value="' . $id . '">' . $data["name"] . '</option>';
                }
            }
        }

        return response()->json([
            'dropdown' => $dropdown,
        ]);
    }


    /*
     * ------------------------------------------------------
     * Balance Sheet Report Start
     * ------------------------------------------------------
     */

    /**
     * Show Balance Sheet.
     *
     * @return \Illuminate\Http\Response
     */
    public function balance_sheet()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.account_reports.balance_sheet.index');
    }

    /**
     * Load Report of Trial Balance.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

    public function balance_sheet_report(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = '';
        $string = "";
        $type = "";
        $decimal = 0;
        $start_date = date('Y-m-d', strtotime($request->start));
        $end_date = date('Y-m-d', strtotime($request->end));
        $taob = 0;
        $type = $request->type;
        $Groups = Groups::whereIn('account_type_id', array(1, 2, 5))->OrderBy('code')->get();
        $tdr = 0;
        $tcr = 0;
        $level = 0;

        foreach ($Groups as $Group) {

            if ($Group->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $Group->level * 30;
                $Lmargin = $margin + 20;
                $font = 900 - 100 * $Group->level;
                $level = $Group->level * 1;
            }

            $data .= '<tr>';
            $data .= '<th colspan="2"> <span style="margin-left:' . $margin . 'px; font-weight:' . $font . '">' . $Group->number . '-' . $Group->name . '</span></th>';
            $data .= '</tr>';
            $Ledgers = Ledgers::where('group_id', $Group->id)->get();
            if (count($Ledgers) > 0) {
                foreach ($Ledgers as $Ledger) {
                    $closing_balance = CoreAccounts::closing_balance($Ledger->id, 0, $start_date, $end_date);
                    $data .= '<tr>';
                    //                $data .= '<table> <tbody>';
                    $aob = CoreAccounts::opening_balance1($end_date, $Ledger->id);
                    $taob += $aob;
                    foreach ($closing_balance as $cb) {
                        $conRate = Currencies::where('code', $cb[0])->first('rate');
                        $tdr += $cb[1] * $conRate->rate;
                        $tcr += $cb[2] * $conRate->rate;
                    }
                    $data .= '<th colspan="1"> <span style="margin-left:' . $margin . 'px; font-weight:' . $font . '">' . $Ledger->number . '-' . $Ledger->name . '</span></th>';
                    $data .= '<th colspan="1" style="text-align: right"> ' . number_format($aob, 2) . '</th>';
                    $data .= '</tr>';
                }

            }
        }

        return view('admin.account_reports.balance_sheet.report', compact('data', "start_date", "end_date"));
    }

    /*
     * Function to export Trial Balance to Excel file.
     */
    protected function balanceSheetExcel($bsheet, $DefaultCurrency, $start_date, $end_date)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $spreadsheet = new Spreadsheet();

        // Set Default Attributes
        $spreadsheet->getProperties()->setCreator("Mustafa Mughal");
        $spreadsheet->getProperties()->setLastModifiedBy("Mustafa Mughal");
        $spreadsheet->getProperties()->setTitle("Balance Sheet");
        $spreadsheet->getProperties()->setSubject("Balance Sheet Report");
        $spreadsheet->getProperties()->setDescription("Function used to generate balance sheet in excel format.");
        $this->sheet = $spreadsheet->getActiveSheet();
        $this->sheet->setCellValue('A1', 'Balance sheet as on' . $end_date);

        $this->sheet->setCellValue('A2', 'Assets (Dr)');
        $this->sheet->setCellValue('B2', 'Amount (' . $DefaultCurrency->code . ')');

        // Print All Data into Excel File
        self::$bs_iterator = 3;
        $this->loopBalanceSheetExcel($bsheet['assets'], -1);
        /* Total Assets */
        $this->sheet->setCellValue('A' . self::$bs_iterator, 'Total');
        $this->sheet->setCellValue('B' . self::$bs_iterator, CoreAccounts::toCurrency('d', $bsheet['assets_total']));
        self::$bs_iterator++;

        // Put Empty rows
        $this->sheet->setCellValue('A' . self::$bs_iterator, '');
        $this->sheet->setCellValue('B' . self::$bs_iterator, '');
        self::$bs_iterator++;
        $this->sheet->setCellValue('A' . self::$bs_iterator, '');
        $this->sheet->setCellValue('B' . self::$bs_iterator, '');
        self::$bs_iterator++;

        /*
         * Liabilities Starts
         */
        $this->sheet->setCellValue('A2', 'Liabilities and Owners Equity (Cr)');
        $this->sheet->setCellValue('B2', 'Amount (' . $DefaultCurrency->code . ')');

        $this->loopBalanceSheetExcel($bsheet['liabilities'], -1);
        /* Total Liabilities */
        $this->sheet->setCellValue('A' . self::$bs_iterator, 'Total');
        $this->sheet->setCellValue('B' . self::$bs_iterator, CoreAccounts::toCurrency('c', $bsheet['liabilities_total']));
        self::$bs_iterator++;
        // Put Empty rows
        $this->sheet->setCellValue('A' . self::$bs_iterator, '');
        $this->sheet->setCellValue('B' . self::$bs_iterator, '');
        self::$bs_iterator++;
        $this->sheet->setCellValue('A' . self::$bs_iterator, '');
        $this->sheet->setCellValue('B' . self::$bs_iterator, '');
        self::$bs_iterator++;


        $writer = new Xlsx($spreadsheet);
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        // It will be called file.xls
        header('Content-Disposition: attachment; filename="TrialBalanceReport.xlsx"');
        // Write file to the browser
        $writer->save('php://output');
    }

    /*
     * Function to iterator over N-Dimentional Data.
     */
    private function loopBalanceSheetExcel($account, $c = 0)
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $counter = $c;
        if (!in_array($account->id, Config('constants.accounts_main_heads'))) {
            $this->sheet->setCellValue('A' . self::$bs_iterator, html_entity_decode(AccountsList::printSpace($counter)) . AccountsList::toCodeWithName($account->code, $account->name));

            if (count($account->children_groups)) {
                $this->sheet->setCellValue('B' . self::$bs_iterator, '');
            } else {
                $this->sheet->setCellValue('B' . self::$bs_iterator, html_entity_decode(CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total)));
            }

            self::$bs_iterator++;
        }
        if (count($account->children_groups)) {
            foreach ($account->children_groups as $id => $data) {
                $counter++;
                $this->loopBalanceSheetExcel($data, $counter);
                $counter--;
            }

            if (!in_array($account->id, Config('constants.accounts_main_heads'))) {
                $this->sheet->setCellValue('A' . self::$bs_iterator, html_entity_decode(AccountsList::printSpace($counter)) . 'Total of ' . AccountsList::toCodeWithName($account->code, $account->name));
                $this->sheet->setCellValue('B' . self::$bs_iterator, html_entity_decode(CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total)));
                self::$bs_iterator++;
            }
        }
    }

    /*
     * ------------------------------------------------------
     * Balance Sheet Report End
     * ------------------------------------------------------
     */


    /*
     * ------------------------------------------------------
     * Profit & Loss Report Start
     * ------------------------------------------------------
     */

    /**
     * Show Profit & Loss.
     *
     * @return \Illuminate\Http\Response
     */
    public function profit_loss()
    {

if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $EntryTypes = EntryTypes::pluckActiveOnly();
        $EntryTypes->prepend('Select an Entry Type', '');

        return view('admin.account_reports.profit_loss.index', compact('EntryTypes'));
    }

    /**
     * Load Report of Profit & Loss.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function profit_loss_report(Request $request)
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $type = "";
        $type = $request->type;
        $expData = '';
        $texp_balance = 0;
        $incomeData = '';
        $tincome_balance = 0;
        $currencyID = $request->currency_id;
        $decimal = Currencies::where('id', 1)->first('decimal_fixed_point');
        $decimal = $decimal->decimal_fixed_point;

        if ($request->start_range && $request->end_range) {
            $start_date = date('Y-m-d', strtotime($request->start_range));
            $end_date = date('Y-m-d', strtotime($request->end_range));

        } else {
            $start_date = null;
            $end_date = null;
        }
        $GroupIncome = Groups::where('account_type_id', 3)->OrderBy('code')->get();
        foreach ($GroupIncome as $income) {
            if ($income->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $income->level * 30;
                $margin = +20;
                $font = 900 - 100 * $income->level;
            }
            $incomeData .= '<tr>';
            $incomeData .= '<td colspan="2"><span style="margin-left:' . $margin . '">' . $income->name . '</span></td>';
            $incomeData .= '</tr>';
            $Ledgers = Ledgers::where('group_id', $income->id)->get();
            foreach ($Ledgers as $Ledger) {
                $closing_balance = CoreAccounts::closing_balance($Ledger->id, $currencyID, $start_date, $end_date);
                foreach ($closing_balance as $cb) {
                    $conRate = Currencies::where('code', $cb[0])->first('rate');
                    $conRate = $conRate->rate;
                    $bal = abs($cb[4]);
                    $incomeData .= '<tr style="text-align: center">';
                    $incomeData .= '<td align="right"> ' . ucfirst($Ledger->name) . ' (' . $cb[0] . ')</td>';
                    $incomeData .= '<td align="right">(' . number_format($bal * $conRate, $cb[3]) . ')</td>';
                    $incomeData .= '</tr>';
                    $tincome_balance += abs($bal * $conRate);
                }
            }
        }
        $incomeData .= '
                    <tr class="bold-text bg-filled">
                        <td>Gross Incomes</td>
                        <td style="text-align: right; border-top: 1px solid black; border-bottom: 1px solid black;" align="right">(' . number_format($tincome_balance, $decimal) . ')</td>
                    </tr>';
        $GroupExp = Groups::where('account_type_id', 4)->OrderBy('code')->get();
        foreach ($GroupExp as $Exp) {
            if ($Exp->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $Exp->level * 30;
                $Lmargin = $margin + 20;
                $font = 900 - 100 * $Exp->level;
            }
            $expData .= '<tr>';
            $expData .= '<td colspan="2"><span style="margin-left:' . $margin . '">' . $Exp->name . '</span></td>';
            $expData .= '</tr>';
            $Ledgers = Ledgers::where('group_id', $Exp->id)->get();
            foreach ($Ledgers as $Ledger) {
                $closing_balance = CoreAccounts::closing_balance($Ledger->id, $currencyID, $start_date, $end_date);
                foreach ($closing_balance as $cb) {
                    $conRate = Currencies::where('code', $cb[0])->first('rate');
                    $conRate = $conRate->rate;
                    $expData .= '<tr style="text-align: center">';
                    $expData .= '<td align="right"> ' . ucfirst($Ledger->name) . ' (' . $cb[0] . ')</td>';
                    $expData .= '<td align="right">' . number_format($cb[4] * $conRate, $cb[3]) . '</td>';
                    $expData .= '</tr>';
                    $texp_balance += ($cb[4] * $conRate);
                }
            }
        }
        $expData .= '
                <tr class="bold-text bg-filled">
                    <td>Gross Expenses</td>
                    <td style="text-align: right; border-top: 1px solid black; border-bottom: 1px solid black;" align="right">' . number_format($texp_balance, $decimal) . '</td>
                </tr>';
        $net = ($tincome_balance) + ($texp_balance);
        if ($net > 0) {
            $net = number_format($net, $decimal);
        } else {
            $net = abs($net);
            $net = '(' . number_format($net, $decimal) . ')';
        }
        $expData .= '<tr class="bold-text">
                    <td>Net (Profit)/Loss</td>
                    <td align="right">' . $net . '</td>
                </tr>';


        $content = view('admin.account_reports.profit_loss.report', compact('expData', 'incomeData', 'start_date', 'end_date'));
        if ($type == 'pdf') {
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            $mpdf->WriteHTML($content);
            $mpdf->Output('profit-loss-report.pdf', 'D');
        } else if ($type == 'excel') {
            self::profit_loss_excel($request->all());
        } else {
            return $content;
        }
    }

    public function profit_loss_excel($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        header('Content-Type: application/vnd.ms-excel charset=UTF-8');
        header('Content-disposition: attachment; filename=' . rand() . '.xls');
        $data = '';
        $expData = '';
        $texp_balance = 0;
        $incomeData = '';
        $tincome_balance = 0;
        $currencyID = $request['currency_id'];
        $decimal = Currencies::where('id', 1)->first('decimal_fixed_point');
        $decimal = $decimal->decimal_fixed_point;
        if ($request['date_range']) {
            $date_range = explode(' - ', $request['date_range']);
            $start_date = date('Y-m-d', strtotime($date_range[0]));
            $end_date = date('Y-m-d', strtotime($date_range[1]));
        } else {
            $start_date = null;
            $end_date = null;
        }
        $data .= '<style>
                table{width: 100%;}
                td,th {
                    border: 0.1pt solid #ccc;
                }
                </style>';
        $data .= excel_header();
        //fetch all incomes groups
        $GroupIncome = Groups::where('account_type_id', 3)->OrderBy('code')->get();
        foreach ($GroupIncome as $income) {
            if ($income->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $income->level * 30;
                $margin = $margin + 20;
                $font = 900 - 100 * $income->level;
            }
            $incomeData .= '<tr>';
            $incomeData .= '<th>Incomes (Cr)</th>';
            $incomeData .= '<th>Amount (Pkr)</th>';
            $incomeData .= '</tr>';
            $incomeData .= '<tr>';
            $incomeData .= '<td><span style="margin-left:' . $margin . '">' . $income->name . '</span></td>';
            $incomeData .= '</tr>';
            $Ledgers = Ledgers::where('group_id', $income->id)->get();
            foreach ($Ledgers as $Ledger) {
                $closing_balance = CoreAccounts::closing_balance($Ledger->id, $currencyID, $start_date, $end_date);
                foreach ($closing_balance as $cb) {
                    $conRate = Currencies::where('code', $cb[0])->first('rate');
                    $conRate = $conRate->rate;
                    $bal = abs($cb[4]);
                    $incomeData .= '<tr style="text-align: center">';
                    $incomeData .= '<td align="right"> ' . ucfirst($Ledger->name) . ' (' . $cb[0] . ')</td>';
                    $incomeData .= '<td align="right">(' . number_format($bal * $conRate, $cb[3]) . ')</td>';
                    $incomeData .= '</tr>';
                    $tincome_balance += abs($bal * $conRate);
                }
            }
        }
        $incomeData .= '
                    <tr class="bold-text bg-filled">
                        <td>Gross Incomes</td>
                        <td style="text-align: right; border-top: 1px solid black; border-bottom: 1px solid black;" align="right">(' . number_format($tincome_balance, $decimal) . ')</td>
                    </tr>';
        //fetch all expenses groups
        $expData .= '<tr>';
        $expData .= '<th>Expenses (Dr)</th>';
        $expData .= '<th>Amount (Pkr)</th>';
        $expData .= '</tr>';
        $GroupExp = Groups::where('account_type_id', 4)->OrderBy('code')->get();
        foreach ($GroupExp as $Exp) {
            if ($Exp->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $Exp->level * 30;
                $Lmargin = $margin + 20;
                $font = 900 - 100 * $Exp->level;
            }
            $expData .= '<tr>';
            $expData .= '<td><span style="margin-left:' . $margin . '">' . $Exp->name . '</span></td>';
            $expData .= '</tr>';
            $Ledgers = Ledgers::where('group_id', $Exp->id)->get();
            foreach ($Ledgers as $Ledger) {
                //                echo $ob=CoreAccounts::opening_balance($start_date, 0, 54, $currencyID);
                $closing_balance = CoreAccounts::closing_balance($Ledger->id, $currencyID, $start_date, $end_date);
                foreach ($closing_balance as $cb) {
                    $conRate = Currencies::where('code', $cb[0])->first('rate');
                    $conRate = $conRate->rate;
                    $expData .= '<tr style="text-align: center">';
                    $expData .= '<td align="right"> ' . ucfirst($Ledger->name) . ' (' . $cb[0] . ')</td>';
                    $expData .= '<td align="right">' . number_format($cb[4] * $conRate, $cb[3]) . '</td>';
                    $expData .= '</tr>';
                    $texp_balance += ($cb[4] * $conRate);
                }
            }
        }
        $expData .= '
                <tr class="bold-text bg-filled">
                    <td>Gross Expenses</td>
                    <td style="text-align: right; border-top: 1px solid black; border-bottom: 1px solid black;" align="right">' . number_format($texp_balance, $decimal) . '</td>
                </tr>';
        $net = ($tincome_balance) + ($texp_balance);
        if ($net > 0) {
            $net = number_format($net, $decimal);
        } else {
            $net = abs($net);
            $net = '(' . number_format($net, $decimal) . ')';
        }
        //end expenses.....................
        $expData .= '<tr class="bold-text">
                    <td>Net (Profit)/Loss</td>
                    <td align="right">' . $net . '</td>
                </tr>';
        $data .= $incomeData . $expData;
        echo '<body style="border: 0.1pt solid #ccc"><table>' . $data . '</table></body>';
    }

    /*
     * Function to export Trial Balance to Excel file.
     */
    protected function profitLossExcel($pandl, $DefaultCurrency, $start_date, $end_date)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $spreadsheet = new Spreadsheet();

        // Set Default Attributes
        $spreadsheet->getProperties()->setCreator("Mustafa Mughal");
        $spreadsheet->getProperties()->setLastModifiedBy("Mustafa Mughal");
        $spreadsheet->getProperties()->setTitle("Balance Sheet");
        $spreadsheet->getProperties()->setSubject("Balance Sheet Report");
        $spreadsheet->getProperties()->setDescription("Function used to generate balance sheet in excel format.");
        $this->sheet = $spreadsheet->getActiveSheet();
        $this->sheet->setCellValue('A1', 'Profit and Loss as on ' . $end_date);

        $this->sheet->setCellValue('A2', 'Incomes (Cr)');
        $this->sheet->setCellValue('B2', 'Amount (' . $DefaultCurrency->code . ')');

        // Print All Data into Excel File
        self::$pandl_iterator = 3;
        $this->loopProfitLossExcel($pandl['gross_incomes'], -1);
        /* Total Assets */
        $this->sheet->setCellValue('A' . self::$pandl_iterator, 'Gross Incomes');
        $this->sheet->setCellValue('B' . self::$pandl_iterator, CoreAccounts::toCurrency('d', $pandl['gross_income_total']));
        self::$pandl_iterator++;

        // Put Empty rows
        $this->sheet->setCellValue('A' . self::$pandl_iterator, '');
        $this->sheet->setCellValue('B' . self::$pandl_iterator, '');
        self::$pandl_iterator++;
        $this->sheet->setCellValue('A' . self::$pandl_iterator, '');
        $this->sheet->setCellValue('B' . self::$pandl_iterator, '');
        self::$pandl_iterator++;

        /*
         * Liabilities Starts
         */
        $this->sheet->setCellValue('A2', 'Expenses (Dr)');
        $this->sheet->setCellValue('B2', 'Amount (' . $DefaultCurrency->code . ')');

        $this->loopProfitLossExcel($pandl['gross_expenses'], -1);
        /* Total Liabilities */
        $this->sheet->setCellValue('A' . self::$pandl_iterator, 'Gross Expenses');
        $this->sheet->setCellValue('B' . self::$pandl_iterator, CoreAccounts::toCurrency('c', $pandl['gross_expense_total']));
        self::$pandl_iterator++;
        // Put Empty rows
        $this->sheet->setCellValue('A' . self::$pandl_iterator, '');
        $this->sheet->setCellValue('B' . self::$pandl_iterator, '');
        self::$pandl_iterator++;
        $this->sheet->setCellValue('A' . self::$pandl_iterator, '');
        $this->sheet->setCellValue('B' . self::$pandl_iterator, '');
        self::$pandl_iterator++;


        $writer = new Xlsx($spreadsheet);
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        // It will be called file.xls
        header('Content-Disposition: attachment; filename="ProfitNLossReport.xlsx"');
        // Write file to the browser
        $writer->save('php://output');
    }

    /*
     * Function to iterator over N-Dimentional Data.
     */
    private function loopProfitLossExcel($account, $c = 0)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $counter = $c;
        if (!in_array($account->id, Config('constants.accounts_main_heads'))) {
            $this->sheet->setCellValue('A' . self::$pandl_iterator, html_entity_decode(AccountsList::printSpace($counter)) . AccountsList::toCodeWithName($account->code, $account->name));

            if (count($account->children_groups)) {
                $this->sheet->setCellValue('B' . self::$pandl_iterator, '');
            } else {
                $this->sheet->setCellValue('B' . self::$pandl_iterator, html_entity_decode(CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total)));
            }

            self::$pandl_iterator++;
        }
        if (count($account->children_groups)) {
            foreach ($account->children_groups as $id => $data) {
                $counter++;
                $this->loopProfitLossExcel($data, $counter);
                $counter--;
            }

            if (!in_array($account->id, Config('constants.accounts_main_heads'))) {
                $this->sheet->setCellValue('A' . self::$pandl_iterator, html_entity_decode(AccountsList::printSpace($counter)) . 'Total of ' . AccountsList::toCodeWithName($account->code, $account->name));
                $this->sheet->setCellValue('B' . self::$pandl_iterator, html_entity_decode(CoreAccounts::toCurrency($account->cl_total_dc, $account->cl_total)));
                self::$pandl_iterator++;
            }
        }
    }

    /*
     * ------------------------------------------------------
     * Balance Sheet Report End
     * ------------------------------------------------------
     */

    /*
     * ------------------------------------------------------
     * Ledger Statement Report Start
     * ------------------------------------------------------
     */

    /**
     * Show Ledger Statement.
     *
     * @return \Illuminate\Http\Response
     */
    public function ledger_statement()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        if (!Gate::allows('account_reports_ledger_statement')) {
            return abort(401);
        }

        // Get All Employees
        $Employees = Employees::pluckActiveOnly();
        $Employees->prepend('Select an Employee', '');

        // Get All Branches
        $Branches = Branches::pluckActiveOnly();
        $Branches->prepend('Select a Branch', '');

        // Get All Departments
        $Departments = Departments::pluckActiveOnly();
        $Departments->prepend('Select a Department', '');

        // Get All Account Types
        $AccountTypes = AccountTypes::getActiveListDropdown(true);

        // Get All Entry Types
        $EntryTypes = EntryTypes::pluckActiveOnly();
        $EntryTypes->prepend('Select an Entry Type', '');

        /* Create list of ledgers to pass to view */
        $parentGroups = new LedgersTree();
        $parentGroups->current_id = -1;
        $parentGroups->restriction_bankcash = 1;
        $parentGroups->build(0);
        $parentGroups->toList($parentGroups, -1);
        $Ledgers = $parentGroups->ledgerList;

        return view('admin.account_reports.ledger_statement.index', compact('Employees', 'Branches', 'Departments', 'AccountTypes', 'EntryTypes', 'Groups', 'Ledgers'));
    }

    /**
     * Load Report of Trial Balance.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function ledger_statement_report(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        if ($request->get('date_range')) {
            $date_range = explode(' - ', $request->get('date_range'));
            $start_date = date('Y-m-d', strtotime($date_range[0]));
            $end_date = date('Y-m-d', strtotime($date_range[1]));
        } else {
            $start_date = null;
            $end_date = null;
        }

        $Ledger = Ledgers::find($request->get('group_id'));

        if (!$Ledger) {
            return;
        }
        // echo '<pre>';print_r($start_date);echo'</pre>';exit;
        // Openinig Balance
        $op = Ledgers::openingBalance($Ledger->id, $start_date, $request->all());
        //$op = $Ledger->opening_balance;

        // Closing Balance
        $cl = Ledgers::closingBalance($Ledger->id, null, $end_date);

        $where = array(
            'entry_items.status' => 0,
            'entry_items.ledger_id' => $Ledger->id,
        );

        // Set Branch ID if exists
        if ($request->get('branch_id')) {
            $where['entries.branch_id'] = $request->get('branch_id');
        }
        // Set Department ID if exists
        if ($request->get('department_id')) {
            $where['entries.department_id'] = $request->get('department_id');
        }
        // Set Employee ID if exists
        if ($request->get('employee_id')) {
            $where['entries.employee_id'] = $request->get('employee_id');
        }

        $query = Entries::join('entry_items', 'entries.id', '=', 'entry_items.entry_id')
            ->where($where);
        if (!is_null($start_date) && $start_date) {
            $query->where('entry_items.voucher_date', '>=', $start_date);
        }
        if (!is_null($end_date) && $end_date) {
            $query->where('entry_items.voucher_date', '<=', $end_date);
        }

        $Entries = $query->get();
        //echo'<pre>'; print_r($Entries);echo'</pre>';exit;
        $DefaultCurrency = Currencies::getDefaultCurrencty();
        $EntryTypes = EntryTypes::all()->getDictionary();

        switch ($request->get('medium_type')) {
            case 'web':
                return view('admin.account_reports.ledger_statement.report', compact('Entries', 'start_date', 'end_date', 'DefaultCurrency', 'op', 'cl', 'Ledger', 'EntryTypes'));
                break;
            case 'print':
                return view('admin.account_reports.ledger_statement.report', compact('Entries', 'start_date', 'end_date', 'DefaultCurrency', 'op', 'cl', 'Ledger', 'EntryTypes'));
                break;
            case 'excel':
                $this->ledgerStatementExcel($Entries, $start_date, $end_date, $DefaultCurrency, $op, $cl, $Ledger, $EntryTypes);
                break;
            case 'pdf':
                $content = View::make('admin.account_reports.ledger_statement.report', compact('Entries', 'start_date', 'end_date', 'DefaultCurrency', 'op', 'cl', 'Ledger', 'EntryTypes'))->render();
                // Create an instance of the class:
                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
                // Write some HTML code:
                $mpdf->WriteHTML($content);
                // Output a PDF file directly to the browser
                $mpdf->Output('TrialBalanceReport.pdf', 'D');
                break;
            default:
                return view('admin.account_reports.ledger_statement.report', compact('Entries', 'start_date', 'end_date', 'DefaultCurrency', 'op', 'cl', 'Ledger', 'EntryTypes'));
                break;
        }
    }


    /*
     * Function to export Trial Balance to Excel file.
     */
    protected function ledgerStatementExcel($Entries, $start_date, $end_date, $DefaultCurrency, $op, $cl, $Ledger, $EntryTypes)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $spreadsheet = new Spreadsheet();

        // Set Default Attributes
        $spreadsheet->getProperties()->setCreator("Mustafa Mughal");
        $spreadsheet->getProperties()->setLastModifiedBy("Mustafa Mughal");
        $spreadsheet->getProperties()->setTitle("Ledger Statement");
        $spreadsheet->getProperties()->setSubject("Ledger Statement Report");
        $spreadsheet->getProperties()->setDescription("Function used to generate ledger statement in excel format.");
        $this->sheet = $spreadsheet->getActiveSheet();
        $this->sheet->setCellValue('A1', 'Ledger Statement for ' . $Ledger->name . ' from ' . $start_date . ' to ' . $end_date);

        $this->sheet->setCellValue('A3', 'Date');
        $this->sheet->setCellValue('B3', 'Number');
        $this->sheet->setCellValue('C3', 'Ledger');
        $this->sheet->setCellValue('D3', 'Entry Type');
        $this->sheet->setCellValue('E3', 'Debit (' . $DefaultCurrency->code . ')');
        $this->sheet->setCellValue('F3', 'Credit (' . $DefaultCurrency->code . ')');
        $this->sheet->setCellValue('G3', 'Balance (' . $DefaultCurrency->code . ')');

        $entry_balance['amount'] = $op['amount'];
        $entry_balance['dc'] = $op['dc'];

        $this->sheet->setCellValue('A4', 'Current opening balance');
        $this->sheet->setCellValue('G4', CoreAccounts::toCurrency($entry_balance['dc'], $entry_balance['amount']));

        self::$ls_iterator = 5;
        foreach ($Entries as $entry) {
            /* Calculate current entry balance */
            $entry_balance = CoreAccounts::calculate_withdc(
                $entry_balance['amount'],
                $entry_balance['dc'],
                $entry['amount'],
                $entry['dc']
            );

            $this->sheet->setCellValue('A' . self::$ls_iterator, $entry->voucher_date);
            $this->sheet->setCellValue('B' . self::$ls_iterator, $entry->number);
            $this->sheet->setCellValue('C' . self::$ls_iterator, Ledgers::entryLedgers($entry->id));
            $this->sheet->setCellValue('D' . self::$ls_iterator, $EntryTypes[$entry->entry_type_id]->code);
            if ($entry['dc'] == 'd') {
                $this->sheet->setCellValue('E' . self::$ls_iterator, CoreAccounts::toCurrency('d', $entry['amount']));
                $this->sheet->setCellValue('F' . self::$ls_iterator, '');
            } elseif ($entry['dc'] == 'c') {
                $this->sheet->setCellValue('E' . self::$ls_iterator, '');
                $this->sheet->setCellValue('F' . self::$ls_iterator, CoreAccounts::toCurrency('c', $entry['amount']));
            } else {
                $this->sheet->setCellValue('E' . self::$ls_iterator, '');
                $this->sheet->setCellValue('F' . self::$ls_iterator, '');
            }
            $this->sheet->setCellValue('G' . self::$ls_iterator, CoreAccounts::toCurrency($entry_balance['dc'], $entry_balance['amount']));

            self::$ls_iterator++;
        }

        $this->sheet->setCellValue('A' . self::$ls_iterator, 'Current closing balance');
        $this->sheet->setCellValue('G' . self::$ls_iterator, CoreAccounts::toCurrency($entry_balance['dc'], $entry_balance['amount']));

        self::$ls_iterator = 5;


        $writer = new Xlsx($spreadsheet);
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        // It will be called file.xls
        header('Content-Disposition: attachment; filename="LedgerStatementReport.xlsx"');
        // Write file to the browser
        $writer->save('php://output');
    }

    /*
     * ------------------------------------------------------
     * Ledger Statement Report End
     * ------------------------------------------------------
     */
    public function expense_summary()
    {
        // Get All Branches
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.account_reports.expense_reports.expense');
    }

    public static function expense_summary_report(Request $request)
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }

        $expData = '';
        $decimal = 0;
        $currencyID = 0;
        $texp_balance = 0;
        $type = "";
        $type = $request->type;
        $start_date = null;
        $end_date = null;

        if ($request->start && $request->end) {
            $start_date = date('Y-m-d', strtotime($request->start));
            $end_date = date('Y-m-d', strtotime($request->end));
        } else {
            $start_date = null;
            $end_date = null;
        }
        if ($request->currency_id > 0) {
            $currencyID = $request->currency_id;
        }
        //        $closing_balance = CoreAccounts::closing_balance(110, 1, $start_date, $end_date);
//        dd($closing_balance, $start_date, $end_date);

        $decimal = Currencies::where('id', 1)->first('decimal_fixed_point');
        $decimal = $decimal->decimal_fixed_point;
        //fetch all expenses groups
        $GroupExp = Groups::where('account_type_id', 3)->OrderBy('code')->get();

        foreach ($GroupExp as $Exp) {
            if ($Exp->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $Exp->level * 30;
                $Lmargin = $margin + 20;
                $font = 900 - 100 * $Exp->level;
            }
            $expData .= '<tr>';
            $expData .= '<td colspan="2"><span style="margin-left:' . $margin . '">' . $Exp->name . '</span></td>';
            $expData .= '</tr>    ';
            $Ledgers = Ledgers::where('group_id', $Exp->id)->get();
            if (count($Ledgers) > 0)

                foreach ($Ledgers as $Ledger) {

                    //                echo $ob=CoreAccounts::opening_balance($start_date, 0, 54, $currencyID);
                    $closing_balance = CoreAccounts::closing_balance1($Ledger->id, 1, $start_date, $end_date);
                    foreach ($closing_balance as $cb) {
                        $conRate = Currencies::where('code', $cb[0])->first('rate');
                        $expData .= '<tr style="text-align: center">';
                        $expData .= '<td align="right"> ' . ucfirst($Ledger->name) . ' (' . $cb[0] . ')</td>';
                        $expData .= '<td align="right">' . number_format(($cb[4] * $conRate->rate), $decimal) . '</td>';
                        $expData .= '</tr>';
                        $texp_balance += ($cb[4] * $conRate->rate);
                    }
                }
        }


        //        dd( $expData);
        $expData .= '


                <tr class="bold-text bg-filled" style="margin-top: 30px">
                    <td>Gross Expenses</td>
                    <td style="text-align: right; border-top: 1px solid black; border-bottom: 1px solid black;" align="right">' . number_format($texp_balance, $decimal) . '</td>
                </tr>';
        $content = view('admin.account_reports.expense_reports.expense_report', compact('expData', 'start_date', 'end_date'));
        if ($type == 'pdf') {
            $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
            // Write some HTML code:
            $mpdf->WriteHTML($content);
            // Output a PDF file directly to the browser
            $mpdf->Output('expense_summary.pdf', 'D');
        } elseif ($type == 'excel') {
            return $content;
            return self::expense_summary_excel($request->all());
        } else {
            return $content;
        }

    }

    //convert expense summary in excel foramt
    public static function expense_summary_excel($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        header('Content-Type: application/vnd.ms-excel charset=UTF-8');
        header('Content-disposition: attachment; filename=' . rand() . '.xls');
        $texp_balance = 0;
        $data = '';
        $decimal = 0;
        $currencyID = 0;
        // Set Default Attributes
        if ($request->start && $request->end) {
            $start_date = date('Y-m-d', strtotime($request->start));
            $end_date = date('Y-m-d', strtotime($request->end));
        } else {
            $start_date = null;
            $end_date = null;
        }
        if ($request['currency_id'] > 0) {
            $currencyID = $request['currency_id'];
        }
        $decimal = Currencies::where('id', 1)->first('decimal_fixed_point');
        $decimal = $decimal->decimal_fixed_point;
        $data .= '<style>
                table{width: 100%;}
                td,th {
                    border: 0.1pt solid #ccc;
                }
                </style>';
        $data .= excel_header();
        $data .= '<tr><th colspan="4">Expense Summary AS on ' . $start_date . ' </th></tr>';
        $data .= '<tr>';
        $data .= '<th>Expenses (Dr)</th>';
        $data .= '<th>Amount</th>';
        $data .= '</tr>';
        //fetch all expenses groups
        $GroupExp = Groups::where('account_type_id', 3)->OrderBy('code')->get();
        foreach ($GroupExp as $Exp) {
            if ($Exp->level == 1) {
                $margin = 0;
                $font = "";
            } else {
                $margin = $Exp->level * 30;
                $Lmargin = $margin + 20;
                $font = 900 - 100 * $Exp->level;
            }
            $data .= '<tr>';
            $data .= '<td><span style="margin-left:' . $margin . '">' . $Exp->name . '</span></td>';
            $data .= '</tr>';
            $Ledgers = Ledgers::where('group_id', $Exp->id)->get();
            foreach ($Ledgers as $Ledger) {
                //                echo $ob=CoreAccounts::opening_balance($start_date, 0, 54, $currencyID);
                $closing_balance = CoreAccounts::closing_balance($Ledger->id, $currencyID, $start_date, $end_date);
                foreach ($closing_balance as $cb) {
                    $conRate = Currencies::where('code', $cb[0])->first('rate');
                    $data .= '<tr style="text-align: center">';
                    $data .= '<td align="right"> ' . ucfirst($Ledger->name) . '</td>';
                    $data .= '<td align="right">' . number_format($cb[4] * $conRate->rate, $decimal) . '</td>';
                    $data .= '</tr>';
                    $texp_balance += ($cb[4] * $conRate->rate);
                }
            }
        }
        $data .= '
                <tr>
                    <td style="border-top: 1px solid black;border-bottom: 1px solid black;">Gross Expenses</td>
                    <td style="border-top: 1px solid black;border-bottom: 1px solid black;" align="right">' . number_format($texp_balance, $decimal) . '</td>
                </tr>';

        echo '<body style="border: 0.1pt solid #ccc"><table>' . $data . '</table></body>';

    }
}

