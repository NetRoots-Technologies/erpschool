@extends('admin.layouts.main')

@section('title')
    Agent
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Agent</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.agent.index') !!}" class="btn btn-primary btn-sm "> Back </a>
                            </div>
                        </div>
                        {{--                        @dd($roles)--}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="w-100">
                            <form action="{!! route('hr.agent.update',$agent->id) !!}" enctype="multipart/form-data"
                                  method="post">
                                @csrf
                              @method('PUT')
                                <div class="box-body" style="margin-top:50px;">
                                    <input type="hidden" name="id" value="{{$agent->id}}">
                                    <h5>Agent Data</h5>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="name">Agent Name*</label>
                                            <input name="name" type="text" class="form-control" value="{{$agent->name}}" required/>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="f_name">Email*</label>
                                            <input name="email" type="email" class="form-control" value="{{$agent->email}}" required/>
                                        </div>
                                    </div>
                                    <div class="row mt-2">

                                        <div class="col-lg-6">
                                            <label for="address">Address*</label>
                                            <input name="address" type="text" class="form-control" value="{{$agent->address}}" required/>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="mobile">Mobile No.*</label>
                                            <input name="mobile" type="text" class="form-control" value="{{$agent->mobile}}" required/>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="comission">Comission (%) *</label>
                                            <input name="comission" type="text" class="form-control" value="{{$agent->comission}}" required/>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="image" class="form-label">Image Upload</label>
                                            <img src="{!! asset($agent->image) !!}" >
                                            <input name="image" class="form-control" type="file" id="formFile">
                                        </div>
                                    </div>
                                    <div class=" row mt-5 mb-3">
                                        <div class="col-12">
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                <a href="{!! route('hr.agent.index') !!}"
                                                   class=" btn btn-sm btn-danger">Cancel </a>
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
    </div>
@stop
@section('css')


@endsection
@section('js')

    <script>

        SSSSSSS
    </script>


@endsection

