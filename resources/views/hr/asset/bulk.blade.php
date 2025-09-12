@extends('admin.layouts.main')

@section('title')
Asset Create
@stop
@section('css')
<style>
    .row {
        padding-bottom: 5px;
        padding-top: 5px;
    }
</style>
@endsection
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Bulk Insert Asset
                    </h3>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </div>
                    @endif

                    <form action="{!! route('asset-bulk-save') !!}" enctype="multipart/form-data" id="form_validation"
                        autocomplete="off" method="post">
                        @csrf

                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:10px;">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="asset"><b>Bulk Asset File</b></label>
                                        <input id="asset" type="file" name="file" accept=".xlsx,.xls,.csv"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-lg-4 mt-4">
                                        <button type="submit" class="btn btn-primary"
                                            style="margin-bottom: 10px;margin-left: 10px;">Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
@endsection
