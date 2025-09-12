@extends('admin.layouts.main')


@section('content')
    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Add Currency</strong> <span class="float-end"><a href="{{url('/admin/currency')}}" class="btn btn-primary" type="submit" value="Save">Back</a></span></div>
                        <div class="card-body">
                            <form method="post" action="">
{{--                            <form action="{{route('admin.permissions.store')}}" method="post" enctype="multipart/form-data">--}}
                                @csrf
                                <div class="row">

                                    <div class="col-md-4">
                                        <label for="name">Name</label>
                                        <input required type="text" name="name" class="form-control" autocomplete="off">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="name">Code</label>
                                        <input required type="text" name="code" class="form-control" autocomplete="off">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="name">Decimal</label>
                                        <input required type="text" name="decimal" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="name">Symbols</label>
                                        <input required type="text" name="symbols" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-6">

                                        <label for="name">Rate</label>
                                        <input required type="text" name="rate" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-6">

                                        <label for="name">Status</label>
                                        <br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="Active">
                                            <label class="form-check-label" for="inlineRadio1">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="Inactive">
                                            <label class="form-check-label" for="inlineRadio2">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Add Currency</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    <div class="container my-4">--}}
{{--        <div class="row">--}}
{{--            <h1>Add Currency</h1>--}}
{{--            <form method="post" action="">--}}
{{--                @csrf--}}

{{--                    <div class="row">--}}

{{--                        <div class="col-md-4">--}}
{{--                            <label for="name">Name</label>--}}
{{--                            <input required type="text" name="name" class="form-control" autocomplete="off">--}}
{{--                        </div>--}}

{{--                        <div class="col-md-4">--}}
{{--                            <label for="name">Code</label>--}}
{{--                            <input type="text" name="code" class="form-control" autocomplete="off">--}}
{{--                        </div>--}}

{{--                        <div class="col-md-4">--}}
{{--                            <label for="name">Decimal</label>--}}
{{--                            <input type="text" name="decimal" class="form-control" autocomplete="off">--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                --}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-6">--}}
{{--                            <label for="name">Symbols</label>--}}
{{--                            <input type="text" name="symbols" class="form-control" autocomplete="off">--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}

{{--                            <label for="name">Rate</label>--}}
{{--                            <input type="text" name="rate" class="form-control" autocomplete="off">--}}
{{--                        </div>--}}
{{--                        <div class="col-md-6">--}}

{{--                            <label for="name">Status</label>--}}
{{--                            <div class="form-check">--}}
{{--                                <input class="form-check-input" type="radio" name="flexRadioDefault"--}}
{{--                                       id="flexRadioDefault1">--}}
{{--                                <label class="form-check-label" for="flexRadioDefault1">--}}
{{--                                    Active--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="form-check">--}}
{{--                                <input class="form-check-input" type="radio" name="flexRadioDefault"--}}
{{--                                       id="flexRadioDefault2"--}}
{{--                                       checked>--}}
{{--                                <label class="form-check-label" for="flexRadioDefault2">--}}
{{--                                    Inactive--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        </div>--}}
{{--                   --}}
{{--               --}}

{{--                <button type="submit" class="btn btn-primary">Add Currency</button>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
