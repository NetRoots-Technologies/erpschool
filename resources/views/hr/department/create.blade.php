@extends('admin.layouts.main')

@section('title')
    Department Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Department</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.department.index') !!}" class="btn btn-primary btn-md"> Back </a>
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
                        <form action="{!! route('hr.department.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            <div class="w-100">

                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="name"> Name <b>*</b> </label>
                                            <input required name="name" id="name" type="text" class="form-control"
                                                   value="{{old('name')}}"/>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="working_hours"> Status </label>
                                            <select class="form-control" name="status">
                                                <option value="1">Active</option>
                                                <option value="2">DeActive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr style="background-color: darkgray">
                                <div class="row mt-8 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">

                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            <a href="{!! route('hr.department.index') !!}"
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

