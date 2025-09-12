
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
<div class="form-group col-md-2 @if($errors->has('branch_id')) has-error @endif">
    {!! Form::label('branch_id', 'Branch*', ['class' => 'control-label']) !!}
    {!! Form::select('branch_id', $Branches, $VoucherData['branch_id'], ['style' => 'width: 100%;', 'class' => 'form-control select2']) !!}
    <span id="branch_id_handler"></span>
    @if($errors->has('branch_id'))
        <span class="help-block">
            {{ $errors->first('branch_id') }}
        </span>
    @endif
</div>
<div class="form-group col-md-3 @if($errors->has('employee_id')) has-error @endif">
    {!! Form::label('employee_id', 'Exmployee*', ['class' => 'control-label']) !!}
    {!! Form::select('employee_id', $Employees, $VoucherData['employee_id'], ['style' => 'width: 100%;', 'class' => 'form-control select2']) !!}
    <span id="employee_id_handler"></span>
    @if($errors->has('employee_id'))
        <span class="help-block">
            {{ $errors->first('employee_id') }}
        </span>
    @endif
</div>
{{--<div class="form-group col-md-3 @if($errors->has('department_id')) has-error @endif">--}}
    {{--{!! Form::label('department_id', 'Department', ['class' => 'control-label']) !!}--}}
    {{--{!! Form::select('department_id', $Departments, $VoucherData['department_id'], ['style' => 'width: 100%;', 'class' => 'form-control select2']) !!}--}}
    {{--<span id="department_id_handler"></span>--}}
    {{--@if($errors->has('department_id'))--}}
        {{--<span class="help-block">--}}
            {{--{{ $errors->first('department_id') }}--}}
        {{--</span>--}}
    {{--@endif--}}
{{--</div>--}}
<div class="row"></div>
<div class="form-group col-md-12  @if($errors->has('remarks')) has-error @endif">
    {!! Form::label('remarks', 'Remarks', ['class' => 'control-label']) !!}
    {!! Form::text('remarks', $VoucherData['remarks'], ['class' => 'form-control']) !!}
    @if($errors->has('remarks'))
        <span class="help-block">
            {{ $errors->first('remarks') }}
        </span>
    @endif
</div>
<div class="form-group col-md-12  @if($errors->has('narration')) has-error @endif">
    {!! Form::label('narration', 'Narration*', ['class' => 'control-label']) !!}
    {!! Form::text('narration', $VoucherData['narration'], ['id' => 'narration', 'onkeydown' => 'FormControls.updateNarration();', 'onkeyup' => 'FormControls.updateNarration();', 'onblur' => 'FormControls.updateNarration();', 'class' => 'form-control']) !!}
    @if($errors->has('narration'))
        <span class="help-block">
            {{ $errors->first('narration') }}
        </span>
    @endif
</div>
<div class="form-group col-md-12  @if($errors->has('base_item')) has-error @endif">
    <table class="table table-condensed">
        <tbody>
        <tr>
            @if(count($VoucherData['entry_items']['counter']))
                @foreach ($VoucherData['entry_items']['counter'] as $key => $val)
                    @if ($key != 1)
                        @break
                    @endif

                    <td width="60%">
                        <div class="form-group" style="margin-bottom: 0px !important;">
                            {!! Form::label('narration', 'Bank A/C*', ['class' => 'control-label']) !!}
                            {!! Form::hidden("entry_items[counter][$val]", $val, ['id' => "entry_item-counter-$val", 'class' => "entry_item-counter-$val"]) !!}
                            {!! Form::select("entry_items[ledger_id][$val]", ($VoucherData['entry_items']['ledger_id'][$val]) ? array($VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->id => $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->number . ' - ' . $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->name) : array(), $VoucherData['entry_items']['ledger_id'][$val], ['id' => "entry_item-ledger_id-$val", 'style' => 'width: 100%;', 'class' => 'form-control base-data-ajax']) !!}
                        </div>
                        {!! Form::hidden("entry_items[cr_amount][$val]", $VoucherData['entry_items']['cr_amount'][$val], [
                            'id' => "entry_item-cr_amount-$val",
                            'class' => 'form-control entry_items-cr_amount',
                        ]) !!}
                        {!! Form::hidden("entry_items[dr_amount][$val]", $VoucherData['entry_items']['dr_amount'][$val], [
                            'id' => "entry_item-dr_amount-$val",
                            'class' => 'form-control entry_items-dr_amount',
                        ]) !!}
                    </td>
                @endforeach
            @else
                <td width="60%">
                    <div class="form-group" style="margin-bottom: 0px !important;">
                        {!! Form::label('narration', 'Bank A/C*', ['class' => 'control-label']) !!}
                        {!! Form::hidden('entry_items[counter][1]', '1', ['id' => "entry_item-counter-1", 'class' => 'entry_item-counter-1']) !!}
                        {!! Form::select('entry_items[ledger_id][1]', array(), null, ['id' => 'entry_item-ledger_id-1', 'style' => 'width: 100%;', 'class' => 'form-control base-data-ajax']) !!}
                    </div>
                </td>
            @endif

            <td width="20%">
                <div class="form-group @if($errors->has('cr_total')) has-error @endif" style="margin-bottom: 0px !important;">
                    {!! Form::label('cr_total', 'Bank Total', ['class' => 'control-label']) !!}
                    {!! Form::number('cr_total', old('cr_total', 0.00), ['id' => 'cr_total', 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
                    @if($errors->has('cr_total'))
                        <span class="help-block">
                                {{ $errors->first('cr_total') }}
                            </span>
                    @endif
                </div>
            </td>

            <td width="20%">
                <div class="form-group @if($errors->has('diff_total')) has-error @endif" style="margin-bottom: 0px !important;">
                    {!! Form::label('diff_total', 'Total Difference', ['class' => 'control-label']) !!}
                    {!! Form::number('diff_total', old('diff_total', 0.00), ['id' => 'diff_total', 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
                    @if($errors->has('diff_total'))
                        <span class="help-block">
                                {{ $errors->first('diff_total') }}
                            </span>
                    @endif
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="row">
    {!! Form::hidden('entry_type_id', old('entry_type_id', '7')) !!}
</div>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="pull-left header"><i class="fa fa-th"></i> Entry Items</li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <table class="table table-condensed" id="entry_items">
                <thead style="width: 100% ; float: left;">
                <tr class="custum-tr-con">
                    <th class="custum-td-con-1">LC/TT</th>
                    <th class="custum-td-con-2">Amount</th>
                </tr>
                </thead>
                <tbody style="width: 100% ; float: left;">
                @if(count($VoucherData['entry_items']['counter']))
                    @foreach ($VoucherData['entry_items']['counter'] as $key => $val)
                        @if ($key == 1)
                            @continue
                        @endif
                        <tr id="entry_item-{{ $val }}" class="custum-tr-con">
                            <td>
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {!! Form::hidden("entry_items[counter][$val]", $val, ['id' => "entry_item-counter-$val", 'class' => "entry_item-counter-$val"]) !!}
                                    {!! Form::select("entry_items[ledger_id][$val]", ($VoucherData['entry_items']['ledger_id'][$val]) ? array($VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->id => $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->number . ' - ' . $VoucherData['ledger_array'][$VoucherData['entry_items']['ledger_id'][$val]]->name) : array(), $VoucherData['entry_items']['ledger_id'][$val], ['id' => "entry_item-ledger_id-$val", 'style' => 'width: 100%;', 'class' => 'form-control description-data-ajax product']) !!}
                                </div>
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom: 0px !important;">
                                    {!! Form::number('entry_items[dr_amount][2]', null, [
                                       'id' => 'entry_item-dr_amount-2',
                                       'class' => 'form-control entry_items-dr_amount',
                                       'placeholder' => 'Amount',
                                       'readonly' => 'true',
                                       ]) !!}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr id="entry_item-2" class="custum-tr-con">
                        <td class="custum-td-con-1">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                {!! Form::hidden('entry_items[counter][2]', '2', ['id' => "entry_item-counter-2", 'class' => 'entry_item-counter-2']) !!}
                                {!! Form::select('entry_items[ledger_id][2]', array(), null, ['id' => 'entry_item-ledger_id-2', 'style' => 'width: 100%;', 'class' => 'form-control description-data-ajax product']) !!}
                            </div>
                        </td>
                        <td class="custum-td-con-2">
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                {!! Form::number('entry_items[dr_amount][2]', null, [
                                'id' => 'entry_item-dr_amount-2',
                                'class' => 'form-control entry_items-dr_amount',
                                'placeholder' => 'Amount',
                                'readonly' => 'true',
                                ]) !!}
                            </div>
                        </td>
                       </tr>
                @endif
                </tbody>
            </table>
            <input type="hidden" id="entry_item-global_counter" value="@if(count($VoucherData['entry_items']['counter'])){{ count($VoucherData['entry_items']['counter']) }}@else{{'2'}}@endif" />
        </div>
        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
<div id="productmodel_col"></div>
{{--<div class="row">--}}
    {{--{!! Form::hidden('entry_type_id', old('entry_type_id', '7')) !!}--}}
{{--</div>--}}