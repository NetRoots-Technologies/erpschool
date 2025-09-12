<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $vendorId = $this->route('vendor_management');
        $isUpdate = $this->isMethod('patch') || $this->isMethod('put');
        return [
            'detail_type' => $isUpdate ? 'sometimes|exists:vendor_categorys,id' : 'required|exists:vendor_categorys,id',
            'vendor_name' => 'required|string|max:255',
            'category' => 'required|exists:b_category,id',
            'description' => 'nullable|string',
            'purchase_control_account' => 'nullable|exists:groups,id',
            'company_name' => 'nullable|string|max:255',
            'cnic' => 'nullable|string|max:25',
            'ntn' => 'nullable|string|max:25',
            'strn' => 'nullable|string|max:25',
            'folio_no' => 'nullable|string|max:25',
            'state' => 'nullable|exists:states,id',
            'city' => 'nullable|exists:cities,id',
            'mobileNo' => 'required|string|max:20',
            'phoneNo' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:vendors,email,' . $vendorId,
            'zip_code' => 'nullable|string|max:10',
            'postal_address' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string|max:255',
        ];
    }
}
