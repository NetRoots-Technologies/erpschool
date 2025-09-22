<?php

namespace App\Http\Controllers\Inventory;

use Exception;
use Carbon\Carbon;
use App\Models\Inventry;
use App\Models\Supplier;
use App\Models\Admin\Branch;
use App\Models\SupplierItem;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Account\Ledger;
use App\Models\Admin\Branches;
use App\Services\LedgerService;
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $mappedType = $type == 'food' ? 'F' : 'S';

        $branches = Branches::active()
            ->with([
                'suppliers' => function ($query) use ($mappedType) {
                    $query->where('type', $mappedType);
                },
                'suppliers.items:id,name'
            ])
            ->select(['id', 'name'])
            ->get();

        $delivery_status = config('constants.delivery_status');
        return view('admin.inventory_management.purchase_order.index', compact('branches', 'delivery_status', 'type'));
    }
    public function approval()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.inventory_management.requisition.approval');
    }

    public function view($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $delivery_status = config('constants.delivery_status');
        $payment_status = config('constants.payment_status');
        $payment_methods = config('constants.payment_method');
        $cash_in_hand = config('constants.FixedGroups.cash_in_hands');

        $purchase_order = PurchaseOrder::with(['purchaseOrderItems', 'items', 'branch', 'supplier'])->find($id);

        $currentStatus = $purchase_order->delivery_status;

        $bank_account_ids = BankAccount::where('type', 'MOA')
            ->pluck('id')->toArray();

        $ledgers = Ledger::where('parent_type', BankAccount::class)
            ->whereIn('parent_type_id', $bank_account_ids)
            ->orWhere(function ($query) use ($cash_in_hand) {
                $query->where('group_id', $cash_in_hand)
                    ->orWhereNull('parent_type');
            })
            ->get();

        return view('admin.inventory_management.purchase_order.view', compact('purchase_order', 'delivery_status', 'currentStatus', 'payment_status', 'payment_methods', 'ledgers'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        ]);
        $type = $request->type == 'food' ? 'F' : 'S';
        try {
            $data = PurchaseOrder::firstOrNew(['id' => $request->id]);
            $data->supplier_id = $request->supplier_id;
            $data->branch_id = $request->branch_id;
            $data->order_date = $request->order_date;
            $data->delivery_date = $request->delivery_date;
            $data->total_amount = $request->total_amount;
            $data->delivery_status = $request->delivery_status;
            $data->type = $type;
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


            DB::commit();
            return response()->json(["success" => true, "message" => 'Data stored successfully'], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $ex->getMessage()], 500);
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $purchaseOrder->delete();
        $purchaseOrder->purchaseOrderItems()->delete();
        return response()->json(["success" => true, 'message' => 'Deleted Successfully', 'data' => []], 200);
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $supplier = PurchaseOrder::select([
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
            ]);
        $type = $request->get('type') == "food" ? 'F' : "S";
        $supplier->where('type', $type);


        $supplier = $supplier->latest()->get();

        return response()->json(["success" => true, 'message' => 'Listing', 'data' => $supplier], 200);
    }
    public function changeStatus(PurchaseOrder $purchaseOrder, $status)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        DB::beginTransaction();

        try {
            $this->delivery_status = config('constants.delivery_status');

            $purchaseOrder->delivery_status = $this->delivery_status[$status];
            $purchaseOrder->save();

            if ($this->delivery_status[$status] == 'COMPLETED') {
                foreach ($purchaseOrder->purchaseOrderItems as $p) {
                    $inventry = Inventry::where('item_id', $p->item_id)->where('branch_id', $purchaseOrder->branch_id)->where('measuring_unit', $p->measuring_unit)->first();

                    if (!$inventry) {
                        $inventry = new Inventry();
                        $inventry->cost_price = $p->total_price;
                        $inventry->unit_price = $p->unit_price;
                        $inventry->quantity = $p->quantity;
                    } else {
                        $inventry->cost_price = $inventry->cost_price + $p->total_price;
                        $inventry->unit_price = $inventry->cost_price / ($inventry->quantity + $p->quantity);
                        $inventry->increment('quantity', $p->quantity);
                    }

                    $inventry->name = $p->item->name;
                    $inventry->item_id = $p->item_id;
                    $inventry->branch_id = $purchaseOrder->branch_id;
                    $inventry->measuring_unit = $p->measuring_unit;
                    $inventry->type = $purchaseOrder->type;
                    $inventry->save();
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
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
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        DB::beginTransaction();
        try {
            $paymentLedger = Ledger::find($status);
            $supplierLedger = Ledger::where([
                'branch_id' => $purchaseOrder->branch_id,
                "parent_type_id" => $purchaseOrder->supplier_id,
                "parent_type" => Supplier::class
            ])->first();

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

            DB::commit();
            return response()->json(["success" => true, 'message' => 'Payment Method Updated', 'data' => []], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(["success" => false, "message" => $e->getMessage()], 500);
        }
    }

    public function grn($type)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.inventory_management.purchase_order.grn', compact('type'));
    }
    public function grnDetail($id)
    {
        if (!Gate::allows('Dashboard-list')) {
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
        if (!Gate::allows('Dashboard-list')) {
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

}

