<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //        $this->call(DesignationSeeder::class);
        $designationIds = DB::table('designations')->whereNull('deleted_at')->get()->pluck('id')->toArray();


        $printSection = [
            'inital Fee',
            'Yearly Fee',
            'Term/Semester Fee',
            'Optional Fee',
        ];

        $feeStructure = [
            'value' => 12
        ];

        $values = [
            'company' => '650',
            'employee' => '130',
            'total' => '780'
        ];
        $custom = ['percentage' => '%', 'rupees' => 'RS'];

        //$employeeType = ['Part Time', 'Permanent', 'Temporary'];

        $employee_time = [
            'year' => '1',

        ];

        $providentFund = [
            'percentage' => '2.5'
        ];

        $employeeWelfare = [
            'value' => '1.5'
        ];
        $grossValue = [
            'value' => '1.1'
        ];
        $salaryValue = [
            'value' => '1.1'
        ];

        $childBenefit = [
            'first child' => '100%',
            'second child' => '50%',
            'third child' => '0%',
            'fourth child' => '0%',
            'fifth child' => '0%',
        ];
        $socialSecurity = [
            'percentage' => '6%',
            'min-salary' => '32000',
        ];

        $salaryPerYear = [
            'salary' => '3'
        ];

        $settings = [
            [
                'name' => 'Grace Period(For Late Arrival)',
                'key' => 'grace_period',
                'values' => '10',
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'OverTime Amount (RS Per Hour)',
                'key' => 'overtime_price_per_hour',
                'values' => 160,
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Calculate Overtime after How Many Hours',
                'key' => 'hours_to_calculate_overtime_after_for_per_hour',
                'values' => 1,
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Designations For OverTime',
                'key' => 'designations_for_per_hour',
                'values' => json_encode($designationIds),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Designation for Compensatory Leaves',
                'key' => 'designations_for_compensatory_leaves',
                'values' => json_encode($designationIds),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Calculate Overtime after Compensatory Leaves',
                'key' => 'hours_to_calculate_overtime_after_for_compensatory_leaves',
                'values' => 1,
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Compensatory Leaves Increment After How Many Consecutive Overtime Days',
                'key' => 'compensatory_leaves_generate_after_consecutive_days',
                'values' => 3,
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Eobi Values',
                'key' => 'eobi',
                'values' => json_encode($values),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Custom Values',
                'key' => 'custom',
                'values' => json_encode($custom),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Provident Fund',
                'key' => 'providentFund',
                'values' => json_encode($providentFund),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Child Benefit',
                'key' => 'childBenefit',
                'values' => json_encode($childBenefit),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],

            //            [
//                'name' => 'Employee Type',
//                'key' => 'employeeType',
//                'values' => json_encode($employeeType),
//                'extraValues' => null,
//                'status' => 1,
//                'created_at' => now(),
//            ],
            [
                'name' => 'Social Security',
                'key' => 'socialSecurity',
                'values' => json_encode($socialSecurity),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],

            [
                'name' => 'Employee Welfare Fund(Multiplying Factor)',
                'key' => 'employeeWelfare',
                'values' => json_encode($employeeWelfare),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],

            //            [
//                'name' => 'Medical Welfare',
//                'key' => 'medicalWelfare',
//                'values' => json_encode($grossValue),
//                'extraValues' => null,
//                'status' => 1,
//                'created_at' => now(),
//            ],
            [
                'name' => 'Year of employment to apply for Advance',
                'key' => 'employeeTime',
                'values' => json_encode($employee_time),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],

            [
                'name' => 'Dividing Factor for Gross Salary',
                'key' => 'salaryValue',
                'values' => json_encode($salaryValue),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],

            [
                'name' => 'Advance Salary Multiplier for Employees with 3 or More Years of Service',
                'key' => 'advanceSalaryTime',
                'values' => json_encode($salaryPerYear),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Print Section',
                'key' => 'print_section',
                'values' => json_encode($printSection),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'Fee Structure divide factor',
                'key' => 'fee_structure_divide',
                'values' => json_encode($feeStructure),
                'extraValues' => null,
                'status' => 1,
                'created_at' => now(),
            ],


        ];


        DB::table('general_settings')->insert($settings);
    }
}
