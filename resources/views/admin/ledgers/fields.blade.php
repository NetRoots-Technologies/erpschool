<div class="row">
    <div class="form-group col-md-6 @if($errors->has('name')) has-error @endif">
        {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
        {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'maxlength' => 250, 'required' => 'required']) !!}

        @if($errors->has('name'))
        <span class="help-block">
            {{ $errors->first('name') }}
        </span>
        @endif
    </div>
</div>
@if(isset($LedgerCurrencies))
@foreach($LedgerCurrencies as $ledgerCurr)
<div class="row">
    <div class="form-group col-md-3">
        <select name="currency_id[]" class="form-control" required>
            {!! \App\Helpers\Currency::currencyList($ledgerCurr->currency_id) !!}
        </select>
    </div>
    <div class="form-group col-md-3">
        <select name="balance_type[]" class="form-control" required>
            {!! \App\Helpers\CoreAccounts::dr_cr($ledgerCurr->balance_type) !!}
        </select>
    </div>
    <div class="form-group col-md-3">
        <input type="number" name="amount[]" class="form-control" value="{{ $ledgerCurr->amount }}" required>
    </div>
</div>
@endforeach
@endif
<div class="row">
    <div class="form-group col-md-3">
        <label class="control-label">Currency</label>
        <select name="currency_id[]" class="form-control" required>
            {!! \App\Helpers\Currency::currencyList() !!}
        </select>
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Balance Type</label>
        <select name="balance_type[]" class="form-control" required>
            {!! \App\Helpers\CoreAccounts::dr_cr() !!}
        </select>
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Opening Balance</label>
        <input type="number" name="amount[]" class="form-control" value="{{ old('amount') }}" required>
    </div>
    <div class="col-md-1">
        <label>More</label>
        <button type="button" class="btn btn-primary" onclick="more_ob()"><i class="fa fa-plus"></i></button>
    </div>
</div>
<div id="add-more"></div>
<!--==============================================================================Previous code-->
<!--<div class="row">
<div class="form-group col-md-4 @if($errors->has('balance_type')) has-error @endif">
    {!! Form::label('balance_type', 'Balance Type(PKR)', ['class' => 'control-label']) !!}
    {!! Form::select('balance_type', array('d' => 'Dr.', 'c' => 'Cr.'), old('balance_type'), ['class' => 'form-control']) !!}
    @if($errors->has('balance_type'))
        <span class="help-block">
            {{ $errors->first('balance_type') }}
        </span>
    @endif
</div>
<div class="form-group col-md-4 @if($errors->has('opening_balance')) has-error @endif">
    {!! Form::label('opening_balance', 'Opening Balance(PKR)', ['class' => 'control-label']) !!}
    {!! Form::text('opening_balance', old('opening_balance'), ['class' => 'form-control' , 'pattern'=> '[0-9]+','maxlength'=>10,'required']) !!}
    @if($errors->has('opening_balance'))
        <span class="help-block">
            {{ $errors->first('opening_balance') }}
        </span>
    @endif
</div>
<div class="form-group col-md-4 @if($errors->has('dl_balance_type')) has-error @endif">
    {!! Form::label('dl_balance_type', 'Balance Type($)', ['class' => 'control-label']) !!}
    {!! Form::select('dl_balance_type', array('d' => 'Dr.', 'c' => 'Cr.'), old('dl_balance_type'), ['class' => 'form-control']) !!}
    @if($errors->has('dl_balance_type'))
        <span class="help-block">
            {{ $errors->first('dl_balance_type') }}
        </span>
    @endif
</div>
</div>
<div class="row">
<div class="form-group col-md-4 @if($errors->has('dl_opening_balance')) has-error @endif">
    {!! Form::label('dl_opening_balance', 'Opening Balance($)', ['class' => 'control-label']) !!}
    {!! Form::text('dl_opening_balance', old('dl_opening_balance'), ['class' => 'form-control' , 'pattern'=> '[0-9]+','maxlength'=>10,'required']) !!}
    @if($errors->has('dl_opening_balance'))
        <span class="help-block">
            {{ $errors->first('dl_opening_balance') }}
        </span>
    @endif
</div>
<div class="form-group col-md-4 @if($errors->has('gl_balance_type')) has-error @endif">
    {!! Form::label('gl_balance_type', 'Balance Type(Gold)', ['class' => 'control-label']) !!}
    {!! Form::select('gl_balance_type', array('d' => 'Dr.', 'c' => 'Cr.'), old('gl_balance_type'), ['class' => 'form-control']) !!}
    @if($errors->has('gl_balance_type'))
        <span class="help-block">
            {{ $errors->first('gl_balance_type') }}
        </span>
    @endif
</div>
<div class="form-group col-md-4 @if($errors->has('gl_opening_balance')) has-error @endif">
    {!! Form::label('gl_opening_balance', 'Opening Balance(Gold)', ['class' => 'control-label']) !!}
    {!! Form::text('gl_opening_balance', old('gl_opening_balance'), ['class' => 'form-control' , 'pattern'=> '[0-9]+','maxlength'=>10,'required']) !!}
    @if($errors->has('gl_opening_balance'))
        <span class="help-block">
            {{ $errors->first('gl_opening_balance') }}
        </span>
    @endif
</div>
</div>
{{--<div class="form-group col-md-4 @if($errors->has('closing_balance')) has-error @endif">--}}
    {{--{!! Form::label('closing_balance', 'Closing Balance', ['class' => 'control-label']) !!}--}}
    {{--{!! Form::number('closing_balance', old('closing_balance'), ['readonly' => true, 'class' => 'form-control']) !!}--}}
    {{--@if($errors->has('closing_balance'))--}}
        {{--<span class="help-block">--}}
            {{--{{ $errors->first('closing_balance') }}--}}
        {{--</span>--}}
    {{--@endif--}}
{{--</div>--}}-->
<div class="row">
    <div class="form-group col-md-10 @if($errors->has('group_id')) has-error @endif">
        {!! Form::label('group_id', 'Group*', ['class' => 'control-label']) !!}
        <select name="group_id" id="group_id" class="form-control select2" style="width: 100%;" required>
            <option value="" selected disabled> Please select a Group </option>
            {!! $Groups !!}
        </select>
        @if($errors->has('group_id'))
        <span class="help-block">
            {{ $errors->first('group_id') }}
        </span>
        @endif
    </div>
</div>

