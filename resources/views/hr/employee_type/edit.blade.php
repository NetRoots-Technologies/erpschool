@extends('admin.layouts.main')

@section('title')
    Employee Type Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Employee Type</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.employee_type.index') !!}" class="btn btn-primary btn-sm "> Back </a>
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
                        <form action="{!! route('hr.employee_type.update',$employee->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            <div class="w-100">

                                @csrf
                                @method('Put')
                                <div class="box-body" style="margin-top:50px;">
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="name"> Name <b>*</b> </label>
                                            <input required name="name" id="name" type="text" class="form-control"
                                                   value="{!! $employee->name !!}"/>
                                        </div>
                                    </div><div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="working_hours"> Working Hours (by month) </label>
                                            <input required name="working_hours" id="working_hours" type="text" class="form-control"
                                                   value="{!! $employee->working_hours !!}"/>
                                        </div>
                                    </div>
                                </div>

                                <hr style="background-color: darkgray">
                                <div class="row mt-8 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">

                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            <a href="{!! route('hr.employee_type.index') !!}"
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

