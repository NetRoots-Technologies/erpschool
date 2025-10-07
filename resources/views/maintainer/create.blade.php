

@extends('admin.layouts.main')
@section('title')
    {{__('Create Maintainer')}}
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
               
                <h5>Create Maintainer</h5>
            </div>
            <div class="card-body">

                {!! Form::open(['route' => 'maintainer.maintainer.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                    
                <div class="form-row">
                    <div class="form-group col-md-6 col-lg-6">
                        {{ Form::label('first_name', __('First Name'), ['class'=>'form-label']) }}
                        {{ Form::text('first_name', null, ['class'=>'form-control','placeholder'=>__('Enter First Name')]) }}
                    </div>

                    <div class="form-group col-md-6 col-lg-6">
                        {{ Form::label('last_name', __('Last Name'), ['class'=>'form-label']) }}
                        {{ Form::text('last_name', null, ['class'=>'form-control','placeholder'=>__('Enter Last Name')]) }}
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6 col-lg-6">
                        {{ Form::label('email', __('Email'), ['class'=>'form-label']) }}
                        {{ Form::text('email', null, ['class'=>'form-control','placeholder'=>__('Enter Email')]) }}
                    </div>

                    <div class="form-group col-md-6 col-lg-6">
                        {{ Form::label('password', __('Password'), ['class'=>'form-label']) }}
                        {{ Form::password('password', ['class'=>'form-control','placeholder'=>__('Enter Password')]) }}
                    </div>
                </div>

                {{-- <div class="form-group">
                    {{ Form::label('phone_number', __('Phone Number'), ['class'=>'form-label']) }}
                    {{ Form::text('phone_number', null, ['class'=>'form-control','placeholder'=>__('Enter Phone Number')]) }}
                </div> --}}

                <div class="form-group">
                    {{ Form::label('branch_id', __('Branch'), ['class'=>'form-label']) }}
                    {{ Form::select('branch_id', $branches, null, ['class'=>'form-control','id'=>'branch_id']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('type_id', __('Type'), ['class'=>'form-label']) }}
                    {{ Form::select('type_id', $types, null, ['class'=>'form-control']) }}
                </div>

                {{-- <div class="form-group">
                    {{ Form::label('profile', __('Profile'), ['class'=>'form-label']) }}
                    {{ Form::file('profile', ['class'=>'form-control']) }}
                </div> --}}

                <div class="form-group text-end">
                    <button type="submit" class="btn btn-success">Save</button>
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


