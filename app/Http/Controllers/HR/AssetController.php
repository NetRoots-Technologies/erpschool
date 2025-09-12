<?php

namespace App\Http\Controllers\HR;

use Exception;
use carbon\Carbon;
use App\Helper\Helpers;
use App\Models\HR\Asset;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Models\Admin\Branch;
use App\Models\HR\AssetType;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Models\Account\Ledger;
use App\Models\Admin\Branches;
use App\Services\AssetService;
use App\Models\Admin\Companies;
use App\Services\LedgerService;
use App\Models\Admin\BankAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Admin\Groups;
use App\Services\AssetTypeService;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Validator;
use App\Exports\AssetSampleExport;
use Illuminate\Validation\ValidationException;
use App\Imports\AssetExcelImport;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $AssetService;
    protected $assetTypeService;
    protected $ledgerService;

    public function __construct(AssetService $AssetService, LedgerService $ledgerService, AssetTypeService $assetTypeService)
    {
        $this->AssetService = $AssetService;
        $this->ledgerService = $ledgerService;
        $this->assetTypeService = $assetTypeService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.asset.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $asserts = AssetType::get();
        $companies = Company::get();
        $branches = Branch::get();
        $ledgers = Ledger::where("code", "cash")->orWhere("parent_type", BankAccount::class)->get();

        return view('hr.asset.create', compact('asserts', 'companies', 'branches', 'ledgers'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        // dd($request->all());
        DB::beginTransaction();
        try {

            $asset = $this->AssetService->store($request);

            $data['amount'] = $request->get('amount');
            $data['narration'] = "Asset Added $request->name $asset->code";
            $data['branch_id'] = (int) $request->get('branch_id');
            $data['entry_type_id'] = 1;

            $entry = $this->ledgerService->createEntry($data);

            $data['entry_id'] = $entry->id;
            $data['ledger_id'] = $request->get("credit_ledger");
            $data['balanceType'] = "c";
            $data['parent_id'] = $asset->id;
            $data['parent_type'] = Asset::class;

            $entry = $this->ledgerService->createEntryItems($data);

            $group = Groups::where("parent_type", AssetType::class)
                ->where("parent_type_id", $request->get('asset_type_id'))
                ->first();

            if ($group) {
                $this->ledgerService->createAutoLedgers([$group->id], $request->name . "Ledger", (int) $request->get('branch_id'), Asset::class, $asset->id);
            }

            DB::commit();
            //die;

            return redirect()->route('hr.asset.index')->with('success', 'Asset created');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            //die;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $asset_type = AssetType::get();
        $companies = Company::get();
        $branches = Branch::get();

        $asset = Asset::find($id);

        return view('hr.asset.edit', compact('asset', 'asset_type', 'companies', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            $this->AssetService->update($request, $id);
            return redirect()->route('hr.asset.index')->with('success', 'Asset Updated');
        } catch (\Exception $e) {
            return redirect()->route('hr.asset.index')->with('error', 'An error occurred while updating asset');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->AssetService->destroy($id);
        return redirect()->route('hr.asset.index')->with('success', 'Asset type deleted');
    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->AssetService->getdata();
    }

    public function bulkShow()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('hr.asset.bulk');
    }

    public function bulkSave(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {

            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:xlsx,xls'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid file type. Only Excel files are allowed.');
            }

            $file = $request->file('file');
            $path = $file->storeAs('temp', $file->getClientOriginalName());
            $data = Excel::toArray([], storage_path("app/$path"));

            $col = [];
            $credit_type = ["Bank" => 1, "Cash" => 0];
            $AssertTypes = AssetType::get()->toArray();
            $asserts = [];

            foreach ($AssertTypes as $AssertType) {
                $name = Str::slug($AssertType['name']);
                $asserts[$name] = [
                    "id" => $AssertType['id'],
                    "name" => $AssertType['name'],
                    "depreciation" => $AssertType['depreciation'],
                ];
            }

            foreach ($data as $sheet) {
                foreach ($sheet as $index => $row) {
                    if ($index === 0)
                        continue;

                    $row = array_values(array_filter($row, fn($value) => !is_null($value) && $value !== ''));

                    if (count($row) < 19) {
                        return redirect()->back()->with('error', "Row $index: Not enough columns!");
                    }

                    $asset_type_id = $asserts[Str::slug($row[3] ?? '')]['id'] ?? false;
                    if (!$asset_type_id && isset($row[3])) {
                        $newAssetType = $this->assetTypeService->store([
                            'name' => trim($row[3]),
                            'depreciation' => is_numeric(trim($row[15] ?? '')) ? (float) trim($row[15]) : trim($row[15] ?? '')
                        ]);

                        $name = Str::slug($newAssetType->name);
                        $asserts[$name] = [
                            "id" => $newAssetType->id,
                            "name" => $newAssetType->name,
                            "depreciation" => $newAssetType->depreciation,
                        ];

                        $asset_type_id = $newAssetType->id;
                    }

                    $purchaseDate = isset($row[7])
                        ? (is_numeric($row[7])
                            ? Carbon::instance(Date::excelToDateTimeObject($row[7]))->format('Y-m-d')
                            : Carbon::parse($row[7])->format('Y-m-d'))
                        : null;

                    $endDate = isset($row[11])
                        ? (is_numeric($row[11])
                            ? Carbon::instance(Date::excelToDateTimeObject($row[11]))->format('Y-m-d')
                            : Carbon::parse($row[11])->format('Y-m-d'))
                        : null;

                    $amount = isset($row[13]) ? (float) str_replace(',', '', $row[13]) : 0;
                    $depreciationPercentage = isset($row[15]) ? (float) str_replace('%', '', $row[15]) : 0;

                    $depreciationType = strtolower(trim($row[14] ?? ''));

                    if ($depreciationType === 'no depreciation') {
                        $remaining_value = 0;
                    } elseif ($depreciationType === 'straight line') {
                        $remaining_value = Helpers::calculateSLDepreciation($amount, $purchaseDate, $depreciationPercentage);
                    } else if ($depreciationType === 'declining balance') {
                        $remaining_value = Helpers::calculateDBDepreciation($amount, $purchaseDate, $depreciationPercentage);
                    } else {
                        $remaining_value = 0;
                    }

                    $saleTax = isset($row[16]) ? (float) str_replace('%', '', $row[16]) * 100 : 0;
                    $incomeTax = isset($row[17]) ? (float) str_replace('%', '', $row[17]) * 100 : 0;

                    $col[$index] = [
                        "credit_type" => $credit_type[$row[1] ?? ''] ?? 0,
                        "credit_ledger" => $credit_type[$row[2] ?? ''] ?? 0,
                        "asset_type_id" => $asset_type_id,
                        "name" => trim($row[4] ?? ''),
                        "code" => trim($row[5] ?? ''),
                        "working" => isset($row[6]) && strtolower(trim($row[6])) == "yes",
                        "company_id" => 4,
                        "branch_id" => 5,
                        "depreciation_type" => str_replace(" ", "_", trim($row[14] ?? '')),
                        "purchase_date" => $purchaseDate,
                        "invoice_number" => trim($row[8] ?? ''),
                        "manufacturer" => trim($row[9] ?? ''),
                        "serial_number" => trim($row[10] ?? ''),
                        "end_date" => $endDate,
                        "image" => trim($row[12] ?? ''),
                        "amount" => $amount,
                        "depreciation" => $remaining_value,
                        "sale_tax" => $saleTax,
                        "income_tax" => $incomeTax,
                        "narration" => trim($row[18] ?? ''),
                        "note" => trim($row[19] ?? ''),
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now()
                    ];
                }
            }

            Asset::insert($col);
            return redirect()->back()->with('success', 'Your File was uploaded successfully!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function exportbulkfile()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Excel::download(new AssetSampleExport, 'class_bulk_sample.xlsx');
    }

    public function importBulkFile(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new AssetExcelImport, $request->file('import_file'));

            return back()->with('success', 'Assets imported successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            // Optional: Log details
            Log::error('Excel Import Validation Failed', ['errors' => $failures]);

            // Get first failure message
            $firstError = $failures[0]->errors()[0] ?? 'Import failed due to validation error.';

            return back()->with('error', 'Import Failed: ' . $firstError);
        } catch (\Throwable $e) {
            Log::error('Excel Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Import Failed: ' . $e->getMessage());
        }
    }

}
