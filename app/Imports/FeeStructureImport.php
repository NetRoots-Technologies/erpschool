<?php

namespace App\Imports;

use App\Models\Fee\FeeFactor;
use App\Models\Fee\FeeCategory;
use App\Models\Fee\FeeDiscount;
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
            // if (count($row) > 10) {
            //     throw new \Exception("Excel file headers should not be more than 10 columns!");
            // }
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
        }
        
        // else{
        //     $studentNmae = Students::whereRaw("CONCAT(TRIM(first_name),' ',TRIM(last_name)) = ?", [trim($row['student_name'])])
        //                         ->where('student_id' , $student->student_id)
        //                         ->first();
        //     if(!$studentNmae){
        //     throw new \Exception("Student record not found: {$row['student_id']} {$row['student_name']}");
        //     }else{
        //         $studentWithClass = Students::where('student_id', $row['student_id'])
        //                     ->where('class_id' , $class_id)
        //                     ->first();
        //         if(!$studentWithClass){
        //             throw new \Exception("Student record not found with the given class: {$row['student_id']} {$row['student_name']} {$class_id} ");
        //         }
        //     }
        // }
        
      
        $student_id = $student->id;


        [$cats, $amts] = $this->parsePipeTriplet(
            $row['category'],
            $row['amount']
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
            ];
        }



        // New Logic For Fee Structure with discount
        
        $discount = FeeDiscount::where('student_id',  $student_id)->first();
       
                $tuitionFeeCategoryWithDiscount = [];
                $roboticsFeeCategoryWithAmount = [];
                if ($discount) {
                    foreach ($detailItems as $category) {
                        $categories = FeeCategory::where('is_active', 1)->where('id', $category['fee_category_id'])->first();
                       
                        if ($categories['name'] == "Tuition Fee"  || $categories['name'] == "Tuition fee") {
                            
                            if ($discount->discount_type == "percentage") {
                                $tuitionFeeCategoryWithDiscount = $category['amount'] - ($category['amount'] * $discount->discount_value / 100);
                               
                            } else {
                                $tuitionFeeCategoryWithDiscount = $category['amount'] - $discount->discount_value;
                            }
                        }

                        // if ($categories['name'] == 'Robotics Charges' || $categories['name'] == "Robotics charges") {
                        //     $roboticsFeeCategoryWithAmount = $category['amount'];
                        // }
                    }
                }else{
                    foreach ($detailItems as $category) {
                        $categories = FeeCategory::where('is_active', 1)->where('id', $category['fee_category_id'])->first();
                        if ($categories->name == "Tuition Fee" || $categories->name == "Tuition fee") {
                                $tuitionFeeCategoryWithDiscount = $category['amount'];
                            }
                        
                        // if ($categories->name == 'Robotics Charges' || $categories->name == "Robotics charges") {
                        //     $roboticsFeeCategoryWithAmount = $category['amount'];
                        // }
                    }
                }

                
                    $tuitionFeeCategoryWithFeeFector = 0;
                if ($tuitionFeeCategoryWithDiscount) {
                    $factor = FeeFactor::whereRaw('LOWER(name) = ?', [$feeFactorNameKey])->first();
                    if ($factor->factor_value == 1.0) {
                        $tuitionFeeCategoryWithFeeFector = $tuitionFeeCategoryWithDiscount / 12;
                    } elseif ($factor->factor_value == 1.2) {
                        $tuitionFeeCategoryWithFeeFector = $tuitionFeeCategoryWithDiscount / 10;
                    } elseif ($factor->factor_value == 2.0) {
                        $tuitionFeeCategoryWithFeeFector = $tuitionFeeCategoryWithDiscount / 6;
                    }
                }
                
                // $roboticsFeeCategoryWithFeeFector = 0;

                // if ($roboticsFeeCategoryWithAmount) {
                //     $factor = FeeFactor::whereRaw('LOWER(name) = ?', [$feeFactorNameKey])->first();;
                //     if ($factor->factor_value == 1.0) {
                //         $roboticsFeeCategoryWithFeeFector  = $roboticsFeeCategoryWithAmount / 12;
                //     } elseif ($factor->factor_value == 1.2) {
                //         $roboticsFeeCategoryWithFeeFector  = $roboticsFeeCategoryWithAmount / 10;
                //     } elseif ($factor->factor_value == 2.0) {
                //         $roboticsFeeCategoryWithFeeFector  = $roboticsFeeCategoryWithAmount / 6;
                //     }
                // }
            $factor = FeeFactor::whereRaw('LOWER(name) = ?', [$feeFactorNameKey])->first();
            $total = 0;
            foreach ($detailItems as $item) {
            $details = FeeStructureDetail::where('fee_structure_id', $factor->id)
                ->where('fee_category_id', $item['fee_category_id'])
                ->get();

            foreach ($details as $detail) {
                $category = FeeCategory::find($detail->fee_category_id);

                if ($category && strtolower($category->name) !== "tuition fee") {
                    $total += $detail->amount;
                }
            }
        }
   
             $finalAmount = $tuitionFeeCategoryWithFeeFector + $total;
            //  dd($finalAmount , $tuitionFeeCategoryWithFeeFector  , $total , $discount , $factor);
            
        // $finalAmount = $months > 0 ? round($total / $months, 2) : $total;


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
                    'notes'            => "Bills for Oct 2025",
                    'company_id'          => auth()->user()->company_id ?? null,
                    'branch_id'           => auth()->user()->branch_id ?? null,
                    'created_by'          => auth()->id(),
                    'updated_by'          => auth()->id(),
                ]);
            }
            return $structure;
        });
    }



    protected function parsePipeTriplet(?string $categories, ?string $amounts): array
{
    // Normalize lookalike pipes to ASCII '|'
    $normalize = function (?string $v): string {
        $v = trim((string) $v);
        return str_replace(
            ["\xE2\x94\x82", "¦", "｜"], // │, ¦, fullwidth |
            "|",
            $v
        );
    };

    /**
     * Split by pipe with an option to keep empty tokens for strict alignment.
     */
    $split = function (?string $v, bool $asNumeric = false, bool $keepEmpty = true) use ($normalize) {
        $v = $normalize($v);
        if ($v === '' || $v === null) return [];

        // split on pipes with optional spaces around them
        $parts = preg_split('/\s*\|\s*/u', $v) ?: [];
        $parts = array_map('trim', $parts);

        if (!$keepEmpty) {
            $parts = array_values(array_filter($parts, fn($x) => $x !== ''));
        }
        // when keeping empties, do NOT filter them out

        if ($asNumeric) {
            $parts = array_map(function ($x) {
                // empty -> 0; remove commas; non-numeric -> 0
                if ($x === '') return 0;
                $x = str_replace(',', '', $x);
                return is_numeric($x) ? $x + 0 : 0;
            }, $parts);
        }

        return $parts;
    };

    // Keep empties to retain positions (STRICT alignment)
    $cats = $split($categories, false, true);
    $amts = $split($amounts,   true,  true);

    $catCount = count($cats);
    $amtCount = count($amts);

    // Align lengths:
    if ($amtCount < $catCount) {
        // pad missing amounts with 0
        $amts = array_pad($amts, $catCount, 0);
    } elseif ($amtCount > $catCount) {
        // truncate extra amounts (safer than throwing)
        $amts = array_slice($amts, 0, $catCount);
        // If you prefer to hard fail instead, replace the slice with an Exception.
        // throw new \Exception("Amounts has extra values: got {$amtCount}, expected {$catCount}.");
    }

    // Optional: normalize category names (collapse internal spaces)
    $cats = array_map(fn($c) => preg_replace('/\s+/u', ' ', $c), $cats);

    return [$cats, $amts];
}

}
