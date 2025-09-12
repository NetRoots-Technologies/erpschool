<div class="col-xs-6 form-group">
    {!! Form::label('name', 'Name*', ['class' => 'control-label']) !!}
    {!! Form::text('name', old('name'), ['class' => 'form-control', 'maxlength' => 200, 'required' => 'required']) !!}
    @if($errors->has('name'))
        <span class="help-block">
            {{ $errors->first('name') }}
        </span>
    @endif
</div>

<div class="col-xs-6 form-group">
    <label for="balance" class="control-label">Opening Balance</label>
    
    @php
        $openingBalance = isset($group->ledgers) ? $group->ledgers->opening_balance : null;
    @endphp

    <input type="number" name="balance" id="balance"
        value="{{ old('balance', $openingBalance) }}"
        class="form-control" step="0.01" min="0">

    @if($errors->has('balance'))
        <span class="help-block text-danger">
            {{ $errors->first('balance') }}
        </span>
    @endif
</div>


<div class="col-xs-12 form-group">
        {!! Form::label('parent_id', 'Parent Group', ['class' => 'control-label']) !!}
        <select name="parent_id" id="parent_id" class="form-control select2" style="width: 100%;" required>
            <option value="" selected disabled> Select a Parent Group </option>
                {!! $Groups !!}
        </select>
        <span id="parent_id_handler"></span>
        @if($errors->has('parent_id'))
                <span class="help-block">
                        {{ $errors->first('parent_id') }}
                </span>
        @endif
</div>
