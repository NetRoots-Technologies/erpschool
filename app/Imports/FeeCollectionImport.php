<?php

namespace App\Imports;

use App\Models\Academic\AcademicClass;
use App\Models\Fee\FeeBilling;
use App\Models\Fee\FeeCategory;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
use App\Models\Student\AcademicSession;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class FeeCollectionImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable;

    protected bool $headersChecked = false;

    public function model(array $row)
    {

         $row = collect($row)->map(function ($v) {
            return is_string($v) ? trim($v) : $v;
        })->toArray();


    //    dd($row);

        if (!$this->headersChecked) {
            $this->headersChecked = true;
            if (count($row) > 10) {
                throw new \Exception("Excel file headers should not be more than 10 columns!");
            }
        }

        
        if (empty($row['class'])) {
            throw new \Exception("class row is empty");
        }
        if (empty($row['session'])) {
            throw new \Exception("session row is empty");
        }
        if (empty($row['collection_date'])) {
            throw new \Exception("collection_date row is empty");
        }
        if (empty($row['category'])) {
            throw new \Exception("category row is empty");
        }
        if (empty($row['amount'])) {
            throw new \Exception("amount row is empty");
        }

        if (empty($row['payment_method'])){
            throw new \Exception("payment_method row is empty");
        } 

            

         $class = AcademicClass::where('name', $row['class'])->first();
            if (!$class) {
                throw new \Exception("Class not found: {$row['class']}");
            }
        $class_id = $class->id;


        $sessions = AcademicSession::where('name', $row['session'])->first();
        if (!$sessions) {
            throw new \Exception("session not found: {$row['session']}");
        }
        $session_id = $sessions->id;

         $allowedTypes = ['Cash', 'Bank Transfer', 'Cheque'];

        if (!in_array(($row["payment_method"]), $allowedTypes)) {
            throw new \Exception("Invalid Payment Type: {$row["payment_method"]}. Allowed types: Cash, Bank Transfer, Cheque");
        }


        $student = Students::where('student_id', $row['student_id'])->first();
        if (!$student) {
            throw new \Exception("Student record not found: {$row['student_id']}");
        } else {
            $studentNmae = Students::whereRaw("CONCAT(TRIM(first_name),' ',TRIM(last_name)) = ?", [trim($row['student_name'])])
                ->where('student_id', $student->student_id)
                ->first();
            if (!$studentNmae) {
                throw new \Exception("Student record not found: {$row['student_id']} {$row['student_name']}");
            } else {
                $studentWithClass = Students::where('student_id', $row['student_id'])
                    ->where('class_id', $class_id)
                    ->first();
                if (!$studentWithClass) {
                    throw new \Exception("Student record not found with the given class: {$row['student_id']} {$row['student_name']} {$class_id} ");
                }
            }
        }
        $student_id = $student->id;

        $collection = $row['collection_date'];
        $collection_date = $this->excelDateToCarbon($collection);
        $valid_collection_Date = $collection_date->format('Y-m-d');

        
        // --- parse "A | B" + "100 | 200" ---
        [$cats, $amts] = $this->parsePipePair($row['category'], $row['amount']);
        if (count($cats) !== count($amts)) {
            throw new \Exception("Categories count (".count($cats).") does not match Amounts count (".count($amts).").");
        }

        // --- build details + total ---
        $detailItems = [];
        $totalAmount = 0;
        foreach ($cats as $i => $catName) {
            $cat = FeeCategory::where('is_active', 1)->where('name', $catName)->first();
            if (!$cat) throw new \Exception("FeeCategory not found or inactive: {$catName}");

            $amount = (float) str_replace(',', '', $amts[$i]);
            if ($amount < 0) throw new \Exception("Amount cannot be negative for category: {$catName}");

            $totalAmount += $amount;
            $detailItems[] = [
                'fee_category_id' => $cat->id,
                'amount'          => $amount,
            ];
        }


         $billing = FeeBilling::where('student_id', $student_id)
                ->where('academic_session_id', $session_id)
                ->where('status', '!=', 'paid')
                ->first();

            // If billing exists, use billing's final amount (with discounts applied)
            if ($billing) {
                $finalAmount = $billing->getFinalAmount();
                $totalAmount = min($totalAmount, $finalAmount); // Don't collect more than due
            }

            

       return  DB::transaction(function () use ($row , $student_id, $session_id, $valid_collection_Date, $totalAmount, $detailItems , $billing) {
            $collection = FeeCollection::create([
                'student_id'          => $student_id,
                'academic_session_id' => $session_id,
                'fee_assignment_id'   => 1, 
                'paid_amount'         => $totalAmount,
                'status'              => 'paid',
                'collection_date'     => $valid_collection_Date,
                'payment_method'      => $row['payment_method'],
                'remarks'             => $row['remarks'],
                'company_id'          => auth()->user()->company_id ?? null,
                'branch_id'           => auth()->user()->branch_id ?? null,
                'created_by'          => auth()->id(),
                'updated_by'          => auth()->id(),
            ]);

            foreach ($detailItems as $d) {
                FeeCollectionDetail::create([
                    'fee_collection_id' => $collection->id,
                    'fee_category_id'   => $d['fee_category_id'],
                    'amount'            => $d['amount'],
                    'company_id'        => auth()->user()->company_id ?? null,
                    'branch_id'         => auth()->user()->branch_id ?? null,
                    'created_by'        => auth()->id(),
                    'updated_by'        => auth()->id(),
                ]);
            }

            if ($billing) {
                $billing->paid_amount = ($billing->paid_amount ?? 0) + $totalAmount;
                $billing->outstanding_amount = $billing->getFinalAmount() - $billing->paid_amount;

                if ($billing->outstanding_amount <= 0) {
                    $billing->status = 'paid';
                } else {
                    $billing->status = 'partially_paid';
                }
                $billing->save();
            }

            // ✅ ACCOUNTING INTEGRATION - Record fee collection in accounts
            try {
                $student = Students::find($student_id);
                $integrationController = new \App\Http\Controllers\Accounts\IntegrationController();

                $integrationRequest = new \Illuminate\Http\Request([
                    'student_id' => $collection->student_id,
                    'fee_amount' => $collection->paid_amount,
                    'collection_date' => $collection->collection_date,
                    'reference' => 'FEE-' . str_pad($collection->id, 6, '0', STR_PAD_LEFT) . ' - ' . ($student->fullname ?? 'Student'),
                ]);
                $response = $integrationController->recordAcademicFee($integrationRequest);
                $responseData = $response->getData(true);

            } catch (\Exception $e) {
                throw new \Exception("Fee accounting integration EXCEPTION: " . $e->getMessage());
            }

            return $collection; // ToModel needs a Model
        });

       
        
    }

       protected function excelDateToCarbon($serial)
        {
            return \Carbon\Carbon::createFromTimestamp(($serial - 25569) * 86400);
        }


      protected function parsePipePair(string $categories, string $amounts): array
    {
        $split = function ($v) {
            if ($v === null || trim($v) === '') return [];
            return array_values(array_filter(array_map('trim', preg_split('/\s*\|\s*/', $v))));
        };
        return [$split($categories), $split($amounts)];
    }
}
