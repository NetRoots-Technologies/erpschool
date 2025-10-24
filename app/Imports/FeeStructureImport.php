<?php

namespace App\Imports;

use App\Models\Fee\FeeFactor;
use App\Models\Fee\FeeCategory;
use App\Models\Fee\FeeStructure;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use App\Models\Academic\AcademicClass;
use App\Models\Fee\FeeStructureDetail;
use App\Models\Student\AcademicSession;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FeeStructureImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    use Importable;

    protected bool $headersChecked = false;

    public function model(array $row)
    {

        $row = collect($row)->map(function ($v) {
            return is_string($v) ? trim($v) : $v;
        })->toArray();


        if (!$this->headersChecked) {
            $this->headersChecked = true;
            if (count($row) > 10) {
                throw new \Exception("Excel file headers should not be more than 10 columns!");
            }
        }

        if (empty($row['structure_name'])) {
            throw new \Exception("structure_name row is empty");
        }
        if (empty($row['class'])) {
            throw new \Exception("class row is empty");
        }
        if (empty($row['academic_session'])) {
            throw new \Exception("academic_session row is empty");
        }
        if (empty($row['fee_factor'])) {
            throw new \Exception("fee_factor row is empty");
        }
        if (empty($row['category'])) {
            throw new \Exception("category row is empty");
        }
        if (empty($row['amount'])) {
            throw new \Exception("amount row is empty");
        }


        $class = AcademicClass::where('name', $row['class'])->first();
        if (!$class) {
            throw new \Exception("Class not found: {$row['class']}");
        }
        $class_id = $class->id;


        $sessions = AcademicSession::where('name', $row['academic_session'])->first();
        if (!$sessions) {
            throw new \Exception("Academic session not found: {$row['academic_session']}");
        }
        $session_id = $sessions->id;


        $allowedTypes = [
            '12 months billing'      => 12,
            '10 months (aug-may)'    => 10,
            '6 months billing'       => 6,
        ];
        $feeFactorNameKey = strtolower($row['fee_factor']);
        if (!array_key_exists($feeFactorNameKey, $allowedTypes)) {
            throw new \Exception("Invalid fee_factor: {$row['fee_factor']}. Allowed: 12 Months Billing, 10 Months (Aug-May), 6 Months Billing");
        }
        $months = $allowedTypes[$feeFactorNameKey];


        $feeFactor = FeeFactor::whereRaw('LOWER(name) = ?', [$feeFactorNameKey])->first();
        if (!$feeFactor) {
            throw new \Exception("FeeFactor record not found: {$row['fee_factor']}");
        }

        if(!$row['student_id']){
             throw new \Exception("Student ID is Empty");
        }
        
        
        $student = Students::where('student_id', $row['student_id'])->first();
        if (!$student) {
            throw new \Exception("Student record not found: {$row['student_id']}");
        }else{
            $studentNmae = Students::whereRaw("CONCAT(TRIM(first_name),' ',TRIM(last_name)) = ?", [trim($row['student_name'])])
                                ->where('student_id' , $student->student_id)
                                ->first();
            if(!$studentNmae){
            throw new \Exception("Student record not found: {$row['student_id']} {$row['student_name']}");
            }else{
                $studentWithClass = Students::where('student_id', $row['student_id'])
                            ->where('class_id' , $class_id)
                            ->first();
                if(!$studentWithClass){
                    throw new \Exception("Student record not found with the given class: {$row['student_id']} {$row['student_name']} {$class_id} ");
                }
            }
        }
        
      
        $student_id = $student->id;


        [$cats, $amts, $notes] = $this->parsePipeTriplet(
            $row['category'],
            $row['amount'],
            $row['notes'] ?? null
        );

        if (count($cats) !== count($amts)) {
            throw new \Exception("Categories count (" . count($cats) . ") does not match Amounts count (" . count($amts) . ").");
        }


        $detailItems = [];
        $total = 0;
        foreach ($cats as $idx => $catName) {
            $cat = FeeCategory::where('is_active', 1)
                ->where('name', $catName)
                ->first();

            if (!$cat) {
                throw new \Exception("FeeCategory not found or inactive: {$catName}");
            }

            $amount = (float) str_replace([','], '', $amts[$idx]);
            if ($amount < 0) {
                throw new \Exception("Amount cannot be negative for category: {$catName}");
            }
            $total += $amount;

            $detailItems[] = [
                'fee_category_id' => $cat->id,
                'amount'          => $amount,
                'notes'           => $notes[$idx] ?? null,
            ];
        }

        $finalAmount = $months > 0 ? round($total / $months, 2) : $total;


        return DB::transaction(function () use ($row, $class_id, $session_id, $feeFactor, $student_id, $finalAmount, $detailItems) {
            $structure = FeeStructure::create([
                'name'                => $row['structure_name'],
                'description'         => $row['description'] ?? null,
                'academic_class_id'   => $class_id,
                'academic_session_id' => $session_id,
                'fee_factor_id'       => $feeFactor->id,
                'student_id'          => $student_id,
                'is_active'           => true,
                'company_id'          => auth()->user()->company_id ?? null,
                'branch_id'           => auth()->user()->branch_id ?? null,
                'created_by'          => auth()->id(),
                'updated_by'          => auth()->id(),
                'final_amount'        => $finalAmount,
            ]);

            foreach ($detailItems as $item) {
                FeeStructureDetail::create([
                    'fee_structure_id' => $structure->id,
                    'fee_category_id'  => $item['fee_category_id'],
                    'amount'           => $item['amount'],
                    'notes'            => $item['notes'],
                    'company_id'          => auth()->user()->company_id ?? null,
                    'branch_id'           => auth()->user()->branch_id ?? null,
                    'created_by'          => auth()->id(),
                    'updated_by'          => auth()->id(),
                ]);
            }
            return $structure;
        });
    }

    /**
     * Parse "A | B | C" style triplet columns.
     *
     * @param string $categories
     * @param string $amounts
     * @param string|null $notes
     * @return array{0: array<int,string>, 1: array<int,string>, 2: array<int,string|null>}
     */
    protected function parsePipeTriplet(string $categories, string $amounts, ?string $notes): array
    {
        $split = function (?string $val): array {
            if ($val === null || trim($val) === '') return [];
            $parts = preg_split('/\s*\|\s*/', $val);
            return array_values(array_filter(array_map('trim', $parts), fn($v) => $v !== ''));
        };

        $cats  = $split($categories);
        $amts  = $split($amounts);
        $nts   = $split($notes ?? '');
        
        if (count($nts) < count($cats)) {
            $nts = array_pad($nts, count($cats), null);
        }

        return [$cats, $amts, $nts];
    }
}
