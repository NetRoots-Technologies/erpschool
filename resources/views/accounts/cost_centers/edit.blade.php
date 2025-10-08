@extends('admin.layouts.main')

@section('title', 'Edit Cost Center')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Edit Cost Center</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.cost_centers.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('accounts.cost_centers.update', $costCenter->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" value="{{ $costCenter->code }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $costCenter->name }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="department" {{ $costCenter->type == 'department' ? 'selected' : '' }}>Department</option>
                            <option value="project" {{ $costCenter->type == 'project' ? 'selected' : '' }}>Project</option>
                            <option value="location" {{ $costCenter->type == 'location' ? 'selected' : '' }}>Location</option>
                            <option value="other" {{ $costCenter->type == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Parent Cost Center</label>
                        <select name="parent_id" class="form-select">
                            <option value="">None (Top Level)</option>
                            @foreach($costCenters as $center)
                                @if($center->id != $costCenter->id)
                                <option value="{{ $center->id }}" {{ $costCenter->parent_id == $center->id ? 'selected' : '' }}>
                                    {{ $center->code }} - {{ $center->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $costCenter->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ $costCenter->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Cost Center
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th>Created:</th>
                            <td class="text-end">{{ $costCenter->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Updated:</th>
                            <td class="text-end">{{ $costCenter->updated_at->format('d M Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
