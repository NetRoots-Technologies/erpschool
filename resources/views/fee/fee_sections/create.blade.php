@extends('admin.layouts.main')

@section('title')
    Fee Section Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create  Fee Section</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.fee-sections.index') !!}" class="btn btn-primary btn-md"> Back </a>
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
                        <form action="{!! route('admin.fee-sections.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:20px;">
                                    <div class="form-row">

                                        <div class="col-md-4">
                                            <label for="branches">Branch Name <b>*</b></label>
                                            <select id="branchSelect" required name="branch_id" class="form-select select2 basic-single mt-3 branch_select" aria-label=".form-select-lg example">
                                                <option value="">Select Branch</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="print_section">Print Section <b>*</b></label>
                                            <select required name="print_section" class="form-select select2 basic-single mt-3" aria-label=".form-select-lg example">
                                                <option value="">Select Print Section</option>
                                                @foreach($feeSections as $section)
                                                    <option value="{{ $section }}">{{ $section }}</option>
                                                @endforeach
                                            </select>
                                        </div>



                                        <div class="col-md-4">
                                            <label for="branches">Fee Section Name <b>*</b></label>
                                            <input type="text" class="form-control" name="name" required>
                                        </div>


                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>

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

@endsection

