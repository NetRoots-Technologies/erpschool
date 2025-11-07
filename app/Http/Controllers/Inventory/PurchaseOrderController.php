<?php

namespace App\Http\Controllers\Inventory;

use Exception;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Inventry;
use App\Models\Supplier;
use App\Models\Admin\Branch;
use App\Models\SupplierItem;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Accounts\AccountLedger;
use App\Models\Admin\Branches;
use App\Services\LedgerService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Admin\BankAccount;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Imports\PurchaseOrderImport;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseOrderController extends Controller
{
    protected $delivery_status;
    protected $payment_status;
    protected $payment_method;
    protected $ledgerService;

    public function __construct(LedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
        $this->delivery_status = config('constants.delivery_status');
        $this->payment_status = config('constants.payment_status');
        $this->payment_method = config('constants.payment_method');
    }

    public function index($type)
    {
        // dd($type);
        if (!Gate::allows('PurchaseOrders-list')) {
            return abort(503);
        }
           $mappedType = match ($type) {
                                            'food' => 'F',
                                            'stationary' => 'S',
                                            'uniform' => 'U',
                                            default => 'S',
                                        };
        $branches = Branches::active()
            ->with([
                // 'suppliers' => function ($query) use ($mappedType) {
            //         $query->where('type', $mappedType);
            //     },
            //     'suppliers.items:id,name'
            // ])
            'suppliers' => fn($q) => $q->where('type', $mappedType),
            'suppliers.items:id,name'
        ])
            ->select(['id', 'name'])
            ->get();

        $delivery_status = config('constants.delivery_status');
        return view('admin.inventory_management.purchase_order.index', compact('branches', 'delivery_status', 'type'));
    }
    public function approval()
    {
        
        return view('admin.inventory_management.requisition.approval');
    }

    public function view($id)
    {
        if (!Gate::allows('PurchaseOrders-list')) {
            return abort(503);
        }
        $delivery_status = config('constants.delivery_status');
        $payment_status = config('constants.payment_status');
        $payment_methods = config('constants.payment_method');
        $cash_in_hand = config('constants.FixedGroups.cash_in_hands');

        $purchase_order = PurchaseOrder::with(['purchaseOrderItems', 'items', 'branch', 'supplier'])->find($id);

        $currentStatus = $purchase_order->delivery_status;

        // Using new accounts system - get cash and bank ledgers
        $ledgers = AccountLedger::where('is_active', true)
            ->where(function($query) {
                $query->where('name', 'LIKE', '%Cash%')
                      ->orWhere('name', 'LIKE', '%Bank%');
            })
            ->get();

        return view('admin.inventory_management.purchase_order.view', compact('purchase_order', 'delivery_status', 'currentStatus', 'payment_status', 'payment_methods', 'ledgers'));
    }

    public function store(Request $request)
    {

        // dd($request);
        if (!Gate::allows('PurchaseOrders-create')) {
            return abort(503);
        }
        DB::beginTransaction();
        $request->validate([
            'supplier_id' => 'required|integer|exists:suppliers,id',
            "branch_id" => 'required|integer|exists:branches,id',
            "item_id" => 'required|array|exists:items,id',
            "quantity" => 'required|array',
            "price" => 'required|array',
            "total" => 'required|array',
            "order_date" => 'required|date',
            "delivery_date" => 'required|date',
            "description" => 'nullable|string',
        ]);
        // $type = $request->type == 'food' ? 'F' : 'S';
       $type = match ($request->type) {
            'food' => 'F',
            'stationary' => 'S',
            'uniform' => 'U',
            default => 'S',
        };

        try {
            $data = PurchaseOrder::firstOrNew(['id' => $request->id]);
            $data->supplier_id = $request->supplier_id;
            $data->branch_id = $request->branch_id;
            $data->order_date = $request->order_date;
            $data->delivery_date = $request->delivery_date;
            $data->total_amount = $request->total_amount;
            $data->delivery_status = $request->delivery_status;
            $data->type = $type;
            $data->description = $request->description;
            $data->save();

            $data->purchaseOrderItems()->delete();
            foreach ($request->item_id as $key => $item) {
                $purchase_order_item = new PurchaseOrderItem();
                $purchase_order_item->item_id = $item;
                $purchase_order_item->purchase_order_id = $data->id;
                $purchase_order_item->quantity = $request->get('quantity')[$key];
                $purchase_order_item->unit_price = $request->get('price')[$key];
                $purchase_order_item->total_price = $request->get('total')[$key];
                $purchase_order_item->quote_item_price = $request->get('quoted_price')[$key];
                $purchase_order_item->measuring_unit = $request->get('measuringUnit')[$key];

                $purchase_order_item->save();
            }


            // ✅ INTEGRATE WITH ACCOUNTS SYSTEM
            try {
                $supplier = \App\Models\Supplier::find($request->supplier_id);
                \Illuminate\Support\Facades\Http::post(route('accounts.integration.inventory_purchase'), [
                    'vendor_id' => $data->supplier_id,
                    'purchase_amount' => $data->total_amount,
                    'purchase_date' => $data->order_date,
                    'reference' => 'PO-' . $data->id . ' - ' . ($supplier->name ?? 'Supplier'),
                ]);
            } catch (\Exception $e) {
                \Log::warning('Purchase order accounts integration failed: ' . $e->getMessage());
            }

            DB::commit();
            return response()->json(["success" => true, "message" => 'Data stored successfully'], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $ex->getMessage()], 500);
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if (!Gate::allows('PurchaseOrders-delete')) {
            return abort(503);
        }
        $purchaseOrder->delete();
        $purchaseOrder->purchaseOrderItems()->delete();
        return response()->json(["success" => true, 'message' => 'Deleted Successfully', 'data' => []], 200);
    }


    public function pdf($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'branch', 'purchaseOrderItems.item'])->findOrFail($id);
        // dd($purchaseOrder->purchaseOrderItems);

        $pdf = Pdf::loadView('admin.inventory_management.purchase_order.pdf', compact('purchaseOrder'));

        return $pdf->download('purchase_order_' . $purchaseOrder->id . '.pdf');
    }


    public function print($id)
    {
        $purchaseOrder = PurchaseOrder::with(['supplier', 'branch', 'purchaseOrderItems.item'])->findOrFail($id);

        return view('admin.inventory_management.purchase_order.print', compact('purchaseOrder'));
    }



    public function getData(Request $request)
    {
        if (!Gate::allows('PurchaseOrders-list')) {
            return abort(503);
        }
        $supplier = PurchaseOrder::select([
            "id",
            "supplier_id",
            "branch_id",
            "order_date",
            "delivery_status",
            "delivery_date",
            'description',
        ])
            ->has('supplier')
            ->has('branch')
            ->has('purchaseOrderItems')
            ->with([
                "supplier:id,name",
                "branch:id,name",
                "purchaseOrderItems:id,item_id,purchase_order_id,quantity,unit_price,total_price,quote_item_price,measuring_unit",
                "purchaseOrderItems.item:id,name",
            ]);
         //   $type = $request->get('type') == "food" ? 'F' : "S";
         // normalize + map
            $rawType = $request->get('type');
            $type = in_array($rawType, ['food','stationary','uniform']) ? $rawType : 'stationary';

            // map to DB code
            $mappedType = match ($type) {
                'food' => 'F',
                'stationary' => 'S',
                'uniform' => 'U',
                default => 'S',
            };

            $supplier->where('type', $mappedType);

            $supplier = $supplier->latest()->get();

        return response()->json(["success" => true, 'message' => 'Listing', 'data' => $supplier], 200);
    }
    // public function changeStatus(PurchaseOrder $purchaseOrder, $status)
    // {
    //     if (!Gate::allows('students')) {
    //         return abort(503);
    //     }
    //     DB::beginTransaction();

    //     try {
    //         $this->delivery_status = config('constants.delivery_status');

    //         $purchaseOrder->delivery_status = $this->delivery_status[$status];
    //         $purchaseOrder->save();

    //         if ($this->delivery_status[$status] == 'COMPLETED') {
    //             foreach ($purchaseOrder->purchaseOrderItems as $p) {
    //                 $inventry = Inventry::where('item_id', $p->item_id)->where('branch_id', $purchaseOrder->branch_id)->where('measuring_unit', $p->measuring_unit)->first();

    //                 if (!$inventry) {
    //                     $inventry = new Inventry();
    //                     $inventry->cost_price = $p->total_price;
    //                     $inventry->unit_price = $p->unit_price;
    //                     $inventry->quantity = $p->quantity;
    //                 } else {
    //                     $inventry->cost_price = $inventry->cost_price + $p->total_price;
    //                     $inventry->unit_price = $inventry->cost_price / ($inventry->quantity + $p->quantity);
    //                     $inventry->increment('quantity', $p->quantity);
    //                 }

    //                 $inventry->name = $p->item->name;
    //                 $inventry->item_id = $p->item_id;
    //                 $inventry->branch_id = $purchaseOrder->branch_id;
    //                 $inventry->measuring_unit = $p->measuring_unit;
    //                 $inventry->type = $purchaseOrder->type;
    //                 $inventry->save();
    //             }
    //         }

    //         DB::commit();
    //         return response()->json(["success" => true, 'message' => 'Delivery Status Updated', 'data' => []], 200);
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return response()->json(["success" => false, "message" => $e->getMessage()], 500);
    //     }
    // }

    public function changeStatus(PurchaseOrder $purchaseOrder, $status)
{
    
    DB::beginTransaction();

    try {
        $this->delivery_status = config('constants.delivery_status');

        $purchaseOrder->delivery_status = $this->delivery_status[$status];
        $purchaseOrder->save();

        if ($this->delivery_status[$status] == 'COMPLETED') {
            foreach ($purchaseOrder->purchaseOrderItems as $p) {
                $inventry = Inventry::where('item_id', $p->item_id)
                    ->where('branch_id', $purchaseOrder->branch_id)
                    ->where('measuring_unit', $p->measuring_unit)
                    ->first();

                if (!$inventry) {
                    // 🔹 First time item aa raha hai → direct assign
                    $inventry = new Inventry();
                    $inventry->cost_price = $p->total_price;
                    $inventry->unit_price = $p->unit_price;
                    $inventry->quantity = $p->quantity;
                } else {
                    // 🔹 Old values
                    $oldQuantity = $inventry->quantity;
                    $oldCost     = $inventry->cost_price;

                    // 🔹 New values
                    $newQuantity = $oldQuantity + $p->quantity;
                    $newCost     = $oldCost + $p->total_price;

                    // 🔹 Weighted Average
                    $inventry->quantity   = $newQuantity;
                    $inventry->cost_price = $newCost;
                    $inventry->unit_price = $newCost / $newQuantity;
                }

                // 🔹 Common fields update
                $inventry->name           = $p->item->name;
                $inventry->item_id        = $p->item_id;
                $inventry->branch_id      = $purchaseOrder->branch_id;
                $inventry->measuring_unit = $p->measuring_unit;
                $inventry->type           = $purchaseOrder->type;
                $inventry->save();
            }

            // ✅ ACCOUNTING ENTRY - Create Journal Entry for Purchase
            try {
                // Get or create supplier ledger
                $supplierLedger = AccountLedger::where('linked_module', 'vendor')
                    ->where('linked_id', $purchaseOrder->supplier_id)
                    ->first();
                
                if (!$supplierLedger) {
                    $supplier = Supplier::find($purchaseOrder->supplier_id);
                    $supplierLedger = AccountLedger::create([
                        'name' => 'Supplier - ' . ($supplier->name ?? 'Unknown'),
                        'code' => 'SUP-' . $purchaseOrder->supplier_id . '-' . time(),
                        'description' => 'Supplier payable account',
                        'account_group_id' => 7, // Accounts Payable
                        'opening_balance' => 0,
                        'opening_balance_type' => 'credit',
                        'current_balance' => 0,
                        'current_balance_type' => 'credit',
                        'linked_module' => 'vendor',
                        'linked_id' => $purchaseOrder->supplier_id,
                        'is_active' => true,
                        'created_by' => 1
                    ]);
                    \Log::info("Supplier ledger auto-created for Supplier ID: " . $purchaseOrder->supplier_id);
                }

                // Get or create inventory ledger
                $inventoryLedger = AccountLedger::where('linked_module', 'inventory')->first();
                
                if (!$inventoryLedger) {
                    $inventoryLedger = AccountLedger::where('name', 'LIKE', '%Inventory%')
                        ->whereHas('accountGroup', function($q) {
                            $q->where('type', 'asset');
                        })
                        ->first();
                }
                
                if (!$inventoryLedger) {
                    $inventoryLedger = AccountLedger::create([
                        'name' => 'Inventory',
                        'code' => 'AST-INV-' . time(),
                        'description' => 'Inventory and stock items',
                        'account_group_id' => 2, // Current Assets
                        'opening_balance' => 0,
                        'opening_balance_type' => 'debit',
                        'current_balance' => 0,
                        'current_balance_type' => 'debit',
                        'linked_module' => 'inventory',
                        'is_active' => true,
                        'created_by' => 1
                    ]);
                    \Log::info("Inventory ledger auto-created with code: " . $inventoryLedger->code);
                }

                if ($supplierLedger && $inventoryLedger) {
                    $supplier = Supplier::find($purchaseOrder->supplier_id);
                    
                    // Create journal entry
                    $data = [
                        "amount" => $purchaseOrder->total_amount,
                        "narration" => "Purchase Order Completed - PO #" . $purchaseOrder->id . " from " . ($supplier->name ?? 'Supplier'),
                        "branch_id" => $purchaseOrder->branch_id,
                        "entry_type_id" => 1, // Journal Entry
                    ];

                    $entry = $this->ledgerService->createEntry($data);

                    if ($entry) {
                        // Debit: Inventory (Asset increases)
                        $this->ledgerService->createEntryItems([
                            "entry_type_id" => 1,
                            "entry_id" => $entry->id,
                            "ledger_id" => $inventoryLedger->id,
                            "amount" => $purchaseOrder->total_amount,
                            "balanceType" => 'd', // Debit
                            "narration" => $data["narration"],
                        ]);

                        // Credit: Supplier Payable (Liability increases)
                        $this->ledgerService->createEntryItems([
                            "entry_type_id" => 1,
                            "entry_id" => $entry->id,
                            "ledger_id" => $supplierLedger->id,
                            "amount" => $purchaseOrder->total_amount,
                            "balanceType" => 'c', // Credit
                            "narration" => $data["narration"],
                        ]);

                        \Log::info("Accounting entry created for Purchase Order #" . $purchaseOrder->id);
                    } else {
                        \Log::error("Failed to create journal entry for Purchase Order #" . $purchaseOrder->id);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Purchase order accounting entry failed: ' . $e->getMessage());
                // Don't fail the whole transaction, just log the error
            }
        }

        DB::commit();
        return response()->json(["success" => true, 'message' => 'Delivery Status Updated', 'data' => []], 200);
    } catch (Exception $e) {
        DB::rollBack();
        return response()->json(["success" => false, "message" => $e->getMessage()], 500);
    }
}

    public function changePaymentStatus(PurchaseOrder $purchaseOrder, $status)
    {
        
        DB::beginTransaction();
        try {
            $this->payment_status = config('constants.payment_status');

            $purchaseOrder->payment_status = $this->payment_status[$status];
            $purchaseOrder->save();

            DB::commit();
            return response()->json(["success" => true, 'message' => 'Payment Status Updated', 'data' => []], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $e->getMessage()], 500);
        }

    }


    public function changePaymentMethod(PurchaseOrder $purchaseOrder, $status)
    {

       
        // DB::beginTransaction();
        // Using new accounts system
        try {
            $paymentLedger = AccountLedger::find($status);
            $supplierLedger = AccountLedger::where('linked_module', 'vendor')
                ->where('linked_id', $purchaseOrder->supplier_id)
                ->first();

            $supplier_name = Supplier::where('id',$purchaseOrder->supplier_id)->value('name');

            $data = [
                "amount" => $purchaseOrder->total_amount,
                "narration" => "Paying Supplier $supplier_name",
                "branch_id" => $purchaseOrder->branch_id,
                "entry_type_id" => 8
            ];

            $entry = $this->ledgerService->createEntry($data);

            $createEntryItemsData = [
                "entry_type_id" => 8,
                "entry_id" => $entry->id,
                "ledger_id" => $supplierLedger->id,
                "amount" => $purchaseOrder->total_amount,
                "balanceType" => 'd',
                "narration" => $data["narration"],
            ];
            $this->ledgerService->createEntryItems($createEntryItemsData);

            $createEntryItemsData = [
                "entry_type_id" => 8,
                "entry_id" => $entry->id,
                "ledger_id" => $paymentLedger->id,
                "amount" => $purchaseOrder->total_amount,
                "balanceType" => 'c',
                "narration" => $data["narration"],
            ];
            $this->ledgerService->createEntryItems($createEntryItemsData);

            $this->payment_method = config('constants.payment_method');
            $purchaseOrder->payment_method = $status == 1
                ? $this->payment_method[1]
                : $this->payment_method[2];

            $purchaseOrder->save();

            // DB::commit();
            return response()->json(["success" => true, 'message' => 'Payment Method Updated', 'data' => []], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $e->getMessage()], 500);
        }
    }

    public function grn($type)
    {
        if (!Gate::allows('GRN-list')) {
            return abort(503);
        }

         $mappedType = $type == 'food' ? 'F' : 'S';
        //  dd($mappedType);

        $branches = Branches::active()
            ->with([
                'suppliers' => function ($query) use ($mappedType) {
                    $query->where('type', $mappedType);
                },
                'suppliers.items:id,name'
            ])
            ->select(['id', 'name'])
            ->get();
            // dd($branches);

        $delivery_status = config('constants.delivery_status');
        // dd($delivery_status);
        return view('admin.inventory_management.purchase_order.grn', compact('branches', 'delivery_status', 'type'));
        // return view('admin.inventory_management.purchase_order.grn', compact('type'));
    }
    public function grnDetail($id)
    {
        if (!Gate::allows('GRN-list')) {
            return abort(503);
        }
        $purchaseOrder = PurchaseOrder::where('id', $id)
            ->select([
                "id",
                "supplier_id",
                "branch_id",
                "order_date",
                "delivery_status",
                "delivery_date",
            ])
            ->has('supplier')
            ->has('branch')
            ->has('purchaseOrderItems')
            ->with([
                "supplier:id,name",
                "branch:id,name",
                "purchaseOrderItems:id,item_id,purchase_order_id,quantity,unit_price,total_price,quote_item_price,measuring_unit",
                "purchaseOrderItems.item:id,name",
            ])->first();

        return view('admin.inventory_management.purchase_order.grnDetail', compact('purchaseOrder'));
    }

    public function uploadPurchase(Request $request)
    {
        if (!Gate::allows('PurchaseOrders-edit')) {
            return abort(503);
        }
        try {

            $request->validate([
                'file' => 'required|mimes:xlsx,csv,xls',
            ]);

            Excel::import(new PurchaseOrderImport, $request->file('file'));

            return redirect()->back()->with('success', 'File uploaded and processed successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    public function showData($id){

        $purchaseOrder = PurchaseOrder::where('id', $id)
            ->select([
                "id",
                "supplier_id",
                "branch_id",
                "order_date",
                "delivery_status",
                "delivery_date",
                'description',
            ])
            ->has('supplier')
            ->has('branch')
            ->has('purchaseOrderItems')
            ->with([
                "supplier:id,name",
                "branch:id,name",
                "purchaseOrderItems:id,item_id,purchase_order_id,quantity,unit_price,total_price,quote_item_price,measuring_unit",
                "purchaseOrderItems.item:id,name",
            ])->first();


        return view('admin.inventory_management.purchase_order.show', compact('purchaseOrder'));

    }

}
