@extends('admin.layouts.main')


@section('content')

    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Update Currency</strong> <span class="float-end"><a href="{{url('/admin/currency')}}" class="btn btn-primary" type="submit" value="Save">Back</a></span></div>
                        <div class="card-body">
                            <form method="post" action="/admin/currency/edit">
                                {{--                            <form action="{{route('admin.permissions.store')}}" method="post" enctype="multipart/form-data">--}}
                                @csrf
                                <div class="row">

                                    <div class="col-md-4">
                                        <input type="hidden" name="id" value="{{$data->id}}

                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{$data->name}}" autocomplete="off" >
                                    </div>

                                    <div class="col-md-4">
                                        <label for="name">Code</label>
                                        <input type="text" name="code" class="form-control" value="{{$data->code}}" autocomplete="off">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="name">Decimal</label>
                                        <input type="decimal" name="decimal" class="form-control" value="{{$data->decimal}}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="name">Symbols</label>
                                        <input type="text" name="symbols" class="form-control" value="{{$data->symbols}}" autocomplete="off" >
                                    </div>
                                    <div class="col-md-6">

                                        <label for="name">Rate</label>
                                        <input type="text" name="rate" class="form-control" value="{{$data->rate}}" autocomplete="off" >
                                    </div>
                                    <div class="col-md-6">

                                        <label for="name">Status</label>
                                        <br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" @if($data->status == 'Active') checked @endif type="radio" name="status" id="inlineRadio1" value="Active">
                                            <label class="form-check-label" for="inlineRadio1">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input"  type="radio" @if($data->status == 'Inactive') checked @endif name="status" id="inlineRadio2" value="Inactive">
                                            <label class="form-check-label" for="inlineRadio2">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Update Currency</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






{{--    <div class="col-sm-6">--}}
{{--        <h1>Edit</h1>--}}
{{--        @if(Session::get('status'))--}}
{{--            <div class="alert alert-success alert-dismissible fade show" role="alert">--}}
{{--                {{Session::get("status")}}--}}
{{--                <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
{{--                    <span aria-hidden="true">&times;</span>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--        <form method="post" action="/edit">--}}
{{--            @csrf--}}
{{--            <div class="form-group">--}}
{{--                <input type="hidden" name="id" value="{{$data->id}}">--}}

{{--                <label for="name">Name</label>--}}
{{--                <input type="text" name="name" class="form-control" value="{{$data->name}}" autocomplete="off" >--}}
{{--            </div>--}}

{{--            <div class="form-group">--}}
{{--                <label for="code">Code</label>--}}
{{--                <input type="text" name="code" class="form-control" value="{{$data->code}}" autocomplete="off">--}}

{{--            </div>--}}

{{--            <div class="form-group">--}}
{{--                <label for="decimal">Decimal</label>--}}
{{--                <input type="decimal" name="decimal" class="form-control" value="{{$data->decimal}}" autocomplete="off">--}}

{{--            </div>--}}
{{--            <div class="form-group">--}}
{{--                <label for="symbol">Symbols</label>--}}
{{--                <input type="text" name="symbols" class="form-control" value="{{$data->symbols}}" autocomplete="off" >--}}

{{--            </div>--}}
{{--            <div class="form-group">--}}
{{--                <label for="rate">Rate</label>--}}
{{--                <input type="text" name="rate" class="form-control" value="{{$data->rate}}" autocomplete="off" >--}}

{{--            </div>--}}
{{--            <div class="form-group">--}}
{{--                <label for="status">Status</label>--}}
{{--                <input type="text" name="status" class="form-control" value="{{$data->status}}" autocomplete="off" >--}}

{{--            </div>--}}


{{--            <button type="submit" class="btn btn-primary">Update Currency</button>--}}
{{--        </form>--}}
{{--    </div>--}}
@endsection
