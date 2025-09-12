<div class="form-group col-md-4  @if($errors->has('number')) has-error @endif">
    {!! Form::label('number', 'Number*', ['class' => 'control-label']) !!}
    {!! Form::text('number', ($VoucherData['number']) ? $VoucherData['number'] : '??????', ['class' => 'form-control', 'readonly' => 'true']) !!}
    @if($errors->has('number'))
        <span class="help-block">
            {{ $errors->first('number') }}
        </span>
    @endif
</div>
<div class="form-group col-md-4  @if($errors->has('voucher_date')) has-error @endif">
    {!! Form::label('number', 'Voucher Date*', ['class' => 'control-label']) !!}
    {!! Form::text('voucher_date', ($VoucherData['voucher_date']) ? $VoucherData['voucher_date'] : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
    @if($errors->has('voucher_date'))
        <span class="help-block">
            {{ $errors->first('voucher_date') }}
        </span>
    @endif
</div>

<div class="form-group col-md-4 @if($errors->has('entry_type_id')) has-error @endif">
    {!! Form::label('entry_type_id', 'Entry Type*', ['class' => 'control-label']) !!}
    {!! Form::select('entry_type_id', Config::get('admin.entry_type'), $VoucherData['entry_type_id'], ['id'=>'entry_type_id','style' => 'width: 100%;', 'class' => 'form-control select2','required']) !!}
    <span id="entry_type_id_handler"></span>
    @if($errors->has('entry_type_id'))
        <span class="help-block">
            {{ $errors->first('entry_type_id') }}
        </span>
    @endif
</div>
<div class="row">
<div class="form-group col-md-4 @if($errors->has('currence_type')) has-error @endif">
    {!! Form::label('currence_type', 'Currency*', ['class' => 'control-label']) !!}
{!! Form::select('currence_type', $Currencies, $VoucherData['currence_type'], ['id'=>'currence_type','style' => 'width: 100%;', 'class' => 'form-control currency_type select2','required']) !!}
    <span id="currence_type_handler"></span>
    @if($errors->has('currence_type'))
    <span class="help-block">
{{ $errors->first('currence_type') }}
        </span>
    @endif
    </div>
</div>



<div class="row">

</div>
<!-- Slip Area Started -->
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="float-start header"><i class="fa fa-th"></i> Entry Items</li>
        <li class="float-end"><a href="#tab_2" data-toggle="tab"><u>P</u>arameters</a></li>
        <li class="active float-end"><a href="#tab_1" data-toggle="tab"><u>B</u>asic</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <button onclick="FormControls.createEntryItem();" type="button" style="margin-bottom: 5px;"
                    class="btn pull-right mt-1 btn-sm btn-flat btn-primary"><i class="fa fa-plus"></i>&nbsp;Add <u>R</u>ow
            </button>
            <table class="table table-condensed" id="entry_items">
                <thead>
                <tr>
                    <th width="30%">Account</th>
{{--                    <th width="12%">Inv List</th>--}}
                    <th width="15%">Currency</th>
                    <th width="10%">Debit</th>
                    <th width="10%">Credit</th>
                    <th width="20%">Narration</th>
                    <th width="4%">Action</th>
                </tr>
                </thead>
                <tbody>
                @if(count($VoucherData['entry_items']['counter']))

                    @foreach ($VoucherData['entry_items']['counter'] as $key => $val)

                        <tr id="entry_item-{{ $val }}">
                            <td colspan="">
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {!! Form::hidden("entry_items[counter][$val]", $val, ['id' => "entry_item-counter-$val", 'class' => "entry_item-counter-$val "]) !!}
                                    {!! Form::select("entry_items[ledger_id][$val]", ($VoucherData['entry_items']['ledger_id'][$val]) ? array($VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->id => $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->number . ' - ' . $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->name) : array(), $VoucherData['entry_items']['ledger_id'][$val], ['id' => "entry_item-ledger_id-$val", 'style' => 'width: 100%;', 'class' => 'form-control description-data-ajax select2']) !!}
                                </div>
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {!! Form::number("entry_items[dr_amount][$val]", $VoucherData['entry_items']['dr_amount'][$val], [
                                    'onkeydown' => 'FormControls.CalculateTotal();',
                                    'onkeyup' => 'FormControls.CalculateTotal();',
                                    'onblur' => 'FormControls.CalculateTotal();',
                                    'id' => "entry_item-dr_amount-$val",
                                    'class' => 'form-control entry_items-dr_amount',
                                    'placeholder' => 'Dr. Amount',
                                    ]) !!}
                                </div>
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {!! Form::number("entry_items[cr_amount][$val]", $VoucherData['entry_items']['cr_amount'][$val], [
                                    'onkeydown' => 'FormControls.CalculateTotal();',
                                    'onkeyup' => 'FormControls.CalculateTotal();',
                                    'onblur' => 'FormControls.CalculateTotal();',
                                    'id' => "entry_item-cr_amount-$val",
                                    'class' => 'form-control entry_items-cr_amount',
                                    'placeholder' => 'Cr. Amount',
                                    ]) !!}
                                </div>
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {!! Form::text("entry_items[narration][$val]", $VoucherData['entry_items']['narration'][$val], [
                                    'id' => "entry_item-narration-$val",
                                    'class' => 'form-control entry_items-narration',
                                    'placeholder' => 'Narration',
                                    ]) !!}
                                </div>
                            </td>
                            <td>
                                <button onclick="FormControls.destroyEntryItem('{{$val}}');"
                                        id="entry_item-del_btn-{{$val}}" type="button"
                                        class="btn btn-block btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr id="entry_item-1">
                        <td colspan="">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                {!! Form::hidden('entry_items[counter][1]', '1', ['id' => "entry_item-counter-1", 'class' => 'entry_item-counter-1']) !!}
                                {!! Form::select('entry_items[ledger_id][1]', array(), old('branch_id'), ['id' => 'entry_item-ledger_id-1', 'style' => 'width: 100%;', 'class' => 'form-control description-data-ajax entry_item select2']) !!}
                            </div>
                        </td>
{{--                        <td>--}}
{{--                            <div class="form-group">--}}
{{--                                <select class="form-control invList">--}}
{{--                                    <option value="">Select Inv</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </td>--}}
                        <td>
                            <div class="form-group">
                                <select class="form-control" name='entry_items[currency_id][1]'>
                                    {!! \App\Helpers\Currency::currencyList(1) !!}
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                {!! Form::number('entry_items[dr_amount][1]', null, [
                                'onkeydown' => 'FormControls.CalculateTotal();',
                                'onkeyup' => 'FormControls.CalculateTotal();',
                                'onblur' => 'FormControls.CalculateTotal();',
                                'id' => 'entry_item-dr_amount-1',
                                'class' => 'form-control entry_items-dr_amount',
                                'placeholder' => 'Dr. Amount'
                                ]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                {!! Form::number('entry_items[cr_amount][1]', null, [
                                'onkeydown' => 'FormControls.CalculateTotal();',
                                'onkeyup' => 'FormControls.CalculateTotal();',
                                'onblur' => 'FormControls.CalculateTotal();',
                                'id' => 'entry_item-cr_amount-1',
                                'class' => 'form-control entry_items-cr_amount',
                                'placeholder' => 'Cr. Amount'
                                ]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                {!! Form::text('entry_items[narration][1]', null, [
                                'id' => 'entry_item-narration-1',
                                'class' => 'form-control entry_items-narration',
                                'placeholder' => 'Narration'
                                ]) !!}
                                <input type="text" class="form-control input-sm balance_detail" style="margin-top: 5px;"
                                       placeholder="Balance Information" disabled>
                            </div>
                        </td>
                        <td>
                            <button onclick="FormControls.destroyEntryItem('1');" id="entry_item-del_btn-1"
                                    type="button" class="btn btn-block btn-danger btn-sm"><i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td align="right" style="padding-top: 12px;" width="12%"><b>Difference</b></td>
                    <td width="12%">
                        <div class="form-group @if($errors->has('diff_total')) has-error @endif">
                            {!! Form::number('diff_total', old('diff_total', 0.00), ['id' => 'diff_total', 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
                            @if($errors->has('diff_total'))
                                <span class="help-block">
                                        {{ $errors->first('diff_total') }}
                                    </span>
                            @endif
                        </div>
                    </td>
                    <td align="right" style="padding-top: 12px;" width="12%"><b>Total</b></td>
                    <td>
                        <div class="form-group @if($errors->has('dr_total')) has-error @endif">
                            {!! Form::number('dr_total', old('dr_total', 0.00), ['id' => 'dr_total', 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
                            @if($errors->has('dr_total'))
                                <span class="help-block">
                                        {{ $errors->first('dr_total') }}
                                    </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-group @if($errors->has('cr_total')) has-error @endif">
                            {!! Form::number('cr_total', old('cr_total', 0.00), ['id' => 'cr_total', 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
                            @if($errors->has('cr_total'))
                                <span class="help-block">
                                        {{ $errors->first('cr_total') }}
                                    </span>
                            @endif
                        </div>
                    </td>
                    <td colspan="2"></td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" id="entry_item-global_counter"
                   value="@if(count($VoucherData['entry_items']['counter'])){{ count($VoucherData['entry_items']['counter']) }}@else{{'1'}}@endif"/>
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_2">
            <div class="row">
                <div class="form-group col-md-6 @if($errors->has('cheque_no')) has-error @endif">
                    {!! Form::label('cheque_no', 'Cheque #', ['class' => 'control-label']) !!}
                    {!! Form::text('cheque_no', $VoucherData['cheque_no'], ['class' => 'form-control']) !!}
                    @if($errors->has('cheque_no'))
                        <span class="help-block">
                        {{ $errors->first('cheque_no') }}
                    </span>
                    @endif
                </div>
                <div class="form-group col-md-6 @if($errors->has('cheque_date')) has-error @endif">
                    {!! Form::label('cheque_date', 'Cheque Date', ['class' => 'control-label']) !!}
                    {!! Form::text('cheque_date', ($VoucherData['cheque_date']) ? $VoucherData['cheque_date'] : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                    @if($errors->has('cheque_date'))
                        <span class="help-block">
                        {{ $errors->first('cheque_date') }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 @if($errors->has('invoice_no')) has-error @endif">
                    {!! Form::label('invoice_no', 'Invoice #', ['class' => 'control-label']) !!}
                    {!! Form::text('invoice_no', $VoucherData['invoice_no'], ['class' => 'form-control']) !!}
                    @if($errors->has('invoice_no'))
                        <span class="help-block">
                        {{ $errors->first('invoice_no') }}
                    </span>
                    @endif
                </div>
                <div class="form-group col-md-6 @if($errors->has('invoice_date')) has-error @endif">
                    {!! Form::label('invoice_date', 'Invoice Date*', ['class' => 'control-label']) !!}
                    {!! Form::text('invoice_date', ($VoucherData['invoice_date']) ? $VoucherData['invoice_date'] : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                    @if($errors->has('invoice_date'))
                        <span class="help-block">
                        {{ $errors->first('invoice_date') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
