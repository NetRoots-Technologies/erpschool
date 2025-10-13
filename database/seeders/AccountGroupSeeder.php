<?php

namespace Database\Seeders;

use App\Models\Accounts\AccountGroup;
use Illuminate\Database\Seeder;

class AccountGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            // ASSETS
            [
                'name' => 'Assets',
                'code' => 'AST-000',
                'type' => 'asset',
                'level' => 1,
                'description' => 'Things owned by the organization',
                'children' => [
                    [
                        'name' => 'Current Assets',
                        'code' => 'AST-001',
                        'type' => 'asset',
                        'level' => 2,
                        'description' => 'Assets that can be converted to cash within a year',
                    ],
                    [
                        'name' => 'Fixed Assets',
                        'code' => 'AST-002',
                        'type' => 'asset',
                        'level' => 2,
                        'description' => 'Long-term tangible assets',
                    ],
                    [
                        'name' => 'Accounts Receivable',
                        'code' => 'AST-003',
                        'type' => 'asset',
                        'level' => 2,
                        'description' => 'Money owed by customers',
                    ],
                ]
            ],
            
            // LIABILITIES
            [
                'name' => 'Liabilities',
                'code' => 'LIA-000',
                'type' => 'liability',
                'level' => 1,
                'description' => 'Obligations owed to others',
                'children' => [
                    [
                        'name' => 'Current Liabilities',
                        'code' => 'LIA-001',
                        'type' => 'liability',
                        'level' => 2,
                        'description' => 'Debts payable within a year',
                    ],
                    [
                        'name' => 'Accounts Payable',
                        'code' => 'LIA-002',
                        'type' => 'liability',
                        'level' => 2,
                        'description' => 'Money owed to vendors/suppliers',
                    ],
                    [
                        'name' => 'Long-term Liabilities',
                        'code' => 'LIA-003',
                        'type' => 'liability',
                        'level' => 2,
                        'description' => 'Debts payable over more than a year',
                    ],
                ]
            ],
            
            // EQUITY
            [
                'name' => 'Equity',
                'code' => 'EQT-000',
                'type' => 'equity',
                'level' => 1,
                'description' => 'Owner\'s stake in the organization',
                'children' => [
                    [
                        'name' => 'Capital',
                        'code' => 'EQT-001',
                        'type' => 'equity',
                        'level' => 2,
                        'description' => 'Owner\'s investment',
                    ],
                    [
                        'name' => 'Retained Earnings',
                        'code' => 'EQT-002',
                        'type' => 'equity',
                        'level' => 2,
                        'description' => 'Accumulated profits',
                    ],
                ]
            ],
            
            // REVENUE
            [
                'name' => 'Revenue',
                'code' => 'REV-000',
                'type' => 'revenue',
                'level' => 1,
                'description' => 'Income earned from operations',
                'children' => [
                    [
                        'name' => 'Fee Revenue',
                        'code' => 'REV-001',
                        'type' => 'revenue',
                        'level' => 2,
                        'description' => 'Student fees and tuition',
                    ],
                    [
                        'name' => 'Transport Revenue',
                        'code' => 'REV-002',
                        'type' => 'revenue',
                        'level' => 2,
                        'description' => 'Transport and van fees',
                    ],
                    [
                        'name' => 'Other Income',
                        'code' => 'REV-003',
                        'type' => 'revenue',
                        'level' => 2,
                        'description' => 'Miscellaneous income',
                    ],
                ]
            ],
            
            // EXPENSES
            [
                'name' => 'Expenses',
                'code' => 'EXP-000',
                'type' => 'expense',
                'level' => 1,
                'description' => 'Costs incurred in operations',
                'children' => [
                    [
                        'name' => 'Salary Expense',
                        'code' => 'EXP-001',
                        'type' => 'expense',
                        'level' => 2,
                        'description' => 'Employee salaries and wages',
                    ],
                    [
                        'name' => 'Rent Expense',
                        'code' => 'EXP-002',
                        'type' => 'expense',
                        'level' => 2,
                        'description' => 'Building and property rent',
                    ],
                    [
                        'name' => 'Utilities Expense',
                        'code' => 'EXP-003',
                        'type' => 'expense',
                        'level' => 2,
                        'description' => 'Electricity, water, gas, etc.',
                    ],
                    [
                        'name' => 'Stationery Expense',
                        'code' => 'EXP-004',
                        'type' => 'expense',
                        'level' => 2,
                        'description' => 'Office supplies and stationery',
                    ],
                    [
                        'name' => 'Transport Expense',
                        'code' => 'EXP-005',
                        'type' => 'expense',
                        'level' => 2,
                        'description' => 'Vehicle fuel and maintenance',
                    ],
                    [
                        'name' => 'Repairs & Maintenance',
                        'code' => 'EXP-006',
                        'type' => 'expense',
                        'level' => 2,
                        'description' => 'Building and equipment maintenance',
                    ],
                ]
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

        $group = AccountGroup::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'type' => $data['type'],
            'level' => $data['level'],
            'description' => $data['description'] ?? null,
            'parent_id' => $parentId,
            'is_active' => true,
        ]);

        // Create children
        foreach ($children as $child) {
            $this->createGroup($child, $group->id);
        }

        return $group;
    }
}

