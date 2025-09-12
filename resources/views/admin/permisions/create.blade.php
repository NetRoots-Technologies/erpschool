@extends('admin.layouts.main')

@section('title')
Permission Create
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-header bg-light">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create </h3>
                </div>
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold  btn-md">Create Permission </h3>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="post" action="{!! route('permissions.store') !!}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <div class="input-label">
                                        <label>Name</label>
                                    </div>
                                    <input type="text" required class="form-control" value=" " name="name">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <div class="input-label">
                                        <label>Main</label>
                                    </div>
                                    <input type="checkbox" id="main_check" value="main" name="main">
                                </div>
                            </div>
                            <div class="col-6" id="parrent">
                                <div class="form-group">
                                    <div class="input-label">
                                        <label>Main Permission</label>
                                    </div>
                                    <select required id="parrent_val" name="parrent" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach($mainpermissions as $item)
                                        <option value="{!! $item->id !!}">{!! $item->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                    <a href="{!! route('permissions.index') !!}" class=" btn btn-sm btn-danger">Cancel
                                    </a>
                                </div>
                            </div>
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
            $('#main_check').change(function () {
                if ($(this).is(":checked")) {
                    $("#parrent").addClass("d-none");
                    $("#parrent_val").prop('required', false);
                } else {
                    $("#parrent").removeClass("d-none");
                    $("#parrent_val").prop('required', true);
                }
            });
        });
</script>
@endsection