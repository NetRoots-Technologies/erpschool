@extends('admin.layouts.main')

@section('title')
    Bank File
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4">Bank File</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.banks_file.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('admin.import.bankFile') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:20px;">

                                    <div class="row">

                                        <div class="col-md-6">
                                            <label for="Academic"><b>Banks *</b></label>
                                            <select name="bank_id"
                                                    class="form-control select2 basic-single"
                                                    required id="session_id">
                                                <option value="" selected disabled>Select Bank</option>
                                                @foreach($formatted_banks as $key => $item)
                                                    <option value="{!! $key !!}">{!! $item !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="file_upload"><b>Upload Excel File *</b></label>
                                            <input type="file" name="excel_file" class="form-control"
                                                 required>
                                        </div>


                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary">Import</button>
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
@section('css')

    <link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">

@endsection
@section('js')

    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>



@endsection

