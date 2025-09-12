@extends('admin.layouts.main')

@section('title')
    Agent Commission Slabs
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Agent Commission Slabs
                        </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.agent_comission.index') !!}" class="btn btn-primary btn-sm "> Back </a>
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
                        <form action="{!! route('hr.agent_comission.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            <div class="w-100">
                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="slab_name"> Slab Name <b>*</b> </label>
                                            <input required name="slab_name" id="slab_name" type="text" class="form-control"
                                                   value="{{old('slab_name')}}"/>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="agent_type_id"> Agent Type </label>
                                            <select required id="agent_type_id"
                                                    class="select2 form-control" name="agent_type_id">
                                                <option value="">Select Agent Type</option>
                                                @foreach ($agent_type as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="min"> Minimum</label>
                                            <input required name="min" id="min" type="text" class="form-control"
                                                   value="{{old('min')}}"/>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="max"> Maximum </label>
                                            <input required name="max" id="max" type="text" class="form-control"
                                                   value="{{old('agent_type_id')}}"/>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="comission"> Comission</label>
                                            <input required name="comission" id="comission" type="text" class="form-control"
                                                   value="{{old('comission')}}"/>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="slab_type"> Slab Type </label>
                                            <select required name="slab_type" id="slab_type" type="text" class="form-control"
                                                   value="{{old('slab_type')}}">
                                                <option value="">Select Slab Type</option>
                                                <option value="1">Comission</option>
                                                <option value="2">Recovery</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr style="background-color: darkgray">
                                <div class="row mt-8 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">

                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            <a href="{!! route('hr.agent_comission.index') !!}"
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
@stop
@section('css')


@endsection
@section('js')



@endsection

