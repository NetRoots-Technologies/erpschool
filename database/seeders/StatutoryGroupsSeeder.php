<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Accounts\AccountGroup;


class StatutoryGroupsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // --- Find parents created by your existing seeder ---
            // You used these codes in your earlier seeder:
            // Liabilities: LIA-000
            // Current Liabilities: LIA-001
            $liabilities = AccountGroup::firstOrCreate(
                ['code' => 'LIA-000'],
                [
                    'name'        => 'Liabilities',
                    'type'        => 'liability',
                    'level'       => 1,
                    'description' => 'Obligations owed to others',
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            $currentLiab = AccountGroup::firstOrCreate(
                ['code' => 'LIA-001'],
                [
                    'name'        => 'Current Liabilities',
                    'type'        => 'liability',
                    'level'       => 2,
                    'parent_id'   => $liabilities->id,
                    'description' => 'Debts payable within a year',
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            // --- Statutory Deductions (parent container) ---
            $statutory = AccountGroup::firstOrCreate(
                ['code' => 'STAT-DED'],
                [
                    'name'        => 'Statutory Deductions',
                    'type'        => 'liability',
                    'level'       => 3,
                    'parent_id'   => $currentLiab->id,
                    'description' => 'PF, EOBI, Social Security etc.',
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            // ---------- EOBI ----------
            $eobiMain = AccountGroup::firstOrCreate(
                ['code' => 'EOBI-MAIN'],
                [
                    'name'        => 'EOBI Contributions',
                    'type'        => 'liability',
                    'level'       => 4,
                    'parent_id'   => $statutory->id,
                    'description' => 'EOBI contributions (employee & company)',
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            $eobiEmp = AccountGroup::firstOrCreate(
                ['code' => 'EOBI-EMP'],
                [
                    'name'        => 'EOBI - Employee Contribution',
                    'type'        => 'liability',
                    'level'       => 5,
                    'parent_id'   => $eobiMain->id,
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            $eobiComp = AccountGroup::firstOrCreate(
                ['code' => 'EOBI-COMP'],
                [
                    'name'        => 'EOBI - Company Contribution',
                    'type'        => 'liability',
                    'level'       => 5,
                    'parent_id'   => $eobiMain->id,
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            // ---------- Provident Fund ----------
            $pfMain = AccountGroup::firstOrCreate(
                ['code' => 'PF-MAIN'],
                [
                    'name'        => 'Provident Fund',
                    'type'        => 'liability',
                    'level'       => 4,
                    'parent_id'   => $statutory->id,
                    'description' => 'PF contributions (employee & company)',
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            $pfEmp = AccountGroup::firstOrCreate(
                ['code' => 'PF-EMP'],
                [
                    'name'        => 'Provident Fund - Employee Contribution',
                    'type'        => 'liability',
                    'level'       => 5,
                    'parent_id'   => $pfMain->id,
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            $pfComp = AccountGroup::firstOrCreate(
                ['code' => 'PF-COMP'],
                [
                    'name'        => 'Provident Fund - Company Contribution',
                    'type'        => 'liability',
                    'level'       => 5,
                    'parent_id'   => $pfMain->id,
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            // ---------- Social Security ----------
            $ssMain = AccountGroup::firstOrCreate(
                ['code' => 'SS-MAIN'],
                [
                    'name'        => 'Social Security',
                    'type'        => 'liability',
                    'level'       => 4,
                    'parent_id'   => $statutory->id,
                    'description' => 'Social Security contributions',
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            $ssEmp = AccountGroup::firstOrCreate(
                ['code' => 'SS-EMP'],
                [
                    'name'        => 'Social Security - Employee Contribution',
                    'type'        => 'liability',
                    'level'       => 5,
                    'parent_id'   => $ssMain->id,
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            $ssComp = AccountGroup::firstOrCreate(
                ['code' => 'SS-COMP'],
                [
                    'name'        => 'Social Security - Company Contribution',
                    'type'        => 'liability',
                    'level'       => 5,
                    'parent_id'   => $ssMain->id,
                    'is_active'   => 1,
                    'created_by'  => 1,
                    'updated_by'  => 1,
                ]
            );

            // ---- Console output: current IDs for your constants mapping ----
            $map = [
                'EOBI' => [$eobiEmp->id, $eobiComp->id],
                'PF'   => [$pfEmp->id,   $pfComp->id],
                'SS'   => [$ssEmp->id,   $ssComp->id],
            ];

            $this->command->info('✅ Statutory groups seeded. Use these IDs in config if needed:');
            foreach ($map as $k => $ids) {
                $this->command->info(sprintf('%s => [%s]', $k, implode(', ', $ids)));
            }
        });
    }
}
