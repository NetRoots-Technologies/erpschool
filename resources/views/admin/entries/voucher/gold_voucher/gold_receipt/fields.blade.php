<div class="form-group col-md-2  @if($errors->has('number')) has-error @endif">
    {!! Form::label('number', 'Number*', ['class' => 'control-label']) !!}
    {!! Form::text('number', ($VoucherData['number']) ? $VoucherData['number'] : '?????', ['class' => 'form-control', 'readonly' => 'true']) !!}
    @if($errors->has('number'))
        <span class="help-block">
            {{ $errors->first('number') }}
        </span>
    @endif
</div>
<div class="form-group col-md-2  @if($errors->has('voucher_date')) has-error @endif">
    {!! Form::label('number', 'Voucher Date*', ['class' => 'control-label']) !!}
    {!! Form::text('voucher_date', ($VoucherData['voucher_date']) ? $VoucherData['voucher_date'] : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
    @if($errors->has('voucher_date'))
        <span class="help-block">
            {{ $errors->first('voucher_date') }}
        </span>
    @endif
</div>
<div class="col-md-3 form-group">
    <label>Cash In Hand</label>
    <select class="form-control" name="trans_acc_to">
        {!! \App\Helpers\CoreAccounts::cash_in_hand() !!}
    </select>
</div>
<div class="col-md-3 form-group">
    <label style="margin: 10px;"><input type="radio" name="gold_type" value="swiss" style="margin-right: 10px;" />Swiss-Refine</label>
    <label><input type="radio" name="gold_type" value="refine" style="margin-right: 10px;" />Refine-Swiss</label>
</div>
{{--<div class="form-group col-md-2 @if($errors->has('branch_id')) has-error @endif">--}}
    {{--{!! Form::label('branch_id', 'Branch*', ['class' => 'control-label']) !!}--}}
    {{--{!! Form::select('branch_id', $Branches, $VoucherData['branch_id'], ['style' => 'width: 100%;', 'class' => 'form-control select2']) !!}--}}
    {{--<span id="branch_id_handler"></span>--}}
    {{--@if($errors->has('branch_id'))--}}
        {{--<span class="help-block">--}}
            {{--{{ $errors->first('branch_id') }}--}}
        {{--</span>--}}
    {{--@endif--}}
{{--</div>--}}
{{--<div class="form-group col-md-3 @if($errors->has('employee_id')) has-error @endif">--}}
    {{--{!! Form::label('employee_id', 'Exmployee*', ['class' => 'control-label']) !!}--}}
    {{--{!! Form::select('employee_id', $Employees, $VoucherData['employee_id'], ['style' => 'width: 100%;', 'class' => 'form-control select2']) !!}--}}
    {{--<span id="employee_id_handler"></span>--}}
    {{--@if($errors->has('employee_id'))--}}
        {{--<span class="help-block">--}}
            {{--{{ $errors->first('employee_id') }}--}}
        {{--</span>--}}
    {{--@endif--}}
{{--</div>--}}
{{--<div class="form-group col-md-3 @if($errors->has('department_id')) has-error @endif">--}}
    {{--{!! Form::label('department_id', 'Department*', ['class' => 'control-label']) !!}--}}
    {{--{!! Form::select('department_id', $Departments, $VoucherData['department_id'], ['style' => 'width: 100%;', 'class' => 'form-control select2','required']) !!}--}}
    {{--<span id="department_id_handler"></span>--}}
    {{--@if($errors->has('department_id'))--}}
        {{--<span class="help-block">--}}
            {{--{{ $errors->first('department_id') }}--}}
        {{--</span>--}}
    {{--@endif--}}
{{--</div>--}}
<div class="row"></div>
{{--<div class="form-group col-md-12  @if($errors->has('remarks')) has-error @endif">--}}
    {{--{!! Form::label('remarks', 'Remarks', ['class' => 'control-label']) !!}--}}
    {{--{!! Form::text('remarks', $VoucherData['remarks'], ['class' => 'form-control']) !!}--}}
    {{--@if($errors->has('remarks'))--}}
        {{--<span class="help-block">--}}
            {{--{{ $errors->first('remarks') }}--}}
        {{--</span>--}}
    {{--@endif--}}
{{--</div>--}}
{{--<div class="form-group col-md-12  @if($errors->has('narration')) has-error @endif">--}}
    {{--{!! Form::label('narration', 'Narration*', ['class' => 'control-label']) !!}--}}
    {{--{!! Form::text('narration', $VoucherData['narration'], ['id' => 'narration', 'onkeydown' => 'FormControls.updateNarration();', 'onkeyup' => 'FormControls.updateNarration();', 'onblur' => 'FormControls.updateNarration();', 'class' => 'form-control']) !!}--}}
    {{--@if($errors->has('narration'))--}}
        {{--<span class="help-block">--}}
            {{--{{ $errors->first('narration') }}--}}
        {{--</span>--}}
    {{--@endif--}}
{{--</div>--}}
{{--<div class="form-group col-md-12  @if($errors->has('base_item')) has-error @endif">--}}
    {{--<table class="table table-condensed">--}}
        {{--<tbody>--}}
            {{--<tr>--}}
                {{--@if(count($VoucherData['entry_items']['counter']))--}}
                    {{--@foreach ($VoucherData['entry_items']['counter'] as $key => $val)--}}
                        {{--@if ($key != 1)--}}
                            {{--@break--}}
                        {{--@endif--}}

                        {{--<td width="60%">--}}
                            {{--<div class="form-group" style="margin-bottom: 0px !important;">--}}
                                {{--{!! Form::label('narration', 'Cash A/C*', ['class' => 'control-label']) !!}--}}
                                {{--{!! Form::hidden("entry_items[counter][$val]", $val, ['id' => "entry_item-counter-$val", 'class' => "entry_item-counter-$val"]) !!}--}}
                                {{--{!! Form::select("entry_items[ledger_id][$val]", ($VoucherData['entry_items']['ledger_id'][$val]) ? array($VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->id => $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->number . ' - ' . $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->name) : array(), $VoucherData['entry_items']['ledger_id'][$val], ['id' => "entry_item-ledger_id-$val", 'style' => 'width: 100%;', 'class' => 'form-control base-data-ajax']) !!}--}}
                            {{--</div>--}}
                            {{--{!! Form::hidden("entry_items[dr_amount][$val]", $VoucherData['entry_items']['dr_amount'][$val], [--}}
                                {{--'id' => "entry_item-dr_amount-$val",--}}
                                {{--'class' => 'form-control entry_items-dr_amount',--}}
                            {{--]) !!}--}}
                            {{--{!! Form::hidden("entry_items[cr_amount][$val]", $VoucherData['entry_items']['cr_amount'][$val], [--}}
                                {{--'id' => "entry_item-cr_amount-$val",--}}
                                {{--'class' => 'form-control entry_items-cr_amount',--}}
                            {{--]) !!}--}}
                            {{--{!! Form::hidden("entry_items[narration][$val]", $VoucherData['narration'], [--}}
                                {{--'id' => "entry_item-narration-$val",--}}
                                {{--'class' => 'form-control entry_items-narration',--}}
                            {{--]) !!}--}}
                        {{--</td>--}}
                    {{--@endforeach--}}
                {{--@else--}}
                    {{--<td width="60%">--}}
                        {{--<div class="form-group" style="margin-bottom: 0px !important;">--}}
                            {{--{!! Form::label('narration', 'Cash A/C*', ['class' => 'control-label']) !!}--}}
                            {{--{!! Form::hidden('entry_items[counter][1]', '1', ['id' => "entry_item-counter-1", 'class' => 'entry_item-counter-1']) !!}--}}
                            {{--{!! Form::select('entry_items[ledger_id][1]', array(), null, ['id' => 'entry_item-ledger_id-1', 'style' => 'width: 100%;', 'class' => 'form-control base-data-ajax']) !!}--}}
                        {{--</div>--}}
                        {{--{!! Form::hidden('entry_items[dr_amount][1]', 0, [--}}
                                {{--'id' => 'entry_item-dr_amount-1',--}}
                                {{--'class' => 'form-control entry_items-dr_amount',--}}
                        {{--]) !!}--}}
                        {{--{!! Form::hidden('entry_items[cr_amount][1]', 0, [--}}
                                {{--'id' => 'entry_item-cr_amount-1',--}}
                                {{--'class' => 'form-control entry_items-cr_amount',--}}
                        {{--]) !!}--}}
                        {{--{!! Form::hidden('entry_items[narration][1]', $VoucherData['narration'], [--}}
                                {{--'id' => 'entry_item-narration-1',--}}
                                {{--'class' => 'form-control entry_items-narration',--}}
                        {{--]) !!}--}}
                    {{--</td>--}}
                {{--@endif--}}

                    {{--<td width="20%">--}}
                        {{--<div class="form-group @if($errors->has('dr_total')) has-error @endif" style="margin-bottom: 0px !important;">--}}
                            {{--{!! Form::label('dr_total', 'Cash Total', ['class' => 'control-label']) !!}--}}
                            {{--{!! Form::number('dr_total', old('dr_total', 0.00), ['id' => 'dr_total', 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}--}}
                            {{--@if($errors->has('dr_total'))--}}
                                {{--<span class="help-block">--}}
                                {{--{{ $errors->first('dr_total') }}--}}
                            {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</td>--}}

                {{--<td width="20%">--}}
                    {{--<div class="form-group @if($errors->has('diff_total')) has-error @endif" style="margin-bottom: 0px !important;">--}}
                        {{--{!! Form::label('diff_total', 'Total Difference', ['class' => 'control-label']) !!}--}}
                        {{--{!! Form::number('diff_total', old('diff_total', 0.00), ['id' => 'diff_total', 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}--}}
                        {{--@if($errors->has('diff_total'))--}}
                            {{--<span class="help-block">--}}
                                {{--{{ $errors->first('diff_total') }}--}}
                            {{--</span>--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</td>--}}
            {{--</tr>--}}
        {{--</tbody>--}}
    {{--</table>--}}
{{--</div>--}}
<div class="row">
    {!! Form::hidden('entry_type_id', old('entry_type_id', '2')) !!}
    {!! Form::hidden('suppliers_id', old('suppliers_id', '2')) !!}
</div>
<!-- Slip Area Started -->
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="pull-left header"><i class="fa fa-th"></i> Entry Items</li>
        <li class="pull-right"><a href="#tab_2" data-toggle="tab"><u>P</u>arameters</a></li>
        <li class="active pull-right"><a href="#tab_1" data-toggle="tab"><u>B</u>asic</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <button onclick="FormControls.createEntryItem();" type="button" style="margin-bottom: 5px;" class="btn pull-right btn-sm btn-flat btn-primary"><i class="fa fa-plus"></i>&nbsp;Add <u>R</u>ow</button>
            <table class="table table-condensed" id="entry_items">
                <thead>
                    <tr>
                        <th colspan="3">Account</th>
                        <th width="12%">Gold Weight</th>
                        <th width="12%">Farqa Ratti</th>
                        <th width="15%">Farqa Weight</th>
                        <th width="28%">Narration</th>
                        <th width="4%">Action</th>
                    </tr>
                </thead>
                <tbody>

                    @if(isset($VoucherData['entry_items']))
                        @foreach ($VoucherData['entry_items']['counter'] as $key => $val)
                            @if ($key == 1)
                                @continue
                            @endif
                            <tr id="entry_item-{{ $val }}">
                                <td colspan="4">
                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                        {!! Form::hidden("entry_items[counter][$val]", $val, ['id' => "entry_item-counter-$val", 'class' => "entry_item-counter-$val"]) !!}
                                        {!! Form::select("entry_items[ledger_id][$val]", ($VoucherData['entry_items']['ledger_id'][$val]) ? array($VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->id => $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->number . ' - ' . $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->name) : array(), $VoucherData['entry_items']['ledger_id'][$val], ['id' => "entry_item-ledger_id-$val", 'style' => 'width: 100%;', 'class' => 'form-control description-data-ajax']) !!}
                                    </div>
                                    {!! Form::hidden("entry_items[dr_amount][$val]", $VoucherData['entry_items']['dr_amount'][$val], [
                                        'id' => "entry_item-dr_amount-$val",
                                        'class' => 'form-control entry_items-dr_amount',
                                        'placeholder' => 'Amount',
                                        ]) !!}
                                </td>
                                <td>
                                    <div class="form-group" style="margin-bottom: 0px !important;">
                                        {!! Form::number("entry_items[cr_amount][$val]", $VoucherData['entry_items']['cr_amount'][$val], [
                                        'onkeydown' => 'FormControls.CalculateTotal();',
                                        'onkeyup' => 'FormControls.CalculateTotal();',
                                        'onblur' => 'FormControls.CalculateTotal();',
                                        'id' => "entry_item-cr_amount-$val",
                                        'class' => 'form-control entry_items-cr_amount',
                                        'placeholder' => 'Amount',
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
                                <td><button onclick="FormControls.destroyEntryItem('{{$val}}');" id="entry_item-del_btn-{{$val}}" type="button" class="btn btn-block btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                            </tr>
                        @endforeach
                    @else
                        <tr id="entry_item-2">
                            <td colspan="3">
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {{--{!! Form::hidden('entry_items[counter][2]', '2', ['id' => "entry_item-counter-2", 'class' => 'entry_item-counter-2']) !!}--}}
                                    {{--{!! Form::select('trans_acc_from[]', array(), null, ['id' => 'entry_item-ledger_id-2', 'style' => 'width: 100%;', 'class' => 'form-control description-data-ajax']) !!}--}}
                                    <select name="trans_acc_from[]"  class="form-control select2 description-data-ajax" id = 'entry_item-ledger_id-2'>
                                      <option value=""></option>
                                    </select>
                                </div>
                                {{--{!! Form::hidden('entry_items[dr_amount][2]', null, [--}}
                                    {{--'id' => 'entry_item-dr_amount-2',--}}
                                    {{--'class' => 'form-control entry_items-dr_amount',--}}
                                    {{--'placeholder' => 'Dr. Amount'--}}
                                    {{--]) !!}--}}
                            </td>
                            <td>
                                <div class="form-group">
                                    {{--<select class="form-control" name="currency_id[]">--}}
                                        {{--{!! \App\Helpers\Currency::currencyList() !!}--}}
                                    {{--</select>--}}
                                    <input type="number" name="gold_weight[]" class="form-control" placeholder="Gold Weight">
                                </div>
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {{--{!! Form::number('entry_items[cr_amount][2]', null, [--}}
                                    {{--'onkeydown' => 'FormControls.CalculateTotal();',--}}
                                    {{--'onkeyup' => 'FormControls.CalculateTotal();',--}}
                                    {{--'onblur' => 'FormControls.CalculateTotal();',--}}
                                    {{--'id' => 'entry_item-cr_amount-2',--}}
                                    {{--'class' => 'form-control entry_items-cr_amount',--}}
                                    {{--'placeholder' => 'Amount'--}}
                                    {{--]) !!}--}}
                                    <input type="number" name="farqa_ratti[]" class="form-control" placeholder="Farqa Ratti">
                                </div>
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {{--{!! Form::text('entry_items[narration][2]', null, [--}}
                                    {{--'id' => 'entry_item-narration-2',--}}
                                    {{--'class' => 'form-control entry_items-narration',--}}
                                    {{--'placeholder' => 'Narration'--}}
                                    {{--]) !!}--}}
                                    <input type="number" name="farqa_weight[]" class="form-control" placeholder="Farqa Weight">
                                </div>
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {{--{!! Form::text('entry_items[narration][2]', null, [--}}
                                    {{--'id' => 'entry_item-narration-2',--}}
                                    {{--'class' => 'form-control entry_items-narration',--}}
                                    {{--'placeholder' => 'Narration'--}}
                                    {{--]) !!}--}}
                                    <input type="text" name="narration[]" class="form-control" placeholder="Narration">
                                </div>
                            </td>
                            <td><button onclick="FormControls.destroyEntryItem('2');" id="entry_item-del_btn-2" type="button" class="btn btn-block btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    @endif
                    {{--<tr>--}}
                        {{--<td align="right" colspan="4" style="padding-top: 12px;" width="54%"><b>Total Amount</b></td>--}}
                        {{--<td>--}}
                            {{--<div class="form-group @if($errors->has('cr_total')) has-error @endif">--}}
                                {{--{!! Form::number('cr_total', old('cr_total', 0.00), ['id' => 'cr_total', 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}--}}
                                {{--@if($errors->has('cr_total'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--{{ $errors->first('cr_total') }}--}}
                                    {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                </tbody>
            </table>
            {{--<input type="hidden" id="entry_item-global_counter" name="counter[]" value="@if(count($VoucherData['entry_items']['counter'])){{ count($VoucherData['entry_items']['counter']) }}@else{{'2'}}@endif" />--}}
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_2">
            <div class="form-group col-md-3 @if($errors->has('cheque_no')) has-error @endif">
                {!! Form::label('cheque_no', 'Cheque #', ['class' => 'control-label']) !!}
                {!! Form::text('cheque_no', $VoucherData['cheque_no'], ['class' => 'form-control']) !!}
                @if($errors->has('cheque_no'))
                    <span class="help-block">
                        {{ $errors->first('cheque_no') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('cheque_date')) has-error @endif">
                {!! Form::label('cheque_date', 'Cheque Date', ['class' => 'control-label']) !!}
                {!! Form::text('cheque_date', ($VoucherData['cheque_date']) ? $VoucherData['cheque_date'] : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                @if($errors->has('cheque_date'))
                    <span class="help-block">
                        {{ $errors->first('cheque_date') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('invoice_no')) has-error @endif">
                {!! Form::label('invoice_no', 'Invoice #', ['class' => 'control-label']) !!}
                {!! Form::text('invoice_no', $VoucherData['invoice_no'], ['class' => 'form-control']) !!}
                @if($errors->has('invoice_no'))
                    <span class="help-block">
                        {{ $errors->first('invoice_no') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('invoice_date')) has-error @endif">
                {!! Form::label('invoice_date', 'Invoice Date*', ['class' => 'control-label']) !!}
                {!! Form::text('invoice_date', ($VoucherData['invoice_date']) ? $VoucherData['invoice_date'] : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                @if($errors->has('invoice_date'))
                    <span class="help-block">
                        {{ $errors->first('invoice_date') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('cdr_no')) has-error @endif">
                {!! Form::label('cdr_no', 'CDR #', ['class' => 'control-label']) !!}
                {!! Form::text('cdr_no', $VoucherData['cdr_no'], ['class' => 'form-control']) !!}
                @if($errors->has('cdr_no'))
                    <span class="help-block">
                        {{ $errors->first('cdr_no') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('cdr_date')) has-error @endif">
                {!! Form::label('cdr_date', 'CDR Date*', ['class' => 'control-label']) !!}
                {!! Form::text('cdr_date', ($VoucherData['cdr_date']) ? $VoucherData['cdr_date'] : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                @if($errors->has('cdr_date'))
                    <span class="help-block">
                        {{ $errors->first('cdr_date') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('bdr_no')) has-error @endif">
                {!! Form::label('bdr_no', 'BDR #', ['class' => 'control-label']) !!}
                {!! Form::text('bdr_no', $VoucherData['bdr_no'], ['class' => 'form-control']) !!}
                @if($errors->has('bdr_no'))
                    <span class="help-block">
                        {{ $errors->first('bdr_no') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('bdr_date')) has-error @endif">
                {!! Form::label('bdr_date', 'BDR Date*', ['class' => 'control-label']) !!}
                {!! Form::text('bdr_date', ($VoucherData['bdr_date']) ? $VoucherData['bdr_date'] : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                @if($errors->has('bdr_date'))
                    <span class="help-block">
                        {{ $errors->first('bdr_date') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('bank_name')) has-error @endif">
                {!! Form::label('bank_name', 'Bank Name', ['class' => 'control-label']) !!}
                {!! Form::text('bank_name', $VoucherData['bank_name'], ['class' => 'form-control']) !!}
                @if($errors->has('bank_name'))
                    <span class="help-block">
                        {{ $errors->first('bank_name') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('bank_branch')) has-error @endif">
                {!! Form::label('bank_branch', 'Bank Branch', ['class' => 'control-label']) !!}
                {!! Form::text('bank_branch', $VoucherData['bank_branch'], ['class' => 'form-control']) !!}
                @if($errors->has('bank_branch'))
                    <span class="help-block">
                        {{ $errors->first('bank_branch') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('drawn_date')) has-error @endif">
                {!! Form::label('drawn_date', 'Drawn Date*', ['class' => 'control-label']) !!}
                {!! Form::text('drawn_date', ($VoucherData['drawn_date']) ? $VoucherData['drawn_date'] : date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                @if($errors->has('drawn_date'))
                    <span class="help-block">
                        {{ $errors->first('drawn_date') }}
                    </span>
                @endif
            </div>
            <div class="row"></div>
        </div>
        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>