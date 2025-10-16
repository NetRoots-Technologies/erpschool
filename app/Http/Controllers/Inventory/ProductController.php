<?php

namespace App\Http\Controllers\Inventory;

use App\Models\Item;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventry;
use App\Models\ProductItem;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\HRM\Employees;
use App\Services\LedgerService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Academic\AcademicClass;
use App\Models\Academic\StudentAttendanceData;

class ProductController extends Controller
{
    protected $type = [];
    protected $ledgerService;
    public function __construct(Request $request, LedgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
        $this->type['food'] = 'F';
        $this->type['stationary'] = 'S';
    }

    public function index($type)
    {
        if (!Gate::allows('Products-list')) {
            return abort(503);
        }
        $query = Inventry::query();
        $query = $type == 'food' ? $query->food() : $query->stationary();
        $ingredients = $query->get();
        $branches = Branch::all();
        return view('admin.inventory_management.product.index', compact('ingredients', 'type', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Products-create')) {
            return abort(503);
        }
        // dd($request->all());
        DB::beginTransaction();
        $request->validate([
            'product_name' => 'required|string|max:255',
            'branch_id' => 'required',
            'cost_amount' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'item_id' => 'required|array',
            'quantity' => 'required|array',
            'measuring_unit' => 'required|array',
        ]);

        try {
            $product = Product::firstOrNew(["id" => $request->id]);
            $product->name = $request->product_name;
            $product->branch_id = $request->branch_id;
            $product->type = $this->type[$request->type];
            $product->cost_amount = $request->cost_amount;
            $product->sale_price = $request->sale_price;
            $product->save();

            $product->ProductItems()->delete();
            foreach ($request->item_id as $key => $item) {

                $productItems = new ProductItem();
                $productItems->product_id = $product->id;
                $productItems->inventory_id = $request->item_id[$key];
                $productItems->quantity = $request->quantity[$key];
                $productItems->measuring_unit = $request->measuring_unit[$key];
                $productItems->save();
            }
            if ($request->type == "food") {
                $group_id = config('constants.FixedGroups.Cafe_Inventory_Items');
            } else {
                $group_id = config('constants.FixedGroups.Stationery_Inventory_Items');
            }

            if (!$request->get('id')) {
                $this->ledgerService->createAutoLedgers([$group_id], $request->product_name, 0, Product::class, $product->id);
            }

            DB::commit();
            return response()->json(["success" => true, "message" => 'Data stored successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => true, "message" => $e->getMessage()], 500);
        }
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('Products-list')) {
            return abort(503);
        }
        $query = Product::query()->with(['ProductItems', 'ProductItems.inventoryItems', 'branch']);

        $query = $request->type == 'food' ? $query->food() : $query->stationary();
        $query = $query->latest()->get();

        return response()->json(["success" => true, 'data' => $query]);
    }

    public function destroy(Product $product)
    {
        if (!Gate::allows('Products-delete')) {
            return abort(503);
        }
        $product->delete();
        $product->ProductItems()->delete();
        return response()->json(["success" => true, 'message' => 'Item Deleted Successfully', 'data' => []], 200);
    }

    public function calculate(Product $product)
    {
       
        $maxProductsForItem = PHP_INT_MAX;

        foreach ($product->productItems as $productItem) {
            $inventory = Inventry::where('id', $productItem->inventory_id)
                ->where('branch_id', $product->branch_id)
                ->first();

            if (!$inventory) {
                return response()->json([
                    'error' => "Not Enough items to make a Product"
                ], 404);
            }


            $inventoryQuantity = $inventory->quantity;
            $inputQuantity = $productItem->quantity;

            if ($inputQuantity <= 0) {
                return response()->json([
                    'error' => 'Invalid input quantity'
                ], 400);
            }

            $maxProductsForItem = min($maxProductsForItem, intval($inventoryQuantity / $inputQuantity));
        }

        if ($maxProductsForItem == PHP_INT_MAX) {
            return response()->json([
                'error' => 'No available items in stock'
            ], 404);
        }

        return response()->json([
            'max_products' => $maxProductsForItem,
        ]);
    }


    public function productInventory(Request $request)
    {

       
        // DB::beginTransaction();

        $request->validate([
            'inventoryProductsName' => 'required',
            'inventoryProductsQuantity' => 'required|numeric|min:1|max:' . $request->inventoryProductsMaxQuantity,
            'inventoryProductsMaxQuantity' => 'required|numeric|min:1',
        ]);

        // try {
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(["success" => false, "message" => "Product not found"], 404);
        }

        $branchId = $product->branch_id;
        $productLedger = $this->ledgerService->getLedger(Product::class, $product->id);
        // dd($productLedger);
        $quantityToProduce = $request->inventoryProductsQuantity;
        $itemsLedger = [];

        foreach ($product->ProductItems as $key => $productItem) {
            $inventory = Inventry::where('id', $productItem->inventory_id)
                ->where('branch_id', $branchId)
                ->first();

            if (!$inventory) {
                return response()->json([
                    "success" => false,
                    "message" => "Inventory item {$productItem->inventory_id} not found in branch {$branchId}"
                ], 404);
            }

            $totalRequired = $productItem->quantity * $quantityToProduce;
            // dd($totalRequired  , $inventory->quantity);
            // ✅ Check stock availability
            if ($inventory->quantity < $totalRequired) {
                return response()->json([
                    "success" => false,
                    "message" => "Not enough stock for item: $inventory->name in branch {$branchId}"
                ], 400);
            }

            $inventory->quantity -= $totalRequired;
            $inventory->save();
            $itemsLedger[$key] = $this->ledgerService->getLedger(Item::class, $inventory->item_id);
            $itemsLedger[$key]['cost_price'] = $inventory->unit_price * $quantityToProduce;
        }

        $type = $request->type_product == 'food' ? 'P' : 'SP';
        $inventory = Inventry::firstOrNew([
            "product_id" => $request->product_id,
            "branch_id" => $branchId,
        ]);

        $inventory->name = $request->inventoryProductsName;
        $inventory->product_id = $request->product_id;
        $inventory->cost_price = $product->cost_amount;
        $inventory->sale_price = $product->sale_price;
        $inventory->quantity += $quantityToProduce;
        $inventory->type = $type;
        $inventory->save();

        // $data = [
        //     "amount" => $product->cost_price,
        //     "narration" => "Adding Quantity - Products $product->name",
        //     "branch_id" => $branchId,
        //     "entry_type_id" => 8
        // ];

        // $entry = $this->ledgerService->createEntry($data);

        // $entryData = [
        //     "entry_type_id" => 8,
        //     "entry_id" => $entry->id,
        //     "ledger_id" => $productLedger->id,
        //     "amount" => $product->cost_amount,
        //     "balanceType" => 'd',
        //     "narration" => "Creating Products $product->name",
        // ];
        // $this->ledgerService->createEntryItems($entryData);

        // foreach ($itemsLedger as $itemLedger) {
        //     $entryData = [
        //         "entry_type_id" => 8,
        //         "entry_id" => $entry['id'],
        //         "ledger_id" => $itemLedger['id'],
        //         "amount" => $itemLedger['cost_price'],
        //         "balanceType" => 'c',
        //         "narration" => "Creating Products $product->name",
        //     ];
        //     $this->ledgerService->createEntryItems($entryData);
        // }

        DB::commit();
        return response()->json(['success' => true, 'message' => 'Product added to Inventory']);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json(["success" => false, "message" => $e->getMessage()], 500);
        // }
    }


    public function productCompleted()
    {
        
        $goods = Inventry::where('type', 'p')->get();
        return view('admin.inventory_management.completed_good.index', compact('goods'));
    }


    public function getCompleted()
    {
       
        $query = Inventry::where('type', 'p')->get();

        return response()->json(["success" => true, 'data' => $query]);
    }
}
