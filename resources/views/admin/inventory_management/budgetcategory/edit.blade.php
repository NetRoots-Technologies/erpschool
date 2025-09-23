@extends('admin.layouts.main')
@section('title', 'Edit Budget Category')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>Edit Category</h4>
        </div>
        <div class="card-body">
            <form id="editform" method="POST" action="{{ route('inventory.category.update', $bCategoryId->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Category Name --}}
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="department_create_label">Category Name:*</label>
                            <input type="text" required class="form-control" id="title" name="title"
                                value="{{ old('title', $bCategoryId->title) }}">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Description</label>
                            <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3">{{ old('description', $bCategoryId->description) }}</textarea>
                        </div>
                    </div>

                    {{-- Checkbox for Parent Category --}}
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="parent_category"
                                   {{ $bCategoryId->parent_id ? 'checked' : '' }}>
                            <label class="form-check-label" for="parent_category">
                                Add as sub category
                            </label>
                        </div>
                    </div>

                    {{-- Dropdown for Parent Category --}}
                    <div class="col-md-12 {{ $bCategoryId->parent_id ? '' : 'd-none' }}" id="showParentCategory">
                        <div class="form-group">
                            <label for="parent_id" class="form-label"><b>Select Parent Category:</b></label>
                            <select name="parent_id" id="parent_id"
                                class="form-control @error('parent_id') is-invalid @enderror">
                                <option value="">-- Choose Parent Category --</option>
                                @foreach ($categories as $cate)
                                    <option value="{{ $cate->id }}" {{ $bCategoryId->parent_id == $cate->id ? 'selected' : '' }}>
                                        {{ $cate->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-12">
                        <div class="form-group text-right d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">Update</button>
                            <a href="{{ route('inventory.category.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#parent_category').on('change', function() {
            if ($(this).is(':checked')) {
                $('#showParentCategory').removeClass('d-none');
            } else {
                $('#showParentCategory').addClass('d-none');
                $('#parent_id').val('');
            }
        });
    });
</script>
@endsection
