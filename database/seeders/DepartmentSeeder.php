<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Department;
use App\Models\Admin\Branch;
use App\Models\Category;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        // Fetch all category IDs by name (e.g., 'Finance' => 1)
        $categories = Category::pluck('id', 'name');

        // Fetch branch IDs
        $ptchsBranchId = Branch::where('name', 'PTCHS Campus')->value('id');
        $globalBranchId = Branch::where('name', 'Global Campus')->value('id');

        // Helper to make department arrays
        $dept = function ($name, $parentId = null, $branchId = null, $categoryName = null) use ($categories) {
            return [
                'name' => $name,
                'parent_id' => $parentId,
                'branch_id' => $branchId,
                'category_id' => $categories[$categoryName] ?? null,
            ];
        };

        // Top-level
        $board = Department::create($dept('Board of Management'));

        // Executive under Board
        $cssDirector = Department::create($dept('CSS Director', $board->id, null, 'Executive'));

        // Departments under CSS Director
        $centralOffice = Department::create($dept('Central Office', $cssDirector->id, null, 'Central'));
        $educationConsultant = Department::create($dept('Education Consultant', $cssDirector->id, null, 'Consultant'));
        $finance = Department::create($dept('Finance', $cssDirector->id, null, 'Finance'));

        // Campus Heads
        $principalPTCHS = Department::create($dept('Principal (PTCHS Campus)', $cssDirector->id, $ptchsBranchId, 'Academic'));
        $principalGlobal = Department::create($dept('Principal (Global Campus)', $cssDirector->id, $globalBranchId, 'Academic'));

        // Admin + HR (PTCHS)
        $adminPTCHS = Department::create($dept('Administration (PTCHS)', $principalPTCHS->id, $ptchsBranchId, 'Administration'));
        $hrPTCHS = Department::create($dept('HR (PTCHS)', $principalPTCHS->id, $ptchsBranchId, 'HR'));

        // Academic departments (PTCHS)
        Department::insert([
            $dept('Preschool (PG, KG, Prepr)', $principalPTCHS->id, $ptchsBranchId, 'Academic'),
            $dept('Lower & Upper Elementary (Grade 1-4)', $principalPTCHS->id, $ptchsBranchId, 'Academic'),
            $dept('Middle School (Grade 5-7)', $principalPTCHS->id, $ptchsBranchId, 'Academic'),
            $dept('Highschool', $principalPTCHS->id, $ptchsBranchId, 'Academic'),
            $dept('SEN & Speech (All Levels)', $principalPTCHS->id, $ptchsBranchId, 'Academic'),
            $dept('BTEC', $principalPTCHS->id, $ptchsBranchId, 'Academic'),
        ]);

        // Finance sub-units
        Department::insert([
            $dept('Fee recovery', $finance->id, null, 'Finance'),
            $dept('Asset management', $finance->id, null, 'Finance'),
            $dept('Budget', $finance->id, null, 'Finance'),
            $dept('Finance (Operations)', $finance->id, null, 'Finance'),
            $dept('Accounts', $finance->id, null, 'Finance'),
            $dept('PTCHS Campus (Finance Oversight)', $finance->id, $ptchsBranchId, 'Finance'),
            $dept('Global Campus (Finance Oversight)', $finance->id, $globalBranchId, 'Finance'),
            $dept('National Campus (Finance Oversight)', $finance->id, null, 'Finance'),
            $dept('Margala Campus (Finance Oversight)', $finance->id, null, 'Finance'),
        ]);

        // Admin sub-units (PTCHS)
        Department::insert([
            $dept('Maintenance (PTCHS)', $adminPTCHS->id, $ptchsBranchId, 'Administration'),
            $dept('Safety & Security (PTCHS)', $adminPTCHS->id, $ptchsBranchId, 'Administration'),
            $dept('House keeping (PTCHS)', $adminPTCHS->id, $ptchsBranchId, 'Administration'),
            $dept('Supply Chain store (PTCHS)', $adminPTCHS->id, $ptchsBranchId, 'Administration'),
        ]);

        // Global Campus leadership
        $globalHeadmistress = Department::create($dept('Headmistress (Global Campus)', $centralOffice->id, $globalBranchId, 'Academic'));
        $globalVicePrincipal = Department::create($dept('Vice Principal (Global Campus)', $centralOffice->id, $globalBranchId, 'Academic'));
        $adminGlobal = Department::create($dept('Administration (Global)', $centralOffice->id, $globalBranchId, 'Administration'));
        $hrGlobal = Department::create($dept('HR (Global)', $centralOffice->id, $globalBranchId, 'HR'));

        // Academic (Global)
        Department::insert([
            $dept('Preschool (Global PG, KG, Prepr)', $globalHeadmistress->id, $globalBranchId, 'Academic'),
            $dept('Lower & Upper Elementary (Global Grade 1-4)', $globalHeadmistress->id, $globalBranchId, 'Academic'),
        ]);

        // Admin sub-units (Global)
        Department::insert([
            $dept('Maintenance (Global)', $adminGlobal->id, $globalBranchId, 'Administration'),
            $dept('Safety & Security (Global)', $adminGlobal->id, $globalBranchId, 'Administration'),
            $dept('House keeping (Global)', $adminGlobal->id, $globalBranchId, 'Administration'),
            $dept('Transport (Global)', $adminGlobal->id, $globalBranchId, 'Administration'),
            $dept('Supply Chain store (Global)', $adminGlobal->id, $globalBranchId, 'Administration'),
        ]);
    }
}
