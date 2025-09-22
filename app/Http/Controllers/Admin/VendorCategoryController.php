<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\VendorCategoryService;
use App\Models\inventory\VendorCategory;
use Illuminate\Support\Facades\Gate;

class VendorCategoryController extends Controller
{

    private $vendorCategoryService = null;
    public function __construct()
    {
        $this->vendorCategoryService = new VendorCategoryService;
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
        return view('admin.inventory_management.vendor_category.index');
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
        $request->validate([
            'name' => 'required|min:3',
            'code' => 'required|integer|unique:vendor_categorys,code',
        ]);

        VendorCategory::create([
            'name' => $request['name'],
            'code' => $request['code'],
            'level' => 1,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Vendor category created successfully.'
        ], 200);
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
        $category = VendorCategory::findOrFail($id);
        return response()->json($category);
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
        $request->validate(
            [
                'name' => 'required|min:3'
            ],
        );
        if ($this->vendorCategoryService->update($request, $id)) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor category updated successfully.'
            ], 200);
        }
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
        if ($this->vendorCategoryService->destroy($id)) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor category deleted successfully.'
            ], 200);
        }
    }

    public function getData(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $this->vendorCategoryService->getCategoriesLevelOne();
        // return $data;
        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('name', fn($row) => $row->name ?? 'N/A')

            ->addColumn('code', fn($row) => $row->code ?? 'N/A')

            ->addColumn('action', function ($row) {
                $btn = '<form class="delete_form" action="' . route("inventory.vendor-category.destroy", $row->id) . '" id="vendor-' . $row->id . '" method="POST">';
                $btn .= '<a data-id="' . $row->id . '" class="btn btn-primary me-2 btn-sm text-white vendor_edit" href="' . route('inventory.vendor-category.edit', $row->id) . '">Edit</a>';
                $btn .= '<button type="submit" class="btn btn-danger delete-op btn-sm">Delete</button>';
                $btn .= method_field('DELETE') . csrf_field();
                $btn .= '</form>';
                return $btn;
            })

            ->rawColumns(['action', 'status'])
            ->make(true);
    }

}

