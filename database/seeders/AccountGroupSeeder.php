<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accounts\AccountGroup;

class AccountGroupSeeder extends Seeder
{
    public function run()
    {
        $groups = [
            // ASSETS
            [
                'name' => 'ASSETS',
                'code' => '01',
                'type' => 'asset',
                'level' => 1,
                'description' => 'Assets',
                'children' => [
                    [
                        'name' => 'Non-Current Assets',
                        'code' => '01001',
                        'type' => 'asset',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Operating Fixed Assets',
                                'code' => '01001001',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Fixed Assets',
                                        'code' => '010010010001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Accumulated Depreciation',
                                        'code' => '010010010002',
                                        'type' => 'contra-asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Intangible Assets',
                                'code' => '01001002',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Software and Systems',
                                        'code' => '010010020001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Accumulated Amortization',
                                        'code' => '010010020002',
                                        'type' => 'contra-asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Capital Work in Progress',
                                'code' => '01001003',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Capital Work in Progress',
                                        'code' => '010010030002',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Long Term Security Deposits',
                                'code' => '01001004',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Electricity Security Deposit',
                                        'code' => '010010040001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Sngpl Security Deposit',
                                        'code' => '010010040002',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Telephone Security Deposit',
                                        'code' => '010010040003',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Long Term Investment',
                                'code' => '01001005',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Investments',
                                        'code' => '010010050001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'name' => 'Current Assets',
                        'code' => '01002',
                        'type' => 'asset',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Cash in Hand',
                                'code' => '01002001',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Cash in Hand -PKR',
                                        'code' => '010020010001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Cash in Hand-GBP',
                                        'code' => '010020010002',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Cash at Bank',
                                'code' => '01002002',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'MCB - Bank',
                                        'code' => '010020020001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'EWF - Sindh Bank',
                                        'code' => '010020020002',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Short Term Investments',
                                'code' => '01002003',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Term Deposit Receipt - MCB',
                                        'code' => '010020030001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Stock',
                                'code' => '01002004',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'General & Stationery Store',
                                        'code' => '010020040001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Food Store',
                                        'code' => '010020040002',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Stores & Spares',
                                        'code' => '010020040003',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Assets Purchase Store',
                                        'code' => '010020040004',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Trade Debtors',
                                'code' => '01002005',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Students Receivable',
                                        'code' => '010020050001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Advances, Deposits, Prepayments & Other Receivables',
                                'code' => '01002006',
                                'type' => 'asset',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Advances to Staff',
                                        'code' => '010020060001',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Advance Tax Receivable',
                                        'code' => '010020060002',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Other Receivables',
                                        'code' => '010020060003',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Prepaid Expenses',
                                        'code' => '010020060004',
                                        'type' => 'asset',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // Capital, reserves & Surplus
            [
                'name' => 'Capital, reserves & Surplus',
                'code' => '03',
                'type' => 'equity',
                'level' => 1,
                'children' => [
                    [
                        'name' => 'Capital Reserves and Surplus',
                        'code' => '03001',
                        'type' => 'equity',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => "Partner's Capital Account",
                                'code' => '03001001',
                                'type' => 'equity',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Maizan Services (Private) Limited.',
                                        'code' => '030010010001',
                                        'type' => 'equity',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Ibadat Educational Trust',
                                        'code' => '030010010002',
                                        'type' => 'equity',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Retained Earinings',
                                'code' => '03001002',
                                'type' => 'equity',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Retained Earinings',
                                        'code' => '030010020001',
                                        'type' => 'equity',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Specific Reserves',
                                'code' => '03001003',
                                'type' => 'equity',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => "Employee's welfare Fund Reserve",
                                        'code' => '030010030001',
                                        'type' => 'equity',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'General Reserves',
                                'code' => '03001004',
                                'type' => 'equity',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Global - Fine',
                                        'code' => '030010040001',
                                        'type' => 'equity',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // LIABILITIES
            [
                'name' => 'Liabilities',
                'code' => '04',
                'type' => 'liability',
                'level' => 1,
                'children' => [
                    [
                        'name' => 'Non-Current Liabilities',
                        'code' => '04001',
                        'type' => 'liability',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Long Term Payables',
                                'code' => '04001001',
                                'type' => 'liability',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Cornerstone School Canal Garden Campus / IET',
                                        'code' => '040010010001',
                                        'type' => 'liability',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'University Of Lahore Loan',
                                        'code' => '040010010002',
                                        'type' => 'liability',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'University of Lahore UEPF',
                                        'code' => '040010010003',
                                        'type' => 'liability',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Current Liabilities',
                        'code' => '04002',
                        'type' => 'liability',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Trade Creditors',
                                'code' => '04002001',
                                'type' => 'liability',
                                'level' => 3,
                                'children' => [
                                    [
                                        'name' => 'Sundry Creditors',
                                        'code' => '040020010001',
                                        'type' => 'liability',
                                        'level' => 4,
                                    ],
                                    [
                                        'name' => 'Creditors For Services',
                                        'code' => '040020010002',
                                        'type' => 'liability',
                                        'level' => 4,
                                    ],
                                ],
                            ],
                            [
                                'name' => 'Accrued Liabilities',
                                'code' => '04002002',
                                'type' => 'liability',
                                'level' => 3,
                                'children' => [
                                    ['name' => 'Expense Payable Account','code'=>'040020020001','type'=>'liability','level'=>4],
                                    ['name' => 'Salaries Payable Account','code'=>'040020020002','type'=>'liability','level'=>4],
                                    ['name' => 'Rent Payable','code'=>'040020020003','type'=>'liability','level'=>4],
                                    ['name' => 'EOBI - Payable','code'=>'040020020004','type'=>'liability','level'=>4],
                                    ['name' => 'Employee Benefits Payable','code'=>'040020020005','type'=>'liability','level'=>4],
                                    ['name' => 'Social Security Contribution Payable','code'=>'040020020006','type'=>'liability','level'=>4],
                                    ['name' => 'Provident Fund Payable','code'=>'040020020007','type'=>'liability','level'=>4],
                                    ['name' => 'Lease Rental Payable','code'=>'040020020008','type'=>'liability','level'=>4],
                                    ['name' => 'Audit Fee Payable','code'=>'040020020009','type'=>'liability','level'=>4],
                                    ['name' => 'Out of Pocket Expense Payable','code'=>'040020020010','type'=>'liability','level'=>4],
                                    ['name' => 'Retained Salary Payable','code'=>'040020020011','type'=>'liability','level'=>4],
                                    ['name' => 'Final Settlement Payable','code'=>'040020020012','type'=>'liability','level'=>4],
                                    ['name' => 'Other Payable','code'=>'040020020013','type'=>'liability','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Parking Account',
                                'code' => '04002003',
                                'type' => 'liability',
                                'level' => 3,
                                'children' => [
                                    ['name' => 'Parking Account','code'=>'040020030001','type'=>'liability','level'=>4],
                                ],
                            ],
                            [
                                'name' => "Student's Securities / Payable",
                                'code' => '04002004',
                                'type' => 'liability',
                                'level' => 3,
                                'children' => [
                                    ['name' => "Student's Securities Refundable",'code'=>'040020040001','type'=>'liability','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Withholding Tax Payable',
                                'code' => '04002005',
                                'type' => 'liability',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'WHT Payable Supplies','code'=>'040020050001','type'=>'liability','level'=>4],
                                    ['name'=>'WHT Payable Services','code'=>'040020050002','type'=>'liability','level'=>4],
                                    ['name'=>'WHT Payable Rent','code'=>'040020050003','type'=>'liability','level'=>4],
                                    ['name'=>'WHT Payable Salaries','code'=>'040020050004','type'=>'liability','level'=>4],
                                    ['name'=>'WHT Payable Construction Contracts','code'=>'040020050005','type'=>'liability','level'=>4],
                                    ['name'=>'With Holding Tax on Fee','code'=>'040020050006','type'=>'liability','level'=>4],
                                    ['name'=>'SALES TAX - PRA','code'=>'040020050007','type'=>'liability','level'=>4],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // REVENUE
            [
                'name' => 'Revenue',
                'code' => '05',
                'type' => 'revenue',
                'level' => 1,
                'children' => [
                    [
                        'name' => 'Student Fee',
                        'code' => '05001',
                        'type' => 'revenue',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Fees',
                                'code' => '05001001',
                                'type' => 'revenue',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Monthly Fee','code'=>'050010010001','type'=>'revenue','level'=>4],
                                    ['name'=>'Fee Concessions','code'=>'050010010002','type'=>'revenue','level'=>4],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Admission Fee',
                        'code' => '05002',
                        'type' => 'revenue',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Admission fee',
                                'code' => '05002001',
                                'type' => 'revenue',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Registration Fee','code'=>'050020010001','type'=>'revenue','level'=>4],
                                    ['name'=>'Admission Fee','code'=>'050020010002','type'=>'revenue','level'=>4],
                                    ['name'=>'Office Charges','code'=>'050020010003','type'=>'revenue','level'=>4],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Annual Fee',
                        'code' => '05003',
                        'type' => 'revenue',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Annual fee',
                                'code' => '05003001',
                                'type' => 'revenue',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Annual fee','code'=>'050030010001','type'=>'revenue','level'=>4],
                                    ['name'=>'Safety & Wellbeing','code'=>'050030010002','type'=>'revenue','level'=>4],
                                    ['name'=>'Climate control Charges','code'=>'050030010003','type'=>'revenue','level'=>4],
                                    ['name'=>'Summer Pack Charges','code'=>'050030010004','type'=>'revenue','level'=>4],
                                    ['name'=>'Student Card Charges','code'=>'050030010005','type'=>'revenue','level'=>4],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Others',
                        'code' => '05004',
                        'type' => 'revenue',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Other Services',
                                'code' => '05004001',
                                'type' => 'revenue',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Speech Therapy Charges','code'=>'050040010001','type'=>'revenue','level'=>4],
                                    ['name'=>'SEN Assesment Fee','code'=>'050040010002','type'=>'revenue','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Other revenues',
                                'code' => '05004002',
                                'type' => 'revenue',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Prospectus Fee','code'=>'050040020001','type'=>'revenue','level'=>4],
                                    ['name'=>'New Admission Test Charges','code'=>'050040020002','type'=>'revenue','level'=>4],
                                    ['name'=>'Summer Camp Charges','code'=>'050040020003','type'=>'revenue','level'=>4],
                                    ['name'=>'Swimming Club Charges','code'=>'050040020004','type'=>'revenue','level'=>4],
                                    ['name'=>'After School Clubs','code'=>'050040020005','type'=>'revenue','level'=>4],
                                    ['name'=>'After School Care','code'=>'050040020006','type'=>'revenue','level'=>4],
                                    ['name'=>'(Other)','code'=>'050030010006','type'=>'revenue','level'=>4],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Income From Profit Centers',
                        'code' => '05005',
                        'type' => 'revenue',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Income form Other Segments',
                                'code' => '05005001',
                                'type' => 'revenue',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Books Copies','code'=>'050050010001','type'=>'revenue','level'=>4],
                                    ['name'=>'Food Charges','code'=>'050050010002','type'=>'revenue','level'=>4],
                                    ['name'=>'Uniform Income','code'=>'050050010003','type'=>'revenue','level'=>4],
                                    ['name'=>'Transportation Charges','code'=>'050050010004','type'=>'revenue','level'=>4],
                                    ['name'=>'Events, Tours and Students Activities','code'=>'050050010005','type'=>'revenue','level'=>4],
                                    ['name'=>'Training Income','code'=>'050050010006','type'=>'revenue','level'=>4],
                                    ['name'=>'Cafe Sales','code'=>'050050010007','type'=>'revenue','level'=>4],
                                ],
                            ],
                        ],
                    ],
                    [
                        'name' => 'Miscellaneous Income',
                        'code' => '05006',
                        'type' => 'revenue',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Miscellaneous Income',
                                'code' => '05006001',
                                'type' => 'revenue',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Misc Income','code'=>'050060010001','type'=>'revenue','level'=>4],
                                    ['name'=>"TDR's Bank Income",'code'=>'050060010002','type'=>'revenue','level'=>4],
                                    ['name'=>'F&B Staff','code'=>'050060010003','type'=>'revenue','level'=>4],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // EXPENSES
            [
                'name' => 'expense / operational Cost',
                'code' => '07',
                'type' => 'expense',
                'level' => 1,
                'children' => [
                    [
                        'name' => 'Direct Cost',
                        'code' => '07001',
                        'type' => 'expense',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Salaries and Wages',
                                'code' => '07001001',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Salaries Academic','code'=>'070010010001','type'=>'expense','level'=>4],
                                    ['name'=>'Salaries Administration','code'=>'070010010002','type'=>'expense','level'=>4],
                                    ['name'=>'Salaries Visiting Staff','code'=>'070010010003','type'=>'expense','level'=>4],
                                    ['name'=>'Salaries SEN - Academics','code'=>'070010010004','type'=>'expense','level'=>4],
                                    ['name'=>'Salaries SEN - Administration','code'=>'070010010005','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Staff Benefits',
                                'code' => '07001002',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Provident Fund','code'=>'070010020001','type'=>'expense','level'=>4],
                                    ['name'=>'Conveyance Allowance','code'=>'070010020002','type'=>'expense','level'=>4],
                                    ['name'=>'Overtime & Allownces','code'=>'070010020003','type'=>'expense','level'=>4],
                                    ['name'=>'Other Allowances','code'=>'070010020004','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Other Cost Staff',
                                'code' => '07001003',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Social Security','code'=>'070010030001','type'=>'expense','level'=>4],
                                    ['name'=>'EOBI','code'=>'070010030002','type'=>'expense','level'=>4],
                                    ['name'=>'Final Settlement','code'=>'070010030003','type'=>'expense','level'=>4],
                                    ['name'=>'Teacher Training Expenses','code'=>'070010030004','type'=>'expense','level'=>4],
                                    ['name'=>'Employee Welfare Fund','code'=>'070010030005','type'=>'expense','level'=>4],
                                    ['name'=>'Consultants Cost','code'=>'070010030006','type'=>'expense','level'=>4],
                                ],
                            ],
                        ],
                    ],

                    [
                        'name' => 'Direct Cost - Profit Centers',
                        'code' => '07002',
                        'type' => 'expense',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Books Cost',
                                'code' => '07002001',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Books Cost','code'=>'070020010001','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Food Cost',
                                'code' => '07002002',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Food Cost','code'=>'070020020001','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Uniform Cost',
                                'code' => '07002003',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Uniform Cost','code'=>'070020030001','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Transportation Cost',
                                'code' => '07002004',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Fuel Cost','code'=>'070020040001','type'=>'expense','level'=>4],
                                    ['name'=>'Vehicle Running Expenses','code'=>'070020040002','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Events Cost',
                                'code' => '07002005',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Events Cost','code'=>'070020050001','type'=>'expense','level'=>4],
                                ],
                            ],
                        ],
                    ],

                    [
                        'name' => 'Support Activities - Administrative Expenses',
                        'code' => '07003',
                        'type' => 'expense',
                        'level' => 2,
                        'children' => [
                            [
                                'name' => 'Admin Expenses Students',
                                'code' => '07003001',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Pearson Subscription - Curriculum Materials','code'=>'070030010001','type'=>'expense','level'=>4],
                                    ['name'=>'Sports Expenses','code'=>'070030010002','type'=>'expense','level'=>4],
                                    ['name'=>'Events, Functions & Outdoor Activities','code'=>'070030010003','type'=>'expense','level'=>4],
                                    ['name'=>'Laboratory Expenses','code'=>'070030010004','type'=>'expense','level'=>4],
                                    ['name'=>'Stationery & Material Students','code'=>'070030010005','type'=>'expense','level'=>4],
                                    ['name'=>'Medical Expense','code'=>'070030010006','type'=>'expense','level'=>4],
                                    ['name'=>'Photocopy Students','code'=>'070030010007','type'=>'expense','level'=>4],
                                    ['name'=>'DHL - Exam','code'=>'070030010008','type'=>'expense','level'=>4],
                                    ['name'=>'Fee Provisions','code'=>'070030010009','type'=>'expense','level'=>4],
                                    ['name'=>'Bad Debts','code'=>'070030010010','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Admin Expenses Operations',
                                'code' => '07003002',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Travelling Expense General','code'=>'070030020001','type'=>'expense','level'=>4],
                                    ['name'=>'Freight Charges','code'=>'070030020002','type'=>'expense','level'=>4],
                                    ['name'=>'Accommodation Charges','code'=>'070030020003','type'=>'expense','level'=>4],
                                    ['name'=>'Rent Expense','code'=>'070030020004','type'=>'expense','level'=>4],
                                    ['name'=>'Lease Rental','code'=>'070030020005','type'=>'expense','level'=>4],
                                    ['name'=>'Stationery & Material - Admin','code'=>'070030020006','type'=>'expense','level'=>4],
                                    ['name'=>'Advertisement and Promotion Expense','code'=>'070030020007','type'=>'expense','level'=>4],
                                    ['name'=>'Postage Expenses','code'=>'070030020008','type'=>'expense','level'=>4],
                                    ['name'=>'Entertainment Expenses','code'=>'070030020009','type'=>'expense','level'=>4],
                                    ['name'=>'Software Support Charges','code'=>'070030020010','type'=>'expense','level'=>4],
                                    ['name'=>'Property Tax','code'=>'070030020011','type'=>'expense','level'=>4],
                                    ['name'=>'Donation/Charity','code'=>'070030020012','type'=>'expense','level'=>4],
                                    ['name'=>'Horticulture Tools & Expenses','code'=>'070030020013','type'=>'expense','level'=>4],
                                    ['name'=>'Housekeeping Supplies','code'=>'070030020014','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Utility Charges',
                                'code' => '07003003',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Electricity Charges','code'=>'070030030001','type'=>'expense','level'=>4],
                                    ['name'=>'Fuel for Generator','code'=>'070030030002','type'=>'expense','level'=>4],
                                    ['name'=>'Sui Gas','code'=>'070030030003','type'=>'expense','level'=>4],
                                    ['name'=>'Water Bill','code'=>'070030030004','type'=>'expense','level'=>4],
                                    ['name'=>'Drain & Waste Charges','code'=>'070030030005','type'=>'expense','level'=>4],
                                    ['name'=>'Liquified Petroleum Gas ( LPG )','code'=>'070030030006','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Communication',
                                'code' => '07003004',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Telephone & Internet Charges','code'=>'070030040001','type'=>'expense','level'=>4],
                                    ['name'=>'(empty)','code'=>'070030040002','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Repair Maintenance',
                                'code' => '07003005',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Repair Maintenance Building','code'=>'070030050001','type'=>'expense','level'=>4],
                                    ['name'=>'Repair & Maintenance Electrical','code'=>'070030050002','type'=>'expense','level'=>4],
                                    ['name'=>'Repair & Maintenance Generator','code'=>'070030050003','type'=>'expense','level'=>4],
                                    ['name'=>'Repair & Maintenance Computer etc','code'=>'070030050004','type'=>'expense','level'=>4],
                                    ['name'=>'Repair Maintance General','code'=>'070030050005','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Profesional Charges',
                                'code' => '07003006',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>"Auditor's Remuneration",'code'=>'070030060001','type'=>'expense','level'=>4],
                                    ['name'=>'Legal Expense','code'=>'070030060002','type'=>'expense','level'=>4],
                                    ['name'=>'Commission','code'=>'070030060003','type'=>'expense','level'=>4],
                                    ['name'=>'Registration / Renewal','code'=>'070030060004','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Financial Expenses',
                                'code' => '07003007',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Bank Charges','code'=>'070030070001','type'=>'expense','level'=>4],
                                    ['name'=>'Mark Up Charges','code'=>'070030070002','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Depreciation Expense',
                                'code' => '07003008',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Depreciation Building & structure','code'=>'070030080001','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Furniture & Fixture','code'=>'070030080002','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation IT Equipment','code'=>'070030080003','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Electricity Equipment','code'=>'070030080004','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Lab Equipment','code'=>'070030080005','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Library Books','code'=>'070030080006','type'=>'expense','level'=>4],
                                    ['name'=>'Amortization Course Books 5 years','code'=>'070030080007','type'=>'expense','level'=>4],
                                    ['name'=>'Amortization Course Books 3 years','code'=>'070030080008','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Other Assets','code'=>'070030080009','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Vehicle','code'=>'070030080010','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Electric installations','code'=>'070030080011','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Plant & Machinery','code'=>'070030080012','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Office Equipment','code'=>'070030080013','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Security Equipment','code'=>'070030080014','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation kitchen Equipment','code'=>'070030080015','type'=>'expense','level'=>4],
                                    ['name'=>'Depreciation Building Improvements','code'=>'070030080016','type'=>'expense','level'=>4],
                                ],
                            ],
                            [
                                'name' => 'Others Expenses',
                                'code' => '07003009',
                                'type' => 'expense',
                                'level' => 3,
                                'children' => [
                                    ['name'=>'Misc Expensees','code'=>'070030090001','type'=>'expense','level'=>4],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($groups as $groupData) {
            $this->createGroup($groupData);
        }
    }

    /**
     * Create group with children recursively
     */
    private function createGroup($data, $parentId = null)
    {
        $children = $data['children'] ?? [];
        unset($data['children']);

        // use firstOrCreate to avoid duplicates if seeder re-run
        $group = AccountGroup::firstOrCreate(
            ['code' => $data['code']],
            [
                'name' => $data['name'],
                'type' => $data['type'] ?? null,
                'level' => $data['level'] ?? null,
                'description' => $data['description'] ?? null,
                'parent_id' => $parentId,
                'is_active' => $data['is_active'] ?? true,
            ]
        );

        // Update parent_id/name if it existed but didn't have parent (safe idempotent)
        if ($parentId && $group->parent_id !== $parentId) {
            $group->parent_id = $parentId;
            $group->save();
        }

        // Create children recursively
        foreach ($children as $child) {
            $this->createGroup($child, $group->id);
        }

        return $group;
    }
}
