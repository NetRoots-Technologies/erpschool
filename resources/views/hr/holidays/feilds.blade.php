<div class="form-group col-md-3 @if($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
    {!! Form::text('name', old('name'), ['class' => 'form-control','data-parsley-required'=> 'true', 'data-parsley-trigger'=> 'change focusout','data-parsley-pattern'=> '/^[a-zA-Z ]*$/', 'data-parsley-minlength'=> '2']) !!}
    @if($errors->has('name'))
        <span class="help-block">
            {{ $errors->first('name') }}
        </span>
    @endif
</div>

<div class="form-group col-md-3 @if($errors->has('shot_name')) has-error @endif">
    {!! Form::label('shot_name', 'Abbreviation*', ['class' => 'control-label']) !!}
    {!! Form::text('shot_name', old('shot_name'), ['class' => 'form-control','data-parsley-required'=> 'true', 'data-parsley-trigger'=> 'change focusout','data-parsley-pattern'=> '/^[a-zA-Z ]*$/', 'data-parsley-minlength'=> '2']) !!}
    @if($errors->has('shot_name'))
        <span class="help-block">
            {{ $errors->first('shot_name') }}
        </span>
    @endif
</div>

<div class="form-group col-md-3 @if($errors->has('holiday_date')) has-error @endif">
    {!! Form::label('holiday_date', 'From Date*', ['class' => 'control-label']) !!}
    {!! Form::text('holiday_date', old('holiday_date'), ['class' => 'form-control','required']) !!}
    @if($errors->has('holiday_date'))
        <span class="help-block">
                        {{ $errors->first('holiday_date') }}
                </span>
    @endif
</div>


<div class="form-group col-md-3 @if($errors->has('holiday_date_to')) has-error @endif">
    {!! Form::label('holiday_date_to', 'To Date*', ['class' => 'control-label']) !!}
    {!! Form::text('holiday_date_to', old('holiday_date_to'), ['class' => 'form-control','required']) !!}
    @if($errors->has('holiday_date_to'))
        <span class="help-block">
                        {{ $errors->first('holiday_date_to') }}
                </span>
    @endif
</div>

<div class="form-group col-md-3 @if($errors->has('is_recurring')) has-error @endif">
    {!! Form::label('is_recurring', 'Repeats Annually', ['class' => 'control-label']) !!}<br/>
    {{ Form::checkbox('is_recurring', 1, old('is_recurring')) }}
    @if($errors->has('is_recurring'))
        <span class="help-block">
                        {{ $errors->first('is_recurring') }}
                </span>
    @endif
</div>

<div class="form-group col-md-3 @if($errors->has('holiday_length')) has-error @endif">
    {!! Form::label('holiday_length', 'Holiday Length*', ['class' => 'control-label']) !!}
    {!! Form::select('holiday_length', array('' => 'Select a Length') + Config::get('hrm.working_full_half_array'), old('holiday_length'), ['class' => 'form-control','required']) !!}
    @if($errors->has('holiday_length'))
        <span class="help-block">
                        {{ $errors->first('holiday_length') }}
                </span>
    @endif
</div>
