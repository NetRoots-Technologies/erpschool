<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use App\Models\EmployeeBenefit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EmployeeBenefitService
{
    /**
     * Summary of createEmployeeBenefit
     * @param integer $employee_id
     * @param float $company_amount
     * @param float $employee_amount
     * @param mixed $type 
     * @return mixed
     */

    public function createEmployeeBenefit($employee_id, $company_amount, $employee_amount, $type = 'EOBI')
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $now = Carbon::now();
            
            $employeeBenefit = EmployeeBenefit::firstOrCreate(
                [
                    'employee_id' => $employee_id,
                    'type' => $type,
                    'year' => $now->year,
                    'month' => $now->month
                ]
            );

            $employeeBenefit->company_amount = $company_amount;
            $employeeBenefit->employee_amount = $employee_amount;

            $employeeBenefit->save();
            return $employeeBenefit;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
