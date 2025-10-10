@extends('admin.layouts.main')

@section('title')
    Edit Permission
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-header bg-light">
                    <h3 class="text-22 text-midnight text-bold mb-4">Edit Permission</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('permissions.update', $Permission->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="font-weight-bold">Name</label>
                                    <input type="text" required name="name" class="form-control" id="name"
                                           value="{{ old('name', $Permission->name) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="main_check" class="font-weight-bold">Main</label><br>
                                    <input type="checkbox" id="main_check" name="main" value="1"
                                           {{ $Permission->main == 1 ? 'checked' : '' }}>
                                    <label for="main_check">Is Main Permission</label>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="parent_permission_section">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parrent_val" class="font-weight-bold">Parent Permission</label>
                                    <select id="parrent_val" name="parrent" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach($mainpermissions as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $Permission->parent_id == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right mt-4">
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            <a href="{{ route('permissions.index') }}" class="btn btn-danger btn-sm">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function () {
        function toggleParentField() {
            if ($('#main_check').is(':checked')) {
                $('#parent_permission_section').addClass('d-none');
                $('#parrent_val').prop('required', false);
            } else {
                $('#parent_permission_section').removeClass('d-none');
                $('#parrent_val').prop('required', true);
            }
        }

        $('#main_check').on('change', toggleParentField);

        // Initial toggle on page load
        toggleParentField();
    });
</script>
@endsection
