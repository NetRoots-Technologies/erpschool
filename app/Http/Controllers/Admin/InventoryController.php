<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\inventory\InventoryCategory;
use Illuminate\Http\Request;
use App\Models\Admin\Inventory;
use App\Models\BCategory;
use App\Models\Accounts\AccountLedger;
use App\Models\Group;
use App\Services\InventoryCategoryService;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;

class InventoryController extends Controller
{
    private $baseCode = 1050;

     public function __construct()
    {
        $this->inventoryCategoryService = new InventoryCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

       public function getDataIndex()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Inventory::with('vendorinventoryCategorys')->orderBy('created_at', 'desc')->get();
        // dd($data);
        return response()->json($data);
    }



      public function index(Request $request)
        {
            if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
            if ($request->ajax()) {
                $data = Inventory::with([
                        'accountType:id,name',
                        'detailType:id,name',
                        'category:id,name'
                    ])
                    ->select([
                        'id',
                        'item_name',
                        'inventory_type',
                        'account_type_id',
                        'detail_type_id',
                        'category_id',
                    ])
                    ->orderBy('created_at', 'desc');

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('account_type_id', fn($row) => $row->accountType->name ?? 'N/A')
                    ->addColumn('detail_type_id', fn($row) => $row->detailType->name ?? 'N/A')
                    ->addColumn('category_id', fn($row) => $row->category->name ?? 'N/A')
                    ->addColumn('status', function ($row) {
            return '<span class="badge bg-success">Active</span>'; // You can return '1' instead if needed
        })

            ->addColumn('action', function ($row) {
                // $editBtn = '<a data-id="' . $row->id . '" class="btn btn-primary btn-sm me-1 text-white inventory_edit">Edit</a>';
               $editBtn= '<a data-id="' . $row->id . '" class="btn btn-primary me-2 btn-sm text-white vendor_edit" href="' . route('inventory.inventory-management.edit', $row->id) . '">Edit</a>';
                $deleteForm = '<form class="delete_form d-inline" action="' . route("inventory.inventory-management.destroy", $row->id) . '" method="POST">';
                $deleteForm .= method_field('DELETE') . csrf_field();
                $deleteForm .= '<button type="submit" class="btn btn-danger btn-sm">Delete</button></form>';
                return $editBtn . $deleteForm;
            })
           ->rawColumns(['status', 'action'])
            ->make(true);
    }

    $categories = $this->inventoryCategoryService->getCategories();
    $inventoryCategories = $this->inventoryCategoryService->getData();

    return view('admin.inventory_management.inventory_center.index', compact('categories', 'inventoryCategories'));
}



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
   {
    if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    // Level 2 categories
    $accountTypes = InventoryCategory::where('level', 2)->get();

    // Level 3 categories
    $detailTypes = InventoryCategory::where('level', 3)->get();

    // Fetch categories from b-category table
    $categories = BCategory::all();
  
     // Fetch all groups where parent_id = 30
    $assetAccounts = Group::where('parent_id', 4)
                            ->where('status', 1) // Optional if you want only active
                            ->pluck('name', 'id'); // Get key-value pair (id => name)
 
    // // Fetch all groups where parent_id = 36
    // $saleAccounts = Group::where('parent_id', 36)
    //                         ->where('status', 1) // Optional if you want only active
    //                         ->pluck('name', 'id'); // Get key-value pair (id => name)

    $saleAccounts = Group::where('id', 4)->value('name');
    $costAccounts = Group::where('id', 4)->value('name');

    return view(
            'admin.inventory_management.inventory_center.create-inventory',
            compact('accountTypes', 'detailTypes', 'categories','assetAccounts','saleAccounts','costAccounts')
        );

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
      
    public function store(Request $request)
      {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        // Validation rules
        $rules = [
           
            'account_type' => 'required|exists:inventory_categorys,id',
            'detail_type' => 'required|exists:inventory_categorys,id',  
            'category' => 'required|exists:b_category,id',    
            'inventory_type' => 'required|string|in:OWNED INVENTORY,CONSIGNMENT INVENTORY,THIRD-PARTY INVENTORY',
            'item_name' => 'required|string|max:255',
            'additional_description' => 'nullable|string',
            'remarks' => 'nullable|string',
            'asset_account' => 'required|exists:groups,id',
            'sale_account_id' => 'nullable|exists:groups,id',
            'cost_account_id' => 'nullable|exists:groups,id',

            'sale_type' => 'nullable|string|in:RETAIL,WHOLESALE,ONLINE',
          
            'sales_tax_percentage' => 'nullable|string',
            'further_sale_tax' => 'nullable|string',
            'hs_code' => 'nullable|string',
            'hs_code_description' => 'nullable|string|max:255',
            'packing_unit' => 'required|integer|min:1',
            'packing_unit_type' => 'required|string|in:BAGS,BOXES,CARTONS',
            'base_sale_unit' => 'required|integer|min:1',
            'base_sale_unit_type' => 'required|string|in:BAGS,BOXES,CARTONS',
            'qty_in_hand' => 'nullable|integer|min:0',
            'as_on_date' => 'nullable|date',
            'as_of_date' => 'nullable|date',
            'cost_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'min_sale_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'reorder_level' => 'nullable|integer|min:0',
            'margin_percentage' => 'nullable|numeric|min:0',
            'commission_percentage' => 'nullable|numeric|min:0',
            'due_expiry_date' => 'nullable|integer|min:0',
            'code' => 'nullable|integer|min:0',

        ];

        $validated = $request->validate($rules);

        
    // Get the level info for the category
    $level = InventoryCategory::find($validated['category']);
    if (!$level) {
        return back()->withErrors(['category' => 'Invalid category selected.']);
    }

    // Generate code
    $code = $this->createCode($level->level + 1, $validated['category']);


        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inventory_images', 'public');
        }

        // Create inventory record
        Inventory::create([
            'account_type_id' => $validated['account_type'], 
            'detail_type_id' => $validated['detail_type'],   
            'category_id' => $validated['category'],        
            'inventory_type' => $validated['inventory_type'],
            'item_name' => $validated['item_name'],
            'additional_description' => $validated['additional_description'],
            'remarks' => $validated['remarks'],
            'asset_account_id' => $validated['asset_account'], 
            'sale_account_id' => $validated['sale_account_id'],
            'cost_account_id' => $validated['cost_account_id'],
            'sale_type' => $validated['sale_type'],
            'sales_tax_percentage' => $validated['sales_tax_percentage'],
            'further_sale_tax' => $validated['further_sale_tax'],
            'hs_code' => $validated['hs_code'],
            'hs_code_description' => $validated['hs_code_description'],
            'packing_unit' => $validated['packing_unit'],
            'packing_unit_type' => $validated['packing_unit_type'],
            'base_sale_unit' => $validated['base_sale_unit'],
            'base_sale_unit_type' => $validated['base_sale_unit_type'],
            'qty_in_hand' => $validated['qty_in_hand'] ?? 0, 
            'as_on_date' => $validated['as_on_date'],
            'as_of_date' => $validated['as_of_date'],
            'cost_price' => $validated['cost_price'] ?? 0.00,
            'sale_price' => $validated['sale_price'] ?? 0.00,
            'min_sale_price' => $validated['min_sale_price'] ?? 0.00,
            'image' => $imagePath,
            'reorder_level' => $validated['reorder_level'] ?? 0,
            'margin_percentage' => $validated['margin_percentage'] ?? 0.00,
            'commission_percentage' => $validated['commission_percentage'] ?? 0.00,
            'due_expiry_date' => $validated['due_expiry_date'],
            'code'=> $code,
        ]);

        return view('admin.inventory_management.inventory_center.index'); // Corrected view path from .index to just .inventory_center
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function edit($id)
{
    if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    $edit = Inventory::findOrFail($id); // renamed from $inventory to $edit

    // Level 2 categories
    $accountTypes = InventoryCategory::where('level', 2)->get();

    // Level 3 categories
    $detailTypes = InventoryCategory::where('level', 3)->get();

    // Fetch categories from b-category table
    $categories = BCategory::all();

    // Fetch all groups where parent_id = 4
    $assetAccounts = Group::where('parent_id', 4)
                        ->where('status', 1)
                        ->pluck('name', 'id');

$saleAccounts = Group::where('status', 1)->pluck('name', 'id'); // returns key-value pair
$costAccounts = Group::where('status', 1)->pluck('name', 'id');

    return view('admin.inventory_management.inventory_center.create-inventory', 
        compact('edit', 'accountTypes', 'detailTypes', 'categories', 'assetAccounts', 'saleAccounts', 'costAccounts'));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
public function update(Request $request, $id)
{
    if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    $inventory = Inventory::findOrFail($id);

    $validated = $request->validate([
        'account_type' => 'required|integer',
        'detail_type' => 'required|integer',
        'inventory_type' => 'required|string',
        'item_name' => 'required|string|max:255',
        'category' => 'nullable|integer',
        'additional_description' => 'nullable|string',
        'remarks' => 'nullable|string',
        'asset_account' => 'required|integer',
        'sale_account_id' => 'nullable|integer',
        'cost_account_id' => 'nullable|integer',
        'sale_type' => 'nullable|string',
        'sales_tax_percentage' => 'nullable|string',
        'further_sale_tax' => 'nullable|string',
        'hs_code' => 'nullable|string',
        'hs_code_description' => 'nullable|string',
        'packing_unit' => 'nullable|numeric',
        'packing_unit_type' => 'nullable|string',
        'base_sale_unit' => 'nullable|numeric',
        'base_sale_unit_type' => 'nullable|string',
        'qty_in_hand' => 'nullable|numeric',
        'as_on_date' => 'nullable|date',
        'as_of_date' => 'nullable|date',
        'cost_price' => 'nullable|numeric',
        'sale_price' => 'nullable|numeric',
        'min_sale_price' => 'nullable|numeric',
        'image' => 'nullable|image|max:2048',
        'reorder_level' => 'nullable|numeric',
        'margin_percentage' => 'nullable|numeric',
        'commission_percentage' => 'nullable|numeric',
        'due_expiry_date' => 'nullable|numeric',
    ]);

    // Handle image upload if a new image is provided
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/inventory'), $imageName);
        $validated['image'] = $imageName;
    }

    // Rename field to match your DB column if needed
    $validated['category_id'] = $validated['category'] ?? null;
    unset($validated['category']);

    // Update the inventory record
    $inventory->update($validated);

    return redirect()->route('inventory.inventory-management.index')
                     ->with('success', 'Inventory updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Inventory::findOrFail($id);
        $data->delete($id);
        return redirect()->route('inventory.inventory-center.index')->with('success', 'Inventory deleted successfully.');

    }

private function createCode($level, $parent)
{
    if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    \Log::info("createCode called with level: $level, parent: $parent");
    switch ($level) {
        case 2:
            $count = InventoryCategory::where('level', $level)->count() + 1;
            return $this->baseCode . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);

        case 3:
            $parentCategory = InventoryCategory::where('id', $parent)->first();
            \Log::info("Parent category for level 3: ", [$parentCategory]);
            if (!$parentCategory) return null;
            $count = InventoryCategory::where('level', $level)->where('parent_id', $parent)->count() + 1;
            return $parentCategory->code . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);

        case 4:
            $parentCategory = InventoryCategory::where('id', $parent)->first();
            \Log::info("Parent category for level 4: ", [$parentCategory]);
            if (!$parentCategory) return null;
            $count = Vendor::where('inventory_category_id', $parent)->count() + 1;
            \Log::info("Vendor count for level 4: $count");
            return $parentCategory->code . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
    \Log::info("Returning null for level: $level");
    return null;
}

}

