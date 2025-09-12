<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Ledger;
use App\Models\Admin\Groups;
use App\Models\Admin\Ledgers;
use App\Models\Admin\EntryItems;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AccountsHelper
{
    function getLedgersByMainGroup($group_ids, $branch_id, $ledgerArray)
    {
        foreach ($group_ids as $group_id) {
            $group = Groups::where('id', $group_id)->first()->toArray();

            if (!$group) {
                continue;
            }

            if (!isset($group['id'])) {
                continue;
            }

            if (isset($group['level']) && $group['level'] < 5) {
                $getGroupChild = $this->getGroupChild($group['id']);
                $childIds = array_column($getGroupChild, 'id');

                if (!empty($childIds)) {
                    $ledgerArray = $this->getLedgersByMainGroup($childIds, $branch_id, $ledgerArray);
                }
            } else {

                $ledgers = $this->getLedgers($group['id'], $branch_id);
                $ledgerIds = array_column($ledgers, 'id');
                $ledgerArray = array_merge($ledgerArray, $ledgerIds);
            }
        }

        return $ledgerArray;
    }

    function getGroup($group_id)
    {
        return Groups::where('id', $group_id)->select('id', 'number', 'name', 'level', 'parent_id')->get()->toArray();
    }

    function getGroupChild($group_id)
    {
        return Groups::where('parent_id', $group_id)->select('id', 'number', 'name', 'level', 'parent_id')->get()->toArray();
    }

    function getLedgers($group_id, $branch_id)
    {
        $ledger = Ledgers::query();
        if ($branch_id) {
            $ledger = $ledger->where('branch_id', $branch_id);
        }

        return $ledger->where('group_id', $group_id)->select('id', 'name', 'number', 'group_number', 'group_id',
            'branch_id', 'opening_balance', 'balance_type')->get()->toArray();
    }

    function getLedgerEntries($ledger, $start_date, $end_date)
    {
        $baseQuery = EntryItems::where('ledger_id', $ledger['id'])
            ->select('entry_id', 'ledger_id', 'dc', 'amount', 'voucher_date');
        $openingQuery = clone $baseQuery;
        $opening = $openingQuery->whereDate('voucher_date', '<', $start_date);
        $opening_balance = $ledger['opening_balance'];
        $opening_balance_type = $ledger['balance_type'];
        $op_dr_sum = (clone $opening)->where('dc', 'd')->sum('amount');
        $op_cr_sum = (clone $opening)->where('dc', 'c')->sum('amount');
        if ($opening_balance_type == 'd') {
            $op_dr_sum += $opening_balance;
        } else {
            $op_cr_sum += $opening_balance;
        }
        $start_date = Carbon::parse($start_date)->toDateString();
        $end_date = Carbon::parse($end_date)->toDateString();
        $transactionQuery = clone $baseQuery;
        $dr_cr = $transactionQuery->whereBetween('voucher_date', [$start_date, $end_date]);
        $dr_sum = (clone $dr_cr)->where('dc', 'd')->sum('amount');
        $cr_sum = (clone $dr_cr)->where('dc', 'c')->sum('amount');
        $closing_dr = $op_dr_sum + $dr_sum;
        $closing_cr = $op_cr_sum + $cr_sum;
        return [
            'opening_dr' => $op_dr_sum,
            'opening_cr' => $op_cr_sum,
            'dr' => $dr_sum,
            'cr' => $cr_sum,
            'closing_dr' => $closing_dr,
            'closing_cr' => $closing_cr,
        ];
    }

    function buildTrialBalance($groups, &$trialHash, $start_date, $end_date, $branch_id)
    {
        foreach ($groups as $g) {
            $trialHash[$g['id']] = [
                'id' => $g['id'],
                'name' => $g['name'],
                'number' => $g['number'],
                'level' => $g['level'],
                'groups' => [],
                'ledgers' => [],
                'sum' => [
                    'opening_dr' => 0,
                    'opening_cr' => 0,
                    'dr' => 0,
                    'cr' => 0,
                    'closing_dr' => 0,
                    'closing_cr' => 0,
                ],
            ];
            if ($g['level'] < 5) {
                $groupChildren = $this->getGroupChild($g['id']);
                if (!empty($groupChildren)) {
                    $trialHash[$g['id']]['groups'] = $this->buildTrialBalance($groupChildren, $trialHash[$g['id']]['groups'], $start_date, $end_date, $branch_id);
                }
            } else {
                $ledgers = $this->getLedgers($g['id'], $branch_id);
                $processedLedgers = [];
                if (!empty($ledgers)) {
                    foreach ($ledgers as $key => $ledger) {
                        $processedLedgers[$key] = [
                            'id' => $ledger['id'],
                            'name' => $ledger['name'],
                            'number' => $ledger['number'],
                            'group_number' => $ledger['group_number'],
                            'group_id' => $ledger['group_id'],
                            'sum' => $this->getLedgerEntries($ledger, $start_date, $end_date),
                        ];
                    }
                    $trialHash[$g['id']]['ledgers'] += $processedLedgers;
                }
            }
        }
        return $trialHash;
    }

    function buildTrialBalanceForLevelFour($group, &$trialHash, $start_date, $end_date, $branch_id)
    {
        $trialHash[$group['id']] = [
            'id' => $group['id'],
            'name' => $group['name'],
            'number' => $group['number'],
            'level' => $group['level'],
            'groups' => [],
            'ledgers' => [],
            'sum' => [
                'opening_dr' => 0,
                'opening_cr' => 0,
                'dr' => 0,
                'cr' => 0,
                'closing_dr' => 0,
                'closing_cr' => 0,
            ],
        ];
        $ledgers = $this->getLedgers($group['id'], $branch_id);
        $processedLedgers = [];
        if (!empty($ledgers)) {
            foreach ($ledgers as $key => $ledger) {
                $processedLedgers[$key] = [
                    'id' => $ledger['id'],
                    'name' => $ledger['name'],
                    'number' => $ledger['number'],
                    'group_number' => $ledger['group_number'],
                    'group_id' => $ledger['group_id'],
                    'sum' => $this->getLedgerEntries($ledger, $start_date, $end_date),
                ];
            }
            $trialHash[$group['id']]['ledgers'] += $processedLedgers;
        }
        return $trialHash;
    }

    public static function calculateSums(array &$array)
    {
        foreach ($array as &$node) {
            self::sumTree($node);
        }

        return $array;
    }

    private static function sumTree(array &$node): array
    {
        $total = [
            'opening_dr' => 0,
            'opening_cr' => 0,
            'dr' => 0,
            'cr' => 0,
            'closing_dr' => 0,
            'closing_cr' => 0,
        ];

        if (isset($node['ledgers']) && is_array($node['ledgers'])) {
            foreach ($node['ledgers'] as $ledger) {
                $total['opening_dr'] += $ledger['sum']['opening_dr'] ?? 0;
                $total['opening_cr'] += $ledger['sum']['opening_cr'] ?? 0;
                $total['dr'] += $ledger['sum']['dr'] ?? 0;
                $total['cr'] += $ledger['sum']['cr'] ?? 0;
                $total['closing_dr'] += $ledger['sum']['closing_dr'] ?? 0;
                $total['closing_cr'] += $ledger['sum']['closing_cr'] ?? 0;
            }
        }

        if (isset($node['groups']) && is_array($node['groups'])) {
            foreach ($node['groups'] as &$child) {
                $childTotal = self::sumTree($child);
                foreach ($total as $key => $value) {
                    $total[$key] += $childTotal[$key];
                }
            }
        }

        if (!isset($node['sum'])) {
            $node['sum'] = $total;
        } else {
            foreach ($total as $key => $value) {
                $node['sum'][$key] += $value;
            }
        }

        return $total;
    }

    public static function calculateTotals($data)
    {
        $total = [
            'opening_dr' => 0,
            'opening_cr' => 0,
            'dr' => 0,
            'cr' => 0,
            'closing_dr' => 0,
            'closing_cr' => 0
        ];

        foreach ($data as $key => $level1) {
            $total['opening_dr'] += $level1['sum']['opening_dr'];
            $total['opening_cr'] += $level1['sum']['opening_cr'];
            $total['dr'] += $level1['sum']['dr'];
            $total['cr'] += $level1['sum']['cr'];
            $total['closing_dr'] += $level1['sum']['closing_dr'];
            $total['closing_cr'] += $level1['sum']['closing_cr'];
        }

        return $total;

    }

    public static function generateHtml(array $data, $start_date, $end_date, $view_as = 0, $level = 1): string
    {
        $total = [
            'opening_dr' => 0,
            'opening_cr' => 0,
            'dr' => 0,
            'cr' => 0,
            'closing_dr' => 0,
            'closing_cr' => 0
        ];

        foreach ($data as $key => $level1) {
            if ($level1['level'] == $level) {
                $total['opening_dr'] += $level1['sum']['opening_dr'];
                $total['opening_cr'] += $level1['sum']['opening_cr'];
                $total['dr'] += $level1['sum']['dr'];
                $total['cr'] += $level1['sum']['cr'];
                $total['closing_dr'] += $level1['sum']['closing_dr'];
                $total['closing_cr'] += $level1['sum']['closing_cr'];
            }
        }

        $html = '';
        $html .= self::generateRows($data, $start_date, $end_date, $view_as);

        $html .= '<tfoot>';
        $html .= '<tr>';
        $html .= '<td>Total</td>';
        $html .= '<td>' . number_format($total['opening_dr'] ?? 0) . '</td>';
        $html .= '<td>' . number_format($total['opening_cr'] ?? 0) . '</td>';
        $html .= '<td>' . number_format($total['dr'] ?? 0) . '</td>';
        $html .= '<td>' . number_format($total['cr'] ?? 0) . '</td>';
        $html .= '<td>' . number_format($total['closing_dr'] ?? 0) . '</td>';
        $html .= '<td>' . number_format($total['closing_cr'] ?? 0) . '</td>';
        $html .= '</tr>';
        $html .= '</tfoot>';

        return $html;
    }

    private static function generateRows(array $data, $start_date, $end_date, $view_as): string
    {
        $rows = '';
        foreach ($data as $item) {

            if (isset($item['level'])) {
                if ($view_as == 1) {

                    if ($item['level'] == 1) {
                        $rows .= '<tr style="background:#d3ffd3;font-weight:700;">';
                        $rows .= '<td>' . $item['number'] . ' - ' . $item['name'] . '</td>';
                        if (isset($item['sum']) && is_array($item['sum'])) {
                            $rows .= '<td>' . number_format($item['sum']['opening_dr'] ?? 0) . '</td>';
                            $rows .= '<td>' . number_format($item['sum']['opening_cr'] ?? 0) . '</td>';
                            $rows .= '<td>' . number_format($item['sum']['dr'] ?? 0) . '</td>';
                            $rows .= '<td>' . number_format($item['sum']['cr'] ?? 0) . '</td>';
                            $rows .= '<td>' . number_format($item['sum']['closing_dr'] ?? 0) . '</td>';
                            $rows .= '<td>' . number_format($item['sum']['closing_cr'] ?? 0) . '</td>';
                        } else {
                            $rows .= '<td colspan="7">No Sum Data</td>';
                        }
                        $rows .= '</tr>';
                    }

                    if (isset($item['groups']) && is_array($item['groups'])) {
                        $rows .= self::generateRows($item['groups'], $start_date, $end_date, $view_as);
                    }
                    if (isset($item['ledgers']) && is_array($item['ledgers'])) {
                        $rows .= self::generateRows($item['ledgers'], $start_date, $end_date, $view_as);
                    }
                } else {

                    $rows .= '<tr style="background:#d3ffd3;font-weight:700;">';
                    $rows .= '<td>' . $item['number'] . ' - ' . $item['name'] . '</td>';
                    if (isset($item['sum']) && is_array($item['sum'])) {
                        $rows .= '<td>' . number_format($item['sum']['opening_dr'] ?? 0) . '</td>';
                        $rows .= '<td>' . number_format($item['sum']['opening_cr'] ?? 0) . '</td>';
                        $rows .= '<td>' . number_format($item['sum']['dr'] ?? 0) . '</td>';
                        $rows .= '<td>' . number_format($item['sum']['cr'] ?? 0) . '</td>';
                        $rows .= '<td>' . number_format($item['sum']['closing_dr'] ?? 0) . '</td>';
                        $rows .= '<td>' . number_format($item['sum']['closing_cr'] ?? 0) . '</td>';
                    } else {
                        $rows .= '<td colspan="7">No Sum Data</td>';
                    }
                    $rows .= '</tr>';

                    if (isset($item['groups']) && is_array($item['groups'])) {
                        $rows .= self::generateRows($item['groups'], $start_date, $end_date, $view_as);
                    }
                    if (isset($item['ledgers']) && is_array($item['ledgers'])) {
                        $rows .= self::generateRows($item['ledgers'], $start_date, $end_date, $view_as);
                    }
                }
            } else {

                $rows .= '<tr>';
//                $rows .= '<td><a href="' . route('admin.ledger_report_print_from_trial_balance', [
//                        'id' => $item['id'],
//                        'start_date' => $start_date,
//                        'end_date' => $end_date
//                    ]) . '" target="_blank">' . $item['number'] . ' - ' . $item['name'] . '</a></td>';
                $rows .= '<td>' . $item['number'] . ' - ' . $item['name'] . '</td>';
                if (isset($item['sum']) && is_array($item['sum'])) {
                    $rows .= '<td>' . number_format($item['sum']['opening_dr'] ?? 0) . '</td>';
                    $rows .= '<td>' . number_format($item['sum']['opening_cr'] ?? 0) . '</td>';
                    $rows .= '<td>' . number_format($item['sum']['dr'] ?? 0) . '</td>';
                    $rows .= '<td>' . number_format($item['sum']['cr'] ?? 0) . '</td>';
                    $rows .= '<td>' . number_format($item['sum']['closing_dr'] ?? 0) . '</td>';
                    $rows .= '<td>' . number_format($item['sum']['closing_cr'] ?? 0) . '</td>';
                } else {
                    $rows .= '<td colspan="7">No Sum Data</td>';
                }
                $rows .= '</tr>';
            }
        }
        return $rows;
    }

    public static function generateExcel(array $data, $start_date, $end_date, $company, $branch, $view_as = 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('NETROOTS')
            ->setTitle('Trial Balance Report')
            ->setSubject('Trial Balance')
            ->setDescription('Trial Balance report generated in Excel format.');

        $sheet->setCellValue('A1', $company);
        $sheet->setCellValue('A2', $branch);

        $sheet->setCellValue('A3', 'Trial Balance from ' . $start_date . ' to ' . $end_date);

        $sheet->setCellValue('A4', 'Account Name');
        $sheet->setCellValue('B4', 'Op. Dr');
        $sheet->setCellValue('C4', 'Op. Cr');
        $sheet->setCellValue('D4', 'Dr');
        $sheet->setCellValue('E4', 'Cr');
        $sheet->setCellValue('F4', 'Cl. Dr');
        $sheet->setCellValue('G4', 'Cl. Cr');

        $total = [
            'opening_dr' => 0,
            'opening_cr' => 0,
            'dr' => 0,
            'cr' => 0,
            'closing_dr' => 0,
            'closing_cr' => 0,
        ];

        foreach ($data as $level1) {
            if (isset($level1['level']) && $level1['level'] == 1) {
                $total['opening_dr'] += $level1['sum']['opening_dr'];
                $total['opening_cr'] += $level1['sum']['opening_cr'];
                $total['dr'] += $level1['sum']['dr'];
                $total['cr'] += $level1['sum']['cr'];
                $total['closing_dr'] += $level1['sum']['closing_dr'];
                $total['closing_cr'] += $level1['sum']['closing_cr'];
            }
        }

        $startRow = 5;
        $startRow = self::writeExcelRows($sheet, $data, $start_date, $end_date, $startRow, $view_as);

        $sheet->setCellValue('A' . $startRow, 'Total');
        $sheet->setCellValue('B' . $startRow, $total['opening_dr']);
        $sheet->setCellValue('C' . $startRow, $total['opening_cr']);
        $sheet->setCellValue('D' . $startRow, $total['dr']);
        $sheet->setCellValue('E' . $startRow, $total['cr']);
        $sheet->setCellValue('F' . $startRow, $total['closing_dr']);
        $sheet->setCellValue('G' . $startRow, $total['closing_cr']);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="TrialBalanceReport.xlsx"');
        $writer->save('php://output');
        exit;
    }

    private static function writeExcelRows($sheet, array $data, $start_date, $end_date, $row, $view_as)
    {
        foreach ($data as $item) {

            if (isset($item['level'])) {
                if ($view_as == 1) {

                    if ($item['level'] == 1) {

                        $sheet->setCellValue('A' . $row, $item['number'] . ' - ' . $item['name']);
                        $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['argb' => 'FF006600'],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFD3FFD3'],
                            ],
                        ]);

                        if (isset($item['sum']) && is_array($item['sum'])) {
                            $sheet->setCellValue('B' . $row, number_format($item['sum']['opening_dr'] ?? 0));
                            $sheet->setCellValue('C' . $row, number_format($item['sum']['opening_cr'] ?? 0));
                            $sheet->setCellValue('D' . $row, number_format($item['sum']['dr'] ?? 0));
                            $sheet->setCellValue('E' . $row, number_format($item['sum']['cr'] ?? 0));
                            $sheet->setCellValue('F' . $row, number_format($item['sum']['closing_dr'] ?? 0));
                            $sheet->setCellValue('G' . $row, number_format($item['sum']['closing_cr'] ?? 0));
                        } else {
                            $sheet->setCellValue('B' . $row, 'No Sum Data');
                            $sheet->mergeCells("B{$row}:G{$row}");
                        }
                        $row++;
                    }

                    if (isset($item['groups']) && is_array($item['groups'])) {
                        $row = self::writeExcelRows($sheet, $item['groups'], $start_date, $end_date, $row, $view_as);
                    }
                    if (isset($item['ledgers']) && is_array($item['ledgers'])) {
                        $row = self::writeExcelRows($sheet, $item['ledgers'], $start_date, $end_date, $row, $view_as);
                    }
                } else {

                    $sheet->setCellValue('A' . $row, $item['number'] . ' - ' . $item['name']);
                    $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['argb' => 'FF006600'],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFD3FFD3'],
                        ],
                    ]);

                    if (isset($item['sum']) && is_array($item['sum'])) {
                        $sheet->setCellValue('B' . $row, number_format($item['sum']['opening_dr'] ?? 0));
                        $sheet->setCellValue('C' . $row, number_format($item['sum']['opening_cr'] ?? 0));
                        $sheet->setCellValue('D' . $row, number_format($item['sum']['dr'] ?? 0));
                        $sheet->setCellValue('E' . $row, number_format($item['sum']['cr'] ?? 0));
                        $sheet->setCellValue('F' . $row, number_format($item['sum']['closing_dr'] ?? 0));
                        $sheet->setCellValue('G' . $row, number_format($item['sum']['closing_cr'] ?? 0));
                    } else {
                        $sheet->setCellValue('B' . $row, 'No Sum Data');
                        $sheet->mergeCells("B{$row}:G{$row}");
                    }
                    $row++;

                    if (isset($item['groups']) && is_array($item['groups'])) {
                        $row = self::writeExcelRows($sheet, $item['groups'], $start_date, $end_date, $row, $view_as);
                    }
                    if (isset($item['ledgers']) && is_array($item['ledgers'])) {
                        $row = self::writeExcelRows($sheet, $item['ledgers'], $start_date, $end_date, $row, $view_as);
                    }
                }
            } else {

                $sheet->setCellValue('A' . $row, $item['number'] . ' - ' . $item['name']);

                if (isset($item['sum']) && is_array($item['sum'])) {
                    $sheet->setCellValue('B' . $row, number_format($item['sum']['opening_dr'] ?? 0));
                    $sheet->setCellValue('C' . $row, number_format($item['sum']['opening_cr'] ?? 0));
                    $sheet->setCellValue('D' . $row, number_format($item['sum']['dr'] ?? 0));
                    $sheet->setCellValue('E' . $row, number_format($item['sum']['cr'] ?? 0));
                    $sheet->setCellValue('F' . $row, number_format($item['sum']['closing_dr'] ?? 0));
                    $sheet->setCellValue('G' . $row, number_format($item['sum']['closing_cr'] ?? 0));
                } else {
                    $sheet->setCellValue('B' . $row, 'No Sum Data');
                    $sheet->mergeCells("B{$row}:G{$row}");
                }
                $row++;
            }
        }
        return $row;
    }

}
