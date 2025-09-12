
<div class="form-group col-md-2  @if($errors->has('number')) has-error @endif">
    {!! Form::label('number', 'Number*', ['class' => 'control-label']) !!}
    {!! Form::text('number','?????', ['class' => 'form-control', 'readonly' => 'true']) !!}
    @if($errors->has('number'))
        <span class="help-block">
            {{ $errors->first('number') }}
        </span>
    @endif
</div>
<div class="form-group col-md-2  @if($errors->has('voucher_date')) has-error @endif">
    {!! Form::label('number', 'Voucher Date*', ['class' => 'control-label']) !!}
    {!! Form::text('voucher_date',date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
    @if($errors->has('voucher_date'))
        <span class="help-block">
            {{ $errors->first('voucher_date') }}
        </span>
    @endif
</div>
<div class="col-md-2">
    <label>Payment From</label>
    <select class="form-control" name="trans_acc_from">
        {!! App\Models\Admin\Ledgers::get_ledgers(Config::get('constants.sale_stock_pure_gold')) !!}
    </select>
</div>
<div class="col-md-2">
    <div class="checkbox">
        <label style="visibility: hidden">ljkashfafh</label>
        <label><input type="radio" name="gold_type" value="normal" checked> Normal</label>
    </div>
</div>
<div class="col-md-2">
    <div class="checkbox">
        <label style="visibility: hidden">ljkashfafh</label>
        <label><input type="radio" name="gold_type" value="swiss"> Swiss-Refine</label>
    </div>
</div>
<div class="col-md-2">
    <div class="checkbox">
        <label style="visibility: hidden">ljkashfafh</label>
        <label><input type="radio" name="gold_type" value="refine"> Refine-Swiss</label>
    </div>
</div>
<div class="clearfix"></div>
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
                    <th>Account</th>
                    <th width="12%">Gold Weight</th>
                    <th width="12%">Farqa Ratti</th>
                    <th width="12%">Farqa Weight</th>
                    <th width="30%">Narration</th>
                    <th width="4%">Action</th>
                </tr>
                </thead>
                <tbody>
                    <tr id="entry_item-2">
                        <td>
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                {{--{!! Form::hidden('entry_items[counter][2]', '2', ['id' => "entry_item-counter-2", 'class' => 'entry_item-counter-2']) !!}--}}
                                {!! Form::select('ledger_id[]', array(), null, ['id' => 'entry_item-ledger_id-2', 'style' => 'width: 100%;', 'class' => 'form-control description-data-ajax']) !!}
                            </div>
                            {!! Form::hidden('entry_items[cr_amount][2]', null, [
                                'id' => 'entry_item-cr_amount-2',
                                'class' => 'form-control entry_items-cr_amount',
                                'placeholder' => 'Dr. Amount'
                                ]) !!}
                        </td>
                        <td>
                            <div class="form-group">
                                <input name="gold_weight[]" type="text" class="form-control gold_weight" placeholder="Gold Weight">
                            </div>
                        </td>
                        <td>
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                {{--{!! Form::number('entry_items[dr_amount][2]', null, [--}}
                                {{--'onkeydown' => 'FormControls.CalculateTotal();',--}}
                                {{--'onkeyup' => 'FormControls.CalculateTotal();',--}}
                                {{--'onblur' => 'FormControls.CalculateTotal();',--}}
                                {{--'id' => 'entry_item-dr_amount-2',--}}
                                {{--'class' => 'form-control entry_items-dr_amount',--}}
                                {{--'placeholder' => 'Farqa Ratti'--}}
                                {{--]) !!}--}}
                                <input type="text" name="farqa_ratti[]" class="form-control farqa_ratti" placeholder="Farqa Ratti" onchange="FormControls.CalculateTotal()">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="farqa_weight[]" class="form-control farqa_weight" placeholder="Farqa Weight">
                            </div>
                        </td>
                        <td>
                            <div class="form-group" style="margin-bottom: 0px !important;">
                                {!! Form::text('narration[]', null, [
                                'id' => 'entry_item-narration-2',
                                'class' => 'form-control entry_items-narration',
                                'placeholder' => 'Narration'
                                ]) !!}
                            </div>
                        </td>
                        <td><button onclick="FormControls.destroyEntryItem('2');" id="entry_item-del_btn-2" type="button" class="btn btn-block btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                    </tr>
                {{--<tr>--}}
                    {{--<td align="right" colspan="4" style="padding-top: 12px;" width="54%"><b>Total Amount</b></td>--}}
                    {{--<td>--}}
                        {{--<div class="form-group @if($errors->has('dr_total')) has-error @endif">--}}
                            {{--{!! Form::number('dr_total', old('dr_total', 0.00), ['id' => 'dr_total', 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}--}}
                            {{--@if($errors->has('dr_total'))--}}
                                {{--<span class="help-block">--}}
                                        {{--{{ $errors->first('dr_total') }}--}}
                                    {{--</span>--}}
                            {{--@endif--}}
                        {{--</div>--}}
                    {{--</td>--}}
                    {{--<td colspan="2"></td>--}}
                {{--</tr>--}}
                </tbody>
            </table>
            {{--<input type="hidden" id="entry_item-global_counter" value="@if(count($VoucherData['entry_items']['counter'])){{ count($VoucherData['entry_items']['counter']) }}@else{{'2'}}@endif" />--}}
        </div>
        <!-- /.tab-pane -->
        <div class="tab-pane" id="tab_2">
            <div class="form-group col-md-3 @if($errors->has('cheque_no')) has-error @endif">
                {!! Form::label('cheque_no', 'Cheque #', ['class' => 'control-label']) !!}
                {!! Form::text('cheque_no', '', ['class' => 'form-control']) !!}
                @if($errors->has('cheque_no'))
                    <span class="help-block">
                        {{ $errors->first('cheque_no') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('cheque_date')) has-error @endif">
                {!! Form::label('cheque_date', 'Cheque Date', ['class' => 'control-label']) !!}
                {!! Form::text('cheque_date', date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                @if($errors->has('cheque_date'))
                    <span class="help-block">
                        {{ $errors->first('cheque_date') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('invoice_no')) has-error @endif">
                {!! Form::label('invoice_no', 'Invoice #', ['class' => 'control-label']) !!}
                {!! Form::text('invoice_no', '', ['class' => 'form-control']) !!}
                @if($errors->has('invoice_no'))
                    <span class="help-block">
                        {{ $errors->first('invoice_no') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('invoice_date')) has-error @endif">
                {!! Form::label('invoice_date', 'Invoice Date*', ['class' => 'control-label']) !!}
                {!! Form::text('invoice_date', date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                @if($errors->has('invoice_date'))
                    <span class="help-block">
                        {{ $errors->first('invoice_date') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('cdr_no')) has-error @endif">
                {!! Form::label('cdr_no', 'CDR #', ['class' => 'control-label']) !!}
                {!! Form::text('cdr_no','', ['class' => 'form-control']) !!}
                @if($errors->has('cdr_no'))
                    <span class="help-block">
                        {{ $errors->first('cdr_no') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('cdr_date')) has-error @endif">
                {!! Form::label('cdr_date', 'CDR Date*', ['class' => 'control-label']) !!}
                {!! Form::text('cdr_date', date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                @if($errors->has('ccr_date'))
                    <span class="help-block">
                        {{ $errors->first('cdr_date') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('bdr_no')) has-error @endif">
                {!! Form::label('bdr_no', 'BDR #', ['class' => 'control-label']) !!}
                {!! Form::text('bdr_no', '', ['class' => 'form-control']) !!}
                @if($errors->has('bdr_no'))
                    <span class="help-block">
                        {{ $errors->first('bdr_no') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('bdr_date')) has-error @endif">
                {!! Form::label('bdr_date', 'BDR Date*', ['class' => 'control-label']) !!}
                {!! Form::text('bdr_date', date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
                @if($errors->has('bdr_date'))
                    <span class="help-block">
                        {{ $errors->first('bdr_date') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('bank_name')) has-error @endif">
                {!! Form::label('bank_name', 'Bank Name', ['class' => 'control-label']) !!}
                {!! Form::text('bank_name', '', ['class' => 'form-control']) !!}
                @if($errors->has('bank_name'))
                    <span class="help-block">
                        {{ $errors->first('bank_name') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('bank_branch')) has-error @endif">
                {!! Form::label('bank_branch', 'Bank Branch', ['class' => 'control-label']) !!}
                {!! Form::text('bank_branch', '', ['class' => 'form-control']) !!}
                @if($errors->has('bank_branch'))
                    <span class="help-block">
                        {{ $errors->first('bank_branch') }}
                    </span>
                @endif
            </div>
            <div class="form-group col-md-3 @if($errors->has('drawn_date')) has-error @endif">
                {!! Form::label('drawn_date', 'Drawn Date*', ['class' => 'control-label']) !!}
                {!! Form::text('drawn_date', date('Y-m-d'), ['class' => 'form-control datepicker']) !!}
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