<?php

namespace App\Imports;

use App\Models\Fee\FeeDiscount;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Fee\FeeCategory;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use App\Models\Academic\AcademicClass;


class FeeDiscountImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    use Importable;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $headersChecked = false;

    public function model(array $row)
    {
      
        if (!$this->headersChecked) {
            $this->headersChecked = true;

            // if (count($row) > 9) {
            //     throw new \Exception("Excel file headers should not be more than 9 columns!");
            // }
        }

        if (empty($row['student_id'])) {
            throw new \Exception("Student ID row is empty");
        }
        if (empty($row['class'])) {
            throw new \Exception("class row is empty");
        }
        if (empty($row['student_name'])) {
            throw new \Exception("student_name row is empty");
        }
        if (empty($row['fee_category'])) {
            throw new \Exception("fee_category row is empty");
        }
        if (empty($row['discount_type'])) {
            throw new \Exception("discount_type row is empty");
        }

       
        if (empty($row['reason_of_discount'])) {
            throw new \Exception("reason_of_discount row is missing");
        }
        if (empty($row['valid_form_month'])) {
            throw new \Exception("valid_form_month row is missing");
        }
        if (empty($row['valid_to_month'])) {
            throw new \Exception("valid_to_month row is missing");
        }

        $percentage = $row['discount_value'];
        $finalPercentage = is_numeric($percentage) ? $percentage * 100 : floatval($percentage);

        // $class = AcademicClass::where('name', $row['class'])->first();
        // if (!$class) {
        //     throw new \Exception("Class not found: {$row['class']}");
        // }
        // $class_id = $class->id;


        $cat = FeeCategory::where('name', $row['fee_category'])->first();
        if (!$cat) {
            throw new \Exception("Category not found: {$row['academcategoriesic_session']}");
        }
        $cat_id = $cat->id;

        $student = Students::where('student_id', $row['student_id'])->first();
        if (!$student) {
            throw new \Exception("Student record not found: {$row['student_id']}");
        } 
        // else {
        //     $studentNmae = Students::whereRaw("CONCAT(TRIM(first_name),' ',TRIM(last_name)) = ?", [trim($row['student_name'])])
        //         ->where('student_id', $student->student_id)
        //         ->first();
        //     if (!$studentNmae) {
        //         throw new \Exception("Student record not found: {$row['student_id']} {$row['student_name']}");
        //     } else {
        //         $studentWithClass = Students::where('student_id', $row['student_id'])
        //             ->where('class_id', $class_id)
        //             ->first();
        //         if (!$studentWithClass) {
        //             throw new \Exception("Student record not found with the given class: {$row['student_id']} {$row['student_name']} {$class_id} ");
        //         }
        //     }
        // }
        $student_id = $student->id;

        $valid_from_serial = $row['valid_form_month'];
        $valid_to_serial   = $row['valid_to_month'];

        $valid_from_date = $this->excelDateToCarbon($valid_from_serial);
        $valid_to_date   = $this->excelDateToCarbon($valid_to_serial);

        if ($valid_to_date->lt($valid_from_date)) {
            throw new \Exception("Valid To date ({$valid_to_date->format('Y-m-d')}) cannot be earlier than Valid From date ({$valid_from_date->format('Y-m-d')}).");
        }

        $valid_from = $valid_from_date->format('Y-m-d');
        $valid_to   = $valid_to_date->format('Y-m-d');  



        return DB::transaction(function () use ($row, $cat_id, $student_id , $valid_from , $valid_to , $finalPercentage) {
            $Feedis = FeeDiscount::create([
                'category_id'      =>    $cat_id,
                'student_id'       =>    $student_id,
                'discount_type'    =>    $row['discount_type'],
                'discount_value'   =>    $finalPercentage,
                'reason'           =>    $row['reason_of_discount'],
                'show_on_voucher'  =>    0,
                'valid_from'       =>    $valid_from,
                'valid_to'         =>    $valid_to,
                'company_id'       => auth()->user()->company_id ?? null,
                'branch_id'        => auth()->user()->branch_id ?? null,
                'created_by'       => auth()->id(),
                'updated_by'       => auth()->id(),
            ]);

            return $Feedis;
        });
    }

            protected function excelDateToCarbon($serial)
        {
            return \Carbon\Carbon::createFromTimestamp(($serial - 25569) * 86400);
        }
}
