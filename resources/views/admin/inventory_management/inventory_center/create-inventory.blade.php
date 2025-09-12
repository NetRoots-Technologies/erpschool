@extends('admin.layouts.main')

@section('title', isset($edit) ? 'Edit Inventory' : 'Create Inventory')

@section('content')

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container-fluid">
        <div class="card p-4">
            <form id="inventoryForm" method="POST" 
                action="{{ isset($edit) ? route('inventory.inventory-management.update', $edit->id) : route('inventory.inventory-management.store') }}" 
                enctype="multipart/form-data">
                @csrf
                @isset($edit)
                    @method('PUT')
                @endisset

                <div class="row">
                    {{-- Account Type --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Account Type</label>
                            <select name="account_type" class="form-select select2">
                                <option value="">Select Account Type</option>
                                @foreach($accountTypes as $account)
                                    <option value="{{ $account->id }}" {{ old('account_type', isset($edit) ? $edit->account_type : '') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Detail Type --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Detail Type</label>
                            <select name="detail_type" class="form-select select2">
                                <option value="">Select Detail Type</option>
                                @foreach($detailTypes as $detail)
                                    <option value="{{ $detail->id }}" {{ old('detail_type', isset($edit) ? $edit->detail_type : '') == $detail->id ? 'selected' : '' }}>
                                        {{ $detail->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('detail_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Inventory Type --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Inventory Type</label>
                            <select name="inventory_type" class="form-select select2">
                                <option value="OWNED INVENTORY" {{ old('inventory_type', isset($edit) ? $edit->inventory_type : '') == 'OWNED INVENTORY' ? 'selected' : '' }}>OWNED INVENTORY</option>
                                <option value="CONSIGNMENT INVENTORY" {{ old('inventory_type', isset($edit) ? $edit->inventory_type : '') == 'CONSIGNMENT INVENTORY' ? 'selected' : '' }}>CONSIGNMENT INVENTORY</option>
                                <option value="THIRD-PARTY INVENTORY" {{ old('inventory_type', isset($edit) ? $edit->inventory_type : '') == 'THIRD-PARTY INVENTORY' ? 'selected' : '' }}>THIRD-PARTY INVENTORY</option>
                            </select>
                            @error('inventory_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Item Name --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" name="item_name" class="form-control" 
                                value="{{ old('item_name', isset($edit) ? $edit->item_name : '') }}">
                            @error('item_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-select select2">
                                <option value="">Choose</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category', isset($edit) ? $edit->category_id : '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Additional Description --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Additional Description</label>
                            <textarea name="additional_description" class="form-control" rows="2">{{ old('additional_description', isset($edit) ? $edit->additional_description : '') }}</textarea>
                            @error('additional_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Remarks --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', isset($edit) ? $edit->remarks : '') }}</textarea>
                            @error('remarks')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Asset Account --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Asset Account</label>
                            <select name="asset_account" class="form-select select2">
                                <option value="">Select Asset Account</option>
                                @foreach($assetAccounts as $id => $name)
                                    <option value="{{ $id }}" {{ old('asset_account', isset($edit) ? $edit->asset_account : '') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('asset_account')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

             {{-- Cost Account --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cost Account</label>
                        <select name="cost_account_id" class="form-select select2">
                            <option value="41">{{ $costAccounts }}</option> {{-- THIS IS ALSO A PROBLEM LINE --}}
                        </select>
                        @error('cost_account')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                    {{-- Sale Type --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sale Type</label>
                            <select name="sale_type" class="form-select select2">
                                <option value="">Choose</option>
                                <option value="RETAIL" {{ old('sale_type', isset($edit) ? $edit->sale_type : '') == 'RETAIL' ? 'selected' : '' }}>RETAIL</option>
                                <option value="WHOLESALE" {{ old('sale_type', isset($edit) ? $edit->sale_type : '') == 'WHOLESALE' ? 'selected' : '' }}>WHOLESALE</option>
                                <option value="ONLINE" {{ old('sale_type', isset($edit) ? $edit->sale_type : '') == 'ONLINE' ? 'selected' : '' }}>ONLINE</option>
                            </select>
                            @error('sale_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Sales Tax % --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sales Tax %</label>
                            <select name="sales_tax_percentage" class="form-select select2">
                                <option value="">Choose</option>
                                <option value="Tax Exempt @ 0%" {{ old('sales_tax_percentage', isset($edit) ? $edit->sales_tax_percentage : '') == 'Tax Exempt @ 0%' ? 'selected' : '' }}>Tax Exempt @ 0%</option>
                                <option value="TAHIR 12345 @ 9.1" {{ old('sales_tax_percentage', isset($edit) ? $edit->sales_tax_percentage : '') == 'TAHIR 12345 @ 9.1' ? 'selected' : '' }}>TAHIR 12345 @ 9.1</option>
                                <option value="Sales Tax @ 25% @ 25" {{ old('sales_tax_percentage', isset($edit) ? $edit->sales_tax_percentage : '') == 'Sales Tax @ 25% @ 25' ? 'selected' : '' }}>Sales Tax @ 25% @ 25</option>
                            </select>
                            @error('sales_tax_percentage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Further Sale Tax --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Further Sale Tax</label>
                            <select name="further_sale_tax" class="form-select select2">
                                <option value="">Choose</option>
                                <option value="TAHIR 12345 @ 9.1%" {{ old('further_sale_tax', isset($edit) ? $edit->further_sale_tax : '') == 'TAHIR 12345 @ 9.1%' ? 'selected' : '' }}>TAHIR 12345 @ 9.1%</option>
                                <option value="Sales Tax @ 25% @ 25%" {{ old('further_sale_tax', isset($edit) ? $edit->further_sale_tax : '') == 'Sales Tax @ 25% @ 25%' ? 'selected' : '' }}>Sales Tax @ 25% @ 25%</option>
                            </select>
                            @error('further_sale_tax')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- HS Code --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HS Code</label>
                            <select name="hs_code" class="form-select select2">
                                <option value="">Choose</option>
                                <option value="1234.56" {{ old('hs_code', isset($edit) ? $edit->hs_code : '') == '1234.56' ? 'selected' : '' }}>1234.56 - General Goods</option>
                                <option value="7890.12" {{ old('hs_code', isset($edit) ? $edit->hs_code : '') == '7890.12' ? 'selected' : '' }}>7890.12 - Electronics</option>
                                <option value="3456.78" {{ old('hs_code', isset($edit) ? $edit->hs_code : '') == '3456.78' ? 'selected' : '' }}>3456.78 - Textiles</option>
                            </select>
                            @error('hs_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- HS Code Description --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>HS Code Description</label>
                            <input type="text" name="hs_code_description" class="form-control" 
                                value="{{ old('hs_code_description', isset($edit) ? $edit->hs_code_description : '') }}">
                            @error('hs_code_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Packing Unit (P) --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Packing Unit (P)</label>
                            <div class="input-group">
                                <input type="number" name="packing_unit" class="form-control" 
                                    value="{{ old('packing_unit', isset($edit) ? $edit->packing_unit : '1') }}">
                                <select name="packing_unit_type" class="form-select">
                                    <option value="BAGS" {{ old('packing_unit_type', isset($edit) ? $edit->packing_unit_type : '') == 'BAGS' ? 'selected' : '' }}>BAGS</option>
                                    <option value="BOXES" {{ old('packing_unit_type', isset($edit) ? $edit->packing_unit_type : '') == 'BOXES' ? 'selected' : '' }}>BOXES</option>
                                    <option value="CARTONS" {{ old('packing_unit_type', isset($edit) ? $edit->packing_unit_type : '') == 'CARTONS' ? 'selected' : '' }}>CARTONS</option>
                                </select>
                            </div>
                            @error('packing_unit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @error('packing_unit_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Base/Sale Unit (S) --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Base/Sale Unit (S)</label>
                            <div class="input-group">
                                <input type="number" name="base_sale_unit" class="form-control" 
                                    value="{{ old('base_sale_unit', isset($edit) ? $edit->base_sale_unit : '1') }}">
                                <select name="base_sale_unit_type" class="form-select">
                                    <option value="BAGS" {{ old('base_sale_unit_type', isset($edit) ? $edit->base_sale_unit_type : '') == 'BAGS' ? 'selected' : '' }}>BAGS</option>
                                    <option value="BOXES" {{ old('base_sale_unit_type', isset($edit) ? $edit->base_sale_unit_type : '') == 'BOXES' ? 'selected' : '' }}>BOXES</option>
                                    <option value="CARTONS" {{ old('base_sale_unit_type', isset($edit) ? $edit->base_sale_unit_type : '') == 'CARTONS' ? 'selected' : '' }}>CARTONS</option>
                                </select>
                            </div>
                            @error('base_sale_unit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @error('base_sale_unit_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Qty In Hand --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Qty In Hand</label>
                            <input type="number" name="qty_in_hand" class="form-control" 
                                value="{{ old('qty_in_hand', isset($edit) ? $edit->qty_in_hand : '') }}">
                            @error('qty_in_hand')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- As On Date --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>As On Date</label>
                            <input type="date" name="as_on_date" class="form-control" 
                                value="{{ old('as_on_date', isset($edit) ? $edit->as_on_date : '') }}">
                            @error('as_on_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- As Of Date --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>As Of Date</label>
                            <input type="date" name="as_of_date" class="form-control" 
                                value="{{ old('as_of_date', isset($edit) ? $edit->as_of_date : '') }}">
                            @error('as_of_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Cost Price --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cost Price</label>
                            <input type="number" name="cost_price" class="form-control" step="0.01" 
                                value="{{ old('cost_price', isset($edit) ? $edit->cost_price : '') }}">
                            @error('cost_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Sale Price (S) --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sale Price (S)</label>
                            <input type="number" name="sale_price" class="form-control" step="0.01" 
                                value="{{ old('sale_price', isset($edit) ? $edit->sale_price : '') }}">
                            @error('sale_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Min. Sale Price (S) --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Min. Sale Price (S)</label>
                            <input type="number" name="min_sale_price" class="form-control" step="0.01" 
                                value="{{ old('min_sale_price', isset($edit) ? $edit->min_sale_price : '') }}">
                            @error('min_sale_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Select Image --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Select Image</label>
                            <input type="file" name="image" class="form-control">
                            @if(isset($edit) && $edit->image)
                                <p class="mt-1">Current image: {{ $edit->image }}</p>
                            @endif
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Reorder Level --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Reorder Level</label>
                            <input type="number" name="reorder_level" class="form-control" 
                                value="{{ old('reorder_level', isset($edit) ? $edit->reorder_level : '') }}">
                            @error('reorder_level')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Margin % --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Margin %</label>
                            <input type="number" name="margin_percentage" class="form-control" step="0.01" 
                                value="{{ old('margin_percentage', isset($edit) ? $edit->margin_percentage : '') }}">
                            @error('margin_percentage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Commission % --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Commission %</label>
                            <input type="number" name="commission_percentage" class="form-control" step="0.01" 
                                value="{{ old('commission_percentage', isset($edit) ? $edit->commission_percentage : '') }}">
                            @error('commission_percentage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Due Expiry Date (In Months) --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Due Expiry Date (In Months)</label>
                            <input type="number" name="due_expiry_date" class="form-control" 
                                value="{{ old('due_expiry_date', isset($edit) ? $edit->due_expiry_date : '') }}">
                            @error('due_expiry_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer justify-content-end">
                    <button type="submit" class="btn btn-primary">{{ isset($edit) ? 'Update' : 'Create' }}</button>
                    <button type="button" class="btn cancel-modal btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

@endsection