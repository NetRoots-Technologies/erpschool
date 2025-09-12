@extends('admin.layouts.main')

@section('title')
    Walk-In Student
@stop

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Walk-In Student </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.student_databank.index') !!}" class="btn btn-primary btn-sm ">
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
                        <form action="{!! route('admin.walk_in_student') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            <div class="w-100">

                                @csrf
                                <div class="row">
                                    <h5>Student Data</h5>

                                    <div class="col-lg-6">
                                        <label for="name">Student Name</label>
                                        <input name="name" id="name" type="text" class="form-control"
                                               value="{{old('name')}}"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="address_city">City </label>
                                        <input name="city" id="city" type="text" class="form-control"
                                               value="{{old('city')}}"/>

                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <h5>Contact Details</h5>

                                <div class="col-lg-6">
                                    <label for="email">Email</label>
                                    <input name="email" id="email" type="email" class="form-control"
                                         required   value="{{old('email')}}"/>
                                </div>
                                <div class="col-lg-6">
                                    <label for="mobile_no">Contact No.</label>
                                    <input name="mobile_no" id="mobile_no" type="tel"
                                           class="form-control" value="{{old('mobile_no')}}"/>


                                </div>

                            </div>
                            <div class="row mt-5 mb-5">
                                <h5>Remarks</h5>

                                <div class="col-lg-12">
                                    <textarea class="form-control" name="remarks"
                                              value="{{old('remarks')}}">{{old('remarks')}}</textarea>
                                </div>


                            </div>
                            <div class="row mt-8 mb-3">

                                <div class="col-12">
                                    <div class="form-group text-right">

                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        <a href="{!! route('admin.student_databank.index') !!}"
                                           class=" btn btn-sm btn-danger">Cancel </a>
                                    </div>
                                </div>
                            </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@stop
@section('css')

@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endsection

