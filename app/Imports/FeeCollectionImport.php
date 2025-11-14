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
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as XlsDate;


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




        if (!$this->headersChecked) {
            $this->headersChecked = true;
            if (count($row) > 14) {
                throw new \Exception("Excel file headers should not be more than 14 columns!");
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

        if (empty($row['payment_method'])) {
            throw new \Exception("payment_method row is empty");
        }



        //  $class = AcademicClass::where('name', $row['class'])->first();
        //     if (!$class) {
        //         throw new \Exception("Class not found: {$row['class']}");
        //     }
        // $class_id = $class->id;


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

        // $collection = $row['collection_date'];
        // $collection_date = $this->excelDateToCarbon($collection);
        // $valid_collection_Date = $collection_date->format('Y-m-d');

        $raw = $row['collection_date'] ?? null;

        if ($raw instanceof \DateTimeInterface) {
            $dt = Carbon::instance($raw);
        } elseif (is_numeric($raw)) {
            // Excel serial
            if (class_exists(\PhpOffice\PhpSpreadsheet\Shared\Date::class)) {
                $dt = Carbon::instance(XlsDate::excelToDateTimeObject((float)$raw));
            } else {
                // Fallback if PhpSpreadsheet helper not available
                $dt = Carbon::createFromTimestampUTC((int)(((float)$raw - 25569) * 86400));
            }
        } else {
            // String dates: handle m/d/Y and d/m/Y
            $s = trim((string)$raw);
            $s = str_replace(['.', '-'], '/', $s); // normalise separators
            $dt = null;

            foreach (['m/d/Y', 'd/m/Y', 'Y/m/d', 'Y/m/d H:i:s', 'Y-m-d'] as $fmt) {
                try {
                    $dt = Carbon::createFromFormat($fmt, $s);
                    break;
                } catch (\Throwable $e) {
                }
            }
            if (!$dt) { // last resort
                try {
                    $dt = Carbon::parse($s);
                } catch (\Throwable $e) {
                    $dt = null;
                }
            }
        }

        if (!$dt) {
            throw new \Exception('Invalid collection_date: ' . json_encode($raw));
        }

        $valid_collection_Date = $dt->format('Y-m-d');


        // --- parse "A | B" + "100 | 200" ---
        [$cats, $amts] = $this->parsePipePair($row['category'], $row['amount']);
        if (count($cats) !== count($amts)) {
            throw new \Exception("Categories count (" . count($cats) . ") does not match Amounts count (" . count($amts) . ").");
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

        // dd( $totalAmount );
        // $billing = FeeBilling::where('student_id', $student_id)
        // ->where('academic_session_id', $session_id)
        // ->whereDate('billing_month', '=', date('Y-m-01', strtotime($valid_collection_Date)))
        //                     ->first();


        // $challanNumber = $this->generateChallanNumber($student->class_id, $valid_collection_Date);

        // if(empty($billing)){
        //     $billing = FeeBilling::create([
        //         'student_id'          => $student_id,
        //         'academic_session_id' => $session_id,
        //         'challan_number'      => $challanNumber,
        //         'billing_date'        => $valid_collection_Date,
        //         'due_date'            => $valid_collection_Date,
        //         'billing_month'       => $valid_collection_Date,
        //         'company_id'          => auth()->user()->company_id ?? null,
        //         'branch_id'           => auth()->user()->branch_id ?? null,
        //         'created_by'          => auth()->id(),
        //         'updated_by'          => auth()->id(),

        //     ]);

        //     $finalAmount = $billing->getFinalAmount();
        //     $totalAmount = min($totalAmount, $finalAmount);
        // }

        // If billing exists, use billing's final amount (with discounts applied)



        $billMonth = Carbon::parse($valid_collection_Date)->startOfMonth()->format('Y-m');
        
        return  DB::transaction(function () use ($row, $student, $student_id, $session_id, $valid_collection_Date, $totalAmount, $detailItems, $billMonth) {


            $billing = FeeBilling::where('student_id', $student_id)
                        ->where('academic_session_id', $session_id)
                        ->whereDate('billing_month', '=', $billMonth)
                        ->lockForUpdate()
                        ->first();

            $challanNumber = $this->generateChallanNumber($student->class_id, $valid_collection_Date);
            if (!$billing) {
                // Create only if not present for this month
                $billing = FeeBilling::create([
                    'student_id'          => $student_id,
                    'academic_session_id' => $session_id,
                    'challan_number'      => $challanNumber,
                    'bill_date'           => $valid_collection_Date,
                    'due_date'            => $valid_collection_Date,
                    'billing_month'       => $billMonth,   //$billMonth,
                    'company_id'          => auth()->user()->company_id ?? null,
                    'branch_id'           => auth()->user()->branch_id ?? null,
                    'created_by'          => auth()->id(),
                    'updated_by'          => auth()->id(),
                ]);
            }

            $alreadyCollected = FeeCollection::where('student_id', $student_id)
                ->where('academic_session_id', $session_id)
                ->whereDate('collection_date', '=', Carbon::parse($valid_collection_Date)->toDateString())
                ->lockForUpdate()
                ->first();

            if ($alreadyCollected) {
                // Skip creating again; return existing collection
                return $alreadyCollected;
            }

            // $finalAmount   = $billing->getFinalAmount();
            // // $alreadyPaid   = (float) ($billing->paid_amount ?? 0);
            // // $remainingDue  = max(0, $finalAmount - $alreadyPaid);
            // $collectAmount = min((float) $totalAmount, $finalAmount);

            // dd(  $finalAmount  ,  $collectAmount);
            $collection = FeeCollection::create([
                'billing_id'          => $billing->id,
                'student_id'          => $student_id,
                'academic_session_id' => $session_id,
                'fee_assignment_id'   => 1,
                'paid_amount'         => $totalAmount,
                'status'              => $row['remarks'],
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

            $billing->paid_amount        = $totalAmount;
            $billing->total_amount        = $totalAmount;
            $billing->outstanding_amount = 0;
            $billing->status             = $billing->outstanding_amount <= 0 ? 'paid'
                                           : ($billing->paid_amount > 0 ? 'partially_paid' : 'generated');
            $billing->updated_by         = auth()->id();
            $billing->save();

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
        // dd($serial ,\Carbon\Carbon::createFromTimestamp(($serial - 25569) * 86400));
        return \Carbon\Carbon::createFromTimestamp(($serial - 25569) * 86400);
    }


    protected function parsePipePair(?string $categories, ?string $amounts): array
    {
        // Normalize wrong pipe characters
        $normalize = function (?string $v): string {
            $v = trim((string) $v);
            return str_replace(
                ["\xE2\x94\x82", "¦", "｜"], // similar to |
                "|",
                $v
            );
        };

        // Split by pipe and sanitize
        $split = function (?string $v, bool $asNumeric = false) use ($normalize) {
            $v = $normalize($v); // ✅ Now it works
            if ($v === '' || $v === null) {
                return [];
            }

            $parts = preg_split('/\s*\|\s*/u', $v); // split by |
            $parts = array_map('trim', $parts);
            $parts = array_filter($parts, fn($x) => $x !== '');

            if ($asNumeric) {
                $parts = array_map(function ($x) {
                    $x = str_replace(',', '', $x);
                    return is_numeric($x) ? $x + 0 : 0;
                }, $parts);
            }

            return array_values($parts);
        };

        $cats = $split($categories, false);
        $amts = $split($amounts, true);

        // Auto-fix mismatch count
        if (count($cats) !== count($amts)) {
            $amts = array_pad($amts, count($cats), 0);
        }

        return [$cats, $amts];
    }

    private function generateChallanNumber($class, $billingMonth)
    {
        $campusCode = $class->code ?? 'CAMP';
        $year = date('Y', strtotime($billingMonth . '-01'));
        $month = date('m', strtotime($billingMonth . '-01'));

        $prefix = $campusCode . $year . $month;

        $lastBilling = FeeBilling::where('challan_number', 'like', $prefix . '%')
            ->orderBy('challan_number', 'desc')
            ->first();

        if ($lastBilling) {
            $lastNumber = intval(substr($lastBilling->challan_number, -4));
            $billNumber = $lastNumber + 1;
        } else {
            $billNumber = 1;
        }

        return $prefix . str_pad($billNumber, 4, '0', STR_PAD_LEFT);
    }
}
