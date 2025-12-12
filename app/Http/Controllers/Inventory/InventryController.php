<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Item;
use App\Models\Product;
use App\Models\Inventry;
use Illuminate\Http\Request;
use App\Models\Accounts\AccountLedger;
use App\Models\PurchaseHistory;
use App\Services\LedgerService;
use App\Models\Student\Students;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use App\Models\Admin\Ledgers;


class InventryController extends Controller
{
    protected $ledgerService;

    public function __construct(LedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
    }
    public function index($type)
    {
        if (!Gate::allows('inventory-list')) {
            return abort(503);
        }
        return view('admin.inventory_management.inventry.index', compact('type'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('inventory-create')) {
            return abort(503);
        }
        // if (!Gate::allows('inventory-store')) {
        //     return response()->json(["success" => false, "message" => 'You Don\'t have permission to perform this action'], 403);
        // }
        DB::beginTransaction();

        $request->validate([
            'sale_price' => 'required|min:1',
            'expiry_date' => 'nullable',
        ]);

        try {
            $inventry = Inventry::firstOrNew(["id" => $request->id]);
            $inventry->sale_price = $request->sale_price;
            $inventry->expiry_date = $request->expiry_date;
            $inventry->save();
            DB::commit();
            return response()->json(["success" => true, "message" => 'Data stored successfully'], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $ex->getMessage()], 500);
        }
    }

    // public function getData(Request $request)
    // {
    //     if (!Gate::allows('inventory-list')) {
    //         return abort(503);
    //     }
    //     $query = Inventry::latest()->with('branch');
    //     // $query = $request->type == 'food' ? $query->food()->orWhere('type', 'p') : $query->stationary()->orWhere('type', 'SP');
    //     $query = $request->type == 'food'
    //     ? $query->food()->orWhereIn('type', ['F', 'P'])
    //     : ($request->type == 'uniform'
    //         ? $query->uniform()->orWhereIn('type', ['U'])
    //         : $query->stationary()->orWhereIn('type', ['S', 'SP']));

    //     return response()->json(["success" => true, 'message' => 'Listing', 'data' => $query->get()], 200);

    // }

    public function getData(Request $request)
    {
        if (!Gate::allows('inventory-list')) {
            return abort(503);
        }

        $query = Inventry::latest()->with('branch');

        if ($request->type == 'food') {
            $query->whereIn('type', ['F', 'P']);
        }
        elseif ($request->type == 'uniform') {
            $query->where('type', 'U');
        }
        elseif ($request->type == 'general') {
            $query->where('type', 'G');
        }
        else { 
            // stationary default
            $query->whereIn('type', ['S', 'SP']);
        }

        return response()->json([
            "success" => true,
            "message" => "Listing",
            "data" => $query->get()
        ], 200);
    }

    public function view($type = 'food')
    {
        if (!Gate::allows('inventory-view')) {
            return abort(503);
        }
        return view('admin.inventory_management.inventry.pos', compact('type'));
    }

    public function listing(Request $request)
    {

        $inventory = Inventry::select([
            'id',
            'name',
            'branch_id',
            'quantity',
            'unit_price',
            'cost_price',
            'sale_price',
            'measuring_unit',
            'type'
        ]);
        if ($request->type == "food") {

            $inventory = $inventory->Canteen();
        }
        if ($request->type == "uniform") {
            // $inventory = $inventory->where('name', 'like', "%uniform%");
        $inventory = $inventory->where('type','sp')->latest()->get();

        }

        
        // $inventory = $inventory->where('type','sp')->latest()->get();
      
        return response()->json(["success" => true, "message" => "Message", "data" => $inventory], 200);

    }

    // public function save(Request $request)
    // {
    
    //     $request->validate([
    //         "name" => 'required|array',
    //         "inventory_id" => 'required|array',
    //         "price" => 'required|array',
    //         "quantity" => 'required|array',
    //         "total" => 'numeric|string',
    //         "discount" => 'nullable|string|between:0,100',
    //         "payment_method" => 'required|in:cash,card,voucher',
    //         "voucher" => 'nullable|string',
    //         "card" => 'nullable|numeric',
    //     ]);
    //     dd($request->all());    

    //     DB::beginTransaction();

    //     try {
    //         $purchaseHistory = new PurchaseHistory();

    //         $purchaseHistory->customer_name = $request->customer_name;
    //         $purchaseHistory->voucher_id = $request->voucher;
    //         $purchaseHistory->card_number = $request->card;
    //         $purchaseHistory->purchase_date = date('Y-m-d');
    //         $purchaseHistory->total_sum = $request->total;
    //         $purchaseHistory->payment_method = $request->payment_method;
    //         $purchaseHistory->total_price = $request->total_price;
    //         $purchaseHistory->status = "completed";
    //         $purchaseHistory->discount_applied = $request->discount;
    //         $purchaseHistory->created_by = auth()->id();

    //         $data = [
    //             "purchase_id" => $purchaseHistory->id,
    //             "total" => $request->total,
    //             "discount" => $request->discount,
    //             "sum" => $request->total_price,
    //             "payment_method" => $request->payment_method,
    //             "voucher" => $request->voucher,
    //             "card" => $request->card,
    //         ];

    //         foreach ($request->inventory_id as $key => $inventory_id) {

    //             $inventory = Inventry::find($inventory_id);
    //             $inventory->quantity = $inventory->quantity - $request->quantity[$key];
    //             $inventory->save();
    //             $data[$inventory->id] = [
    //                 "inventory_id" => $inventory_id,
    //                 "item_name" => $inventory->name,
    //                 "quantity" => $request->quantity[$key],
    //                 "price" => $request->price[$key],
    //             ];

    //         }
    //         $purchaseHistory->item_lists = json_encode($data);
    //         $purchaseHistory->save();

    //         // Maitaining Ledgers for POS
    //         // Inventory items to be Cr by cast price
    //         // Cash in hand to be Dr by Sale Price
    //         // Profit/loss to be  Dr bt the difference1 

    //         //Entry
    //         $data = [
    //             "amount" => $request->total,
    //             "narration" => "Pos Transaction",
    //             "branch_id" => auth()->user()->employee->branch_id ?? 1,
    //             "entry_type_id" => 8
    //         ];

    //         $entry = $this->ledgerService->createEntry($data);

    //         $cashLedger = Ledger::first();
    //         $data = [
    //             "entry_type_id" => 8,
    //             "entry_id" => $entry->id,
    //             "ledger_id" => $cashLedger->id,
    //             "amount" => $request->total,
    //             "balanceType" => 'd',
    //             "narration" => "Pos Transaction on cash ledger",
    //         ];

    //         $this->ledgerService->createEntryItems($data);

    //         foreach ($request->inventory_id as $key => $inventory_id) {
    //             $inventory = Inventry::find($inventory_id);
    //             $id = $inventory->product_id ?? $inventory->item_id;
    //             $model = $inventory->product_id ? Product::class : Item::class;

    //             $itemLedger = $this->ledgerService->getLedger($model, $id);

    //             $data = [
    //                 "entry_type_id" => 8,
    //                 "entry_id" => $entry->id,
    //                 "ledger_id" => $itemLedger->id,
    //                 "amount" => $request->price[$key],
    //                 "balanceType" => 'c',
    //                 "narration" => "Pos Transaction on Inventory $inventory->name",
    //             ];

    //             $this->ledgerService->createEntryItems($data);
    //         }


    //         DB::commit();
    //         return response()->json(["success" => true, "message" => "Order Completed"], 200);
    //     } catch (\Exception $ex) {
    //         DB::rollBack();
    //         return response()->json(["success" => false, "message" => $ex->getMessage(), "data" => ""], 500);
    //     }
    // }

    
    public function save(Request $request)
    {
        
        // dd($request->all());
        $validated = $request->validate([
            'inventory_id'   => ['required','array','min:1'],
            // 'inventory_id.*' => ['required','integer'],
            'name'           => ['required','array','min:1'],
            'name.*'         => ['required','string','max:255'],
            'price'          => ['required','array','min:1'],
            'price.*'        => ['required','numeric','gte:0'],
            'quantity'       => ['required','array','min:1'],
            'quantity.*'     => ['required','integer','gte:1'],

            // Scalars coming from client are ignored for computation but validated for shape
            'total'          => ['nullable','numeric','gte:0'],
            'discount'       => ['nullable','numeric','between:0,100'],
            'payment_method' => ['required', Rule::in(['cash','card','voucher'])],
            'voucher'        => ['nullable','string'],
            'card'           => ['nullable','digits_between:4,19'], // basic PAN length guard
            'customer_name'  => ['nullable','string','max:255'],
        ]);

        // Conditional requirements
        if ($validated['payment_method'] === 'card') {
            $request->validate(['card' => ['required','digits_between:4,19']]);
        }
        if ($validated['payment_method'] === 'voucher') {
            $request->validate(['voucher' => ['required','string']]); // or exists:vouchers,code
        }

        // 2) Recompute totals on server
        $lineCount = count($validated['inventory_id']);
        if (! (count($validated['name']) === $lineCount
            && count($validated['price']) === $lineCount
            && count($validated['quantity']) === $lineCount)) {
            return response()->json([
                'success' => false,
                'message' => 'Line arrays must be the same length.'
            ], 422);
        }

        $lines = [];
        $grossTotal = 0.0;

        for ($i = 0; $i < $lineCount; $i++) {
            $qty   = (int) $validated['quantity'][$i];
            $price = (float) $validated['price'][$i];
            $lineTotal = $qty * $price;

            $lines[] = [
                'inventory_id' => (int)$validated['inventory_id'][$i],
                'item_name'    => (string)$validated['name'][$i],
                'quantity'     => $qty,
                'price'        => $price,
                'line_total'   => $lineTotal,
            ];
            $grossTotal += $lineTotal;
        }

        $discountPct = (float)($validated['discount'] ?? 0);
        $discountAmt = round($grossTotal * ($discountPct / 100), 2);
        $netTotal    = round($grossTotal - $discountAmt, 2);

        // 3) Transaction: save, update stock, create ledger entries
        return DB::transaction(function () use ($request, $validated, $lines, $grossTotal, $discountPct, $discountAmt, $netTotal) {

            // Create purchase history
            $purchaseHistory = new PurchaseHistory();
            $purchaseHistory->customer_name    = $validated['customer_name'] ?? null;
            $purchaseHistory->voucher_id       = $validated['voucher'] ?? null;
            $purchaseHistory->card_number      = $validated['card'] ?? null;
            $purchaseHistory->purchase_date    = now()->toDateString();
            $purchaseHistory->total_sum        = $grossTotal;     // before discount
            $purchaseHistory->total_price      = $netTotal;       // after discount
            $purchaseHistory->payment_method   = $validated['payment_method'];
            $purchaseHistory->status           = 'completed';
            $purchaseHistory->discount_applied = $discountPct;    // store percent; add another column if you want amount too
            $purchaseHistory->created_by       = auth()->id();
            // Pack meta + lines into JSON
            $purchaseHistory->item_lists = [
                'summary' => [
                    'gross_total' => $grossTotal,
                    'discount_pct'=> $discountPct,
                    'discount_amt'=> $discountAmt,
                    'net_total'   => $netTotal,
                    'line_count'  => count($lines),
                ],
                'lines' => $lines,
            ];
            $purchaseHistory->save();
            // dd($purchaseHistory->toArray());

            // Update inventory (guard against negative stock)
            foreach ($lines as $line) {
                $inventory = Inventry::lockForUpdate()->find($line['inventory_id']);
                if (! $inventory) {
                    throw new \Exception("Inventory not found: {$line['inventory_id']}");
                }
                if ($inventory->quantity < $line['quantity']) {
                    throw new \Exception("Insufficient stock for {$inventory->name}. Available: {$inventory->quantity}, requested: {$line['quantity']}");
                }
                $inventory->quantity = $inventory->quantity - $line['quantity'];
                $inventory->save();
            }
            // dd('here');

            // 4) Ledger entries (adjust as per your chart of accounts)
            // Example: Dr Cash/Bank/AR, Cr Sales/Inventory (you had comments about cost vs sale; below keeps your shape)
            $entry = $this->ledgerService->createEntry([
                'amount'       => $netTotal,
                'narration'    => 'POS Transaction',
                'branch_id'    => auth()->user()->employee->branch_id ?? 1,
                'entry_type_id'=> 8,
            ]);
            // dd($entry->toArray());

            // Dr side: Cash (or the method’s ledger)
            $cashLedger = Ledgers::first();
            $this->ledgerService->createEntryItems([
                'entry_type_id' => 8,
                'entry_id'      => $entry->id,
                'ledger_id'     => $cashLedger->id,
                'amount'        => $netTotal,
                'balanceType'   => 'd',
                'narration'     => 'POS received',
            ]);
            // dd('after cash');

        foreach ($lines as $line) {
        $inventory = Inventry::find($line['inventory_id']);

        $id    = $inventory->product_id ?? $inventory->item_id;
        $model = $inventory->product_id ? \App\Models\Product::class : \App\Models\Item::class;

        $itemLedger = $this->ledgerService->getLedger($model, $id);

        // ✅ FIX: if ledger doesn't exist, create one
        if (!$itemLedger) {
            $itemLedger = $this->ledgerService->createLedger([
                'model_type' => $model,
                'model_id'   => $id,
                'name'       => $inventory->name,
            ]);
        }

        $this->ledgerService->createEntryItems([
            'entry_type_id' => 8,
            'entry_id'      => $entry->id,
            'ledger_id'     => $itemLedger->id,
            'amount'        => $line['line_total'],
            'balanceType'   => 'c',
            'narration'     => "POS sale of {$inventory->name}",
        ]);
    }

            // dd('after items');
            return response()->json([
                'success' => true,
                'message' => 'Order Completed',
                'data'    => [
                    'purchase_id' => $purchaseHistory->id,
                    'gross_total' => $grossTotal,
                    'discount_pct'=> $discountPct,
                    'discount_amt'=> $discountAmt,
                    'net_total'   => $netTotal,
                ],
            ], 200);
        });
    }

    public function purchaseHistory()
    {
       
        $purchaseHistory = PurchaseHistory::latest()->get();
        return view('admin.inventory_management.inventry.purchase_history', compact('purchaseHistory'));
    }
}

