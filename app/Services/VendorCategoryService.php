<?php

namespace App\Services;

use App\Models\Admin\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\inventory\VendorCategory;

class VendorCategoryService
{
    private $baseCode = 850;

    public function getCategories()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return VendorCategory::get();
    }
    public function getCategoriesLevelOne()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return VendorCategory::whereNull('parent_id')->get();
    }
    public function getData()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return VendorCategory::whereNull('parent_id')->with(['recursiveChildren', 'vendors'])->get();
    }
    public function store(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $level = VendorCategory::find($request['category'], ['level']);

        $code = $this->createCode($level->level + 1, $request['category']);

        // dd($code);
        VendorCategory::create([
            'name' => $request['name'],
            'parent_id' => $request['category'],
            'code' => $code,
            'level' => $level->level + 1,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $category = VendorCategory::find($id);
        return $category->update([
            'name' => $request['name'],
        ]);
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $category = VendorCategory::findOrFail($id);
        return $category->delete();
    }

    private function createCode($level, $parent)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        if ($level < 4)
            $lastItem = VendorCategory::where('level', $level)->orderBy('id', 'DESC')->value('code');
        else
            $lastItem = Vendor::where('id', $parent)->orderBy('id', 'DESC')->value('code');

        // $lastValue = (int) substr($lastItem, strlen($lastItem) - 1, 1) + 1;
        preg_match('/(\d+)$/', $lastItem, $matches);
        $lastValue = isset($matches[1]) ? (int) $matches[1] + 1 : 1;
        switch ($level) {
            case 2:
                return $this->baseCode . '-' . str_pad('', 1, '0') . $lastValue;
            case 3:
                $code = VendorCategory::where('id', $parent)->first(['code']);
                return $code->code . '-' . str_pad('', 2, '0') . $lastValue;
            case 4:
                // $code = VendorCategory::where('vendor_category_id', $parent)->first(['code']);
                $code = VendorCategory::where('id', $parent)->first(['code']);
                return $code->code . '-' . str_pad('', 3, '0') . $lastValue;
        }
        return null;
    }

    //vendorListing
    public function storeVendors($validatedDate)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $code = $this->createCode(4, $validatedDate['detail_type']);
        Vendor::create([
            'vendor_category_id' => $validatedDate['detail_type'],
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
            'email' => $validatedDate['email'],
            'zip_code' => $validatedDate['zip_code'] ?? null,
            'postal_address' => $validatedDate['postal_address'] ?? null,
            'shipping_address' => $validatedDate['shipping_address'] ?? null,
            'code' => $code
        ]);
        // dd($code);
    }
}
