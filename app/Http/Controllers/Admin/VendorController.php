<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\Accounts\AccountLedger;
use App\Models\Admin\City;
use App\Models\Admin\State;
use App\Models\Admin\Vendor;
use App\Models\BCategory;
use App\Models\inventory\VendorCategory;
use App\Services\VendorCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VendorController extends Controller
{
    private $vendorCategoryService;
    public function __construct()
    {
        $this->vendorCategoryService = new VendorCategoryService;
    }

    public function getDataIndex()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = Vendor::with('vendorCategorys')->orderBy('created_at', 'desc')->get();
        // dd($data);
        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        if ($request->ajax()) {
            $data = Vendor::with(['vendorCategorys', 'cities'])->orderBy('created_at', 'desc');
            // return $data;
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('name', fn($row) => $row->name ?? 'N/A')

                ->addColumn('detail_type', fn($row) => $row->vendorCategorys->name ?? 'N/A')

                ->addColumn('email', fn($row) => $row->email ?? 'N/A')

                ->addColumn('city', fn($row) => $row->cities->name ?? 'N/A')

                ->addColumn('status', function ($row) {
                    return '
                    <div class="form-check form-switch">
                        <input class="form-check-input start-50 translate-middle-x ms-0 shadow-none" value="' . $row->status . '" data-id="' . $row->id . '" type="checkbox" role="switch" id="status-switch"' . ($row->status ? 'checked' : '') . ' >
                    </div>';
                })

                ->addColumn('mobileNo', fn($row) => $row->mobileNo ?? 'N/A')

                ->addColumn('action', function ($row) {
                    $btn = '<form class="delete_form" action="' . route("inventory.vendor-management.destroy", $row->id) . '" id="vendor-' . $row->id . '" method="POST">';
                    $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary me-2 btn-sm text-white vendor_edit" href="' . route('inventory.vendor-management.edit', $row->id) . '">Edit</a>';
                    $btn .= '<button type="submit" class="btn btn-danger delete-op btn-sm">Delete</button>';
                    $btn .= method_field('DELETE') . csrf_field();
                    $btn .= '</form>';
                    return $btn;
                })

                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        $vendorsCatgories = VendorCategory::get();
        return view('admin.inventory_management.vendor_center.index', compact('vendorsCatgories'));
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
        $vendorCategory = VendorCategory::where('level', 1)->get();
        $states = State::get();
        $budgetCategories = BCategory::get();
        $pca = Ledger::find(config('account_constants.PURCHASE_CONTROL_ACCOUNT'));

        return view('admin.inventory_management.vendor_center.create-vendor', compact('vendorCategory', 'states', 'budgetCategories', 'pca'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorRequest $request)
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $request->validated();
        $this->vendorCategoryService->storeVendors($data);
        return redirect()->route('inventory.vendor-management.index')->with('success', 'Vendor created successfully.');
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
        $edit = Vendor::where('id', $id)->with('vendorCategorys')->first();
        // dd($edit->vendorCategorys->name);
        $states = State::get();
        $categories = BCategory::get();
        $pca = Ledger::find(config('account_constants.PURCHASE_CONTROL_ACCOUNT'));
        return view('admin.inventory_management.vendor_center.create-vendor', compact('states', 'categories', 'pca', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VendorRequest $validatedDate, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $vendor = Vendor::findorFail($id);
        if (!$vendor)
            return redirect()->route('inventory.vendor-management.index')->with('failed', 'Error in Update');
        $vendor->update([
            'name' => $validatedDate['vendor_name'],
            'description' => $validatedDate['description'] ?? null,
            'b_category_id' => $validatedDate['category'],
            'company_name' => $validatedDate['company_name'] ?? null,
            'cnic' => $validatedDate['cnic'] ?? null,
            'ntn' => $validatedDate['ntn'] ?? null,
            'strn' => $validatedDate['strn'] ?? null,
            'folio_no' => $validatedDate['folio_no'] ?? null,
            'state_id' => $validatedDate['state'] ?? null,
            'city_id' => $validatedDate['city'] ?? null,
            'mobileNo' => $validatedDate['mobileNo'],
            'phoneNo' => $validatedDate['phoneNo'] ?? null,
            'zip_code' => $validatedDate['zip_code'] ?? null,
            'postal_address' => $validatedDate['postal_address'] ?? null,
            'shipping_address' => $validatedDate['shipping_address'] ?? null,
        ]);
        return redirect()->route('inventory.vendor-management.index')->with('success', 'Vendor Updated successfully.');

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
        $data = Vendor::findOrFail($id);
        $data->delete($id);
        return redirect()->route('inventory.vendor-management.index')->with('success', 'Vendor deleted successfully.');

    }
    public function getCities(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $request->validate([
            'state_id' => 'nullable|exists:states,id',
        ]);
        return City::where('state_id', $request['state_id'])->get();
    }

    public function toggleStatus(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'status' => 'required|boolean',
        ]);

        $vendor->status = $request['status'];
        $vendor->save();

        return response()->json([
            'message' => 'Vendor status updated successfully.',
            'status' => $vendor->status,
        ]);
    }

}

