@extends('admin.layouts.main')
@section('title')
permission show
@stop
@section('content')
<div class="container">

    <div class="row    mt-4 mb-4 ">
        <div class="col-12 text-right">
            <a href="{!! route('admin.permissions.index') !!}" class="btn btn-primary btn-sm "> Permission</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-header bg-light">
                    <h3 class="text-22 text-midnight text-bold mb-4"> View </h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <div class="input-label">
                            <label>Name</label>
                        </div>
                        <input disabled type="text" required class="form-control" value="{!! $Permision->name !!}"
                            name="name">
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <div class="input-label">
                                <label>Main</label>
                            </div>
                            <input disabled type="checkbox" @if($Permision->main==1) checked @endif class=" "
                            value="main" name="main">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('short_detail');
        CKEDITOR.replace('detail');
</script>
@endsection