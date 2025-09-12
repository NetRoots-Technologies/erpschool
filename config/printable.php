<?php

use App\Models\Admin\Branch;
use App\Models\Admin\Company;
use App\Models\Admin\Department;
use App\Models\Financial;
use App\Models\HR\Designation;



return
    [
        'company' => [
            'model' => Company::class,
            'fields' => [
                'name' => 'Name',
            ]
        ],
        'branches' => [
            'model' => Branch::class,
            'fields' => [
                'id' => 'id',
                'company.name' => 'Company',
                'name' => 'Name',
                'ip_config' => 'IP Address'
            ],
            'with' => ['company:id,name'],
            'foreign_keys' => ['company_id'],
        ],
        'departments' => [
            'model' => Department::class,
            'fields' => [
                'id' => 'id',
                'company.name' => 'Company',
                'branch.name' => 'Branch',
                'name' => 'Name'
            ],
            'with' => ['company:id,name', 'branch:id,name'],
            'foreign_keys' => ['company_id', 'branch_id']
        ],
        'financial_years' => [
            'model' => Financial::class,
            'fields' => [
                'name' => 'Name',
                'start_date' => 'Start Date',
                'end_date' => 'End Date'
            ]
        ],
        'designations'=>[
            'model'=>Designation::class,
            'fields'=>[
                'name'=>'Designation',
            ]
        ]
    ];