<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
            [
                'name' => 'Assets',
            ],
            [
                'name' => 'Liabilities',
                'children' => [],
            ],
            [
                'name' => 'Expense',
                'children' => [
                    ['name' => 'Salary'],
                ],
            ],
            [
                'name' => 'Income',
                'children' => [
                    ['name' => 'Fee'],
                ],
            ],
        ];

        foreach ($groups as $group) {
            $this->createGroup($group, null);
        }
    }

    /**
     * Create group recursively.
     *
     * @param array $group
     * @param int|null $parentId
     * @return void
     */
    private function createGroup(array $group, $parentId = null)
    {
        $levelAndNumber = $this->generateLevelAndNumber($parentId ?? 0, 0);
        $data = [
            'name' => $group['name'],
            'parent_id' => $parentId,
            'level' => $levelAndNumber['level'],
            'number' => $levelAndNumber['number'],
        ];

        $createdGroup = Group::create($data);

        if (isset($group['children']) && is_array($group['children'])) {
            foreach ($group['children'] as $child) {
                $this->createGroup($child, $createdGroup->id);
            }
        }
    }

    /**
     * Generate level and number (stub function for example).
     *
     * Replace this with the actual implementation of generateLevelAndNumber.
     */
    private static function generateLevelAndNumber($parent_id, $adittional_number = 0)
    {

        $ParentGroup = Group::findOrFail($parent_id);
        return [
            'number' => $ParentGroup->number . '-' . sprintf('%0' . (count(explode('-', $ParentGroup->number)) + 1) . 'd', (Group::where(['parent_id' => $ParentGroup->id])->count() + ($adittional_number + 1))),
            'level' => ++$ParentGroup->level,
        ];
    }

}
