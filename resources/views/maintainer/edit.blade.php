

@extends('admin.layouts.main')
@section('title')
    {{__('Update Maintainer')}}
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Update Maintainer</h5>
            </div>
            <div class="card-body">

                {{ Form::model($maintainer, array('route' => array('maintainer.maintainer.update', $maintainer->id), 'method' => 'PUT','enctype' => "multipart/form-data")) }}
                
                <div class="form-row">
                    <div class="form-group col-md-12 col-lg-12">
                    {{Form::label('first_name',__('Name'),array('class'=>'form-label'))}}
                    {{Form::text('first_name',$user->name,array('class'=>'form-control','placeholder'=>__('Enter Name')))}}
                    </div>
                </div>


            
                <div class="form-row">

                    <div class="form-group col-md-6 col-lg-6 ">
            {{Form::label('email',__('Email'),array('class'=>'form-label'))}}
            {{Form::text('email',$user->email,array('class'=>'form-control','placeholder'=>__('Enter Email')))}}
                    </div>
                   

                <div class="form-group col-md-6 col-lg-6">
                    {{ Form::label('branch_id', __('Branch'), ['class'=>'form-label']) }}
                    {{ Form::select('branch_id', $branches, $maintainer->branch_id, ['class'=>'form-control','id'=>'branch_id']) }}
                   
                </div>
                    
                </div>

                
                
                <div class="form-row">

                <div class="form-group col-md-6 col-lg-6">
                    {{ Form::label('type_id', __('Type'), ['class'=>'form-label']) }}
                    {{ Form::select('type_id', $types, $maintainer->type_id, ['class'=>'form-control']) }}
                </div>

                </div>

                

                <div class="form-group text-end">
                    <button type="submit" class="btn btn-info">Update</button>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('#branch_id').select2({
        placeholder: "Select Properties",
        allowClear: true,
        width: '100%'
    });
});
</script>
@endsection




