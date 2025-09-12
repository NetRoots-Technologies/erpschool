@extends('admin.layouts.main')
@section('title', isset($edit) ? 'Edit Vendor' : 'Create Vendor')

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
            <form id="vendorForm" method="POST"
                action="{{ isset($edit) ? route('inventory.vendor-management.update', $edit->id) : route('inventory.vendor-management.store') }}">
                @csrf
                @isset($edit)
                    @method('PUT')
                @endisset
                <div class="row">
                    {{-- Vendor Name --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Vendor Name <span class="text-danger">*</span></label>
                            <input type="text" name="vendor_name" class="form-control"
                                value="{{ old('vendor_name', isset($edit) ? $edit->name : (env('APP_ENV') == 'local' ? 'Vendor abc' : '')) }}">
                            @error('vendor_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Vendor Category --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Vendor Category <span class="text-danger">*</span></label>
                            @if(!isset($edit))
                                <select name="detail_type" class="form-select select2">
                                    <option value="">Select Detail Type</option>
                                    @foreach ($vendorCategory as $type)
                                        <option value="{{ $type->id }}" {{ old('detail_type', isset($edit) ? $edit->vendor_category_id : '') == $type->id ? 'selected' : '' }}>
                                            {{ $type->code . ' - ' . $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('detail_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @else
                                <p style="line-height: 1.2"
                                    class="border border-black mb-0 p-2 rounded-1 bg-gray-100 text-black">
                                    {{ $edit->code . ' - ' . $edit->vendorCategorys->name }}
                                </p>
                            @endif
                        </div>

                    </div>

                    {{-- Category --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Budget Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select select2">
                                <option value="">Select Category Type</option>
                                @foreach ($budgetCategories as $type)
                                    <option value="{{ $type->id }}" {{ old('category', isset($edit) ? $edit->b_category_id : '') == $type->id ? 'selected' : '' }}>
                                        {{ $type->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control"
                                value="{{ old('description', isset($edit) ? $edit->description : '') }}">
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Purchase Control Account --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Purchase Control Account</label>
                            <select name="purchase_control_account" class="form-select select2">
                                <option value="">Select Purchase Control Account</option>
                                <option value="{{ $pca->id }}" {{ old('purchase_control_account', isset($edit) ? $edit->purchase_control_account : '') == $pca->id ? 'selected' : '' }}>
                                    {{ $pca->name }}
                                </option>
                            </select>
                            @error('purchase_control_account')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Company Name --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" name="company_name" class="form-control"
                                value="{{ old('company_name', isset($edit) ? $edit->company_name : '') }}">
                            @error('company_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- CNIC --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>CNIC</label>
                            <input type="text" name="cnic" class="form-control"
                                value="{{ old('cnic', isset($edit) ? $edit->cnic : '') }}">
                            @error('cnic')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- NTN --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>NTN</label>
                            <input type="text" name="ntn" class="form-control"
                                value="{{ old('ntn', isset($edit) ? $edit->ntn : '') }}">
                            @error('ntn')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- STRN --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>STRN</label>
                            <input type="text" name="strn" class="form-control"
                                value="{{ old('strn', isset($edit) ? $edit->strn : '') }}">
                            @error('strn')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Folio No --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Folio No.</label>
                            <input type="text" name="folio_no" class="form-control"
                                value="{{ old('folio_no', isset($edit) ? $edit->folio_no : '') }}">
                            @error('folio_no')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- State --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>State / Region</label>
                            <select name="state" class="form-select select2" id="state-select">
                                <option value="">Select State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state', isset($edit) ? $edit->state_id : '') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- City --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>City</label>
                            <select name="city" class="form-select select2" id="city-select" disabled>
                                <option value="">Select City</option>
                                {{-- Options will be populated via JS --}}
                            </select>
                            @error('city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Mobile No --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mobile No. <span class="text-danger">*</span></label>
                            <input type="text" name="mobileNo" class="form-control"
                                value="{{ old('mobileNo', isset($edit) ? $edit->mobileNo : (env('APP_ENV') == 'local' ? '03210000000' : '')) }}">
                            @error('mobileNo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Phone No --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Phone No.</label>
                            <input type="text" name="phoneNo" class="form-control"
                                value="{{ old('phoneNo', isset($edit) ? $edit->phoneNo : '') }}">
                            @error('phoneNo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            @if(!isset($edit))
                                <input type="email" name="email" class="form-control" value="{{ old('email', '') }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @else
                                <p style="line-height: 1.2"
                                    class="border border-black mb-0 p-2 rounded-1 bg-gray-100 text-black">
                                    {{ $edit->email ?? "Don't have an Email"}}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Zip Code --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Zip Code</label>
                            <input type="text" name="zip_code" class="form-control"
                                value="{{ old('zip_code', isset($edit) ? $edit->zip_code : '') }}">
                            @error('zip_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Postal Address --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Postal Address</label>
                            <textarea name="postal_address" class="form-control"
                                rows="2">{{ old('postal_address', isset($edit) ? $edit->postal_address : '') }}</textarea>
                            @error('postal_address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Shipping Address --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Shipping Address</label>
                            <textarea name="shipping_address" class="form-control"
                                rows="2">{{ old('shipping_address', isset($edit) ? $edit->shipping_address : '') }}</textarea>
                            @error('shipping_address')
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

@section('js')
    <script>
        $(document).ready(function () {
            const selectedState = "{{ old('state', isset($edit) ? $edit->state_id : '') }}";
            const selectedCity = "{{ old('city', isset($edit) ? $edit->city_id : '') }}";

            if (selectedState) {
                getCities(selectedState, selectedCity);
            }

            $('#state-select').on('change', function () {
                getCities($(this).val());
            });


        });
        function getCities(state, preselected = '') {
            $.ajax({
                url: "/inventory/getCities",
                type: 'GET',
                data: { state_id: state },
                success: function (response) {
                    $('#city-select').removeAttr('disabled');
                    $('#city-select').empty().append('<option value="">Select City</option>');
                    $.each(response, function (index, city) {
                        const selected = city.id == preselected ? 'selected' : '';
                        $('#city-select').append('<option value="' + city.id + '" ' + selected + '>' + city.name + '</option>');
                    });
                },
                error: function (xhr) {
                    console.error('Error fetching cities:', xhr.responseText);
                }
            });
        }
    </script>
@endsection