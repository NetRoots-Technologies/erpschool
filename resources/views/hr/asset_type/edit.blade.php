@extends('admin.layouts.main')

@section('title')
Asset Type
@stop

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Edit Asset Type</h3>
                    <div class="row    mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{!! route('hr.asset_type.index') !!}" class="btn btn-primary btn-sm ">
                                Back </a>
                        </div>
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('hr.asset_type.update',$asset_type->id) }}" enctype="multipart/form-data"
                        id="form_validation" autocomplete="off" method="post">
                        @csrf
                        @method('put')
                        <div class="w-100 p-3">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="branch"><b>Name *</b></label>
                                        <input type="text" value="{{ $asset_type->name }}" name="name"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="depreciation"><b>Depreciation Percentage <span class="danger">*</span> </b></label>
                                        <input id="depreciation" type="number" min="0" max="100" value="{{ $asset_type->depreciation }}" name="depreciation" class="form-control" placeholder="10%" required>
                                    </div>
                                </div>

                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"
                                    style="margin-bottom: 10px;margin-left: 10px;">Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('css')

<link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">

@endsection
@section('js')

<script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>

<script>
    $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

</script>
<script>
    $(document).ready(function () {
            $('.basic-multiple').select2();
        });
</script>




@endsection
