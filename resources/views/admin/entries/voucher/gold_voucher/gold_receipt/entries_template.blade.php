<table style="display: none;">
    <tbody id="entry_item-container" >
    <tr id="entry_item-######">
        <td colspan="3">
            <div class="form-group" style="margin-bottom: 0px !important;">
                {{--{!! Form::hidden('entry_items[counter][######]', '######', ['id' => 'entry_item-counter-######', 'class' => 'entry_item-counter-######']) !!}--}}
                {!! Form::select('trans_acc_from[]', array(), old('branch_id'), ['id' => 'entry_item-ledger_id-######', 'style' => 'width: 100%;', 'class' => 'form-control description-data-ajax######']) !!}
            </div>
            {{--{!! Form::hidden('entry_items[dr_amount][######]', null, [--}}
                {{--'id' => 'entry_item-dr_amount-######',--}}
                {{--'class' => 'form-control entry_items-dr_amount########',--}}
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
                {{--{!! Form::number('entry_items[cr_amount][######]', null, [--}}
                {{--'onkeydown' => 'FormControls.CalculateTotal();',--}}
                {{--'onkeyup' => 'FormControls.CalculateTotal();',--}}
                {{--'onblur' => 'FormControls.CalculateTotal();',--}}
                {{--'id' => 'entry_item-cr_amount-######',--}}
                {{--'class' => 'form-control entry_items-cr_amount########',--}}
                {{--'placeholder' => 'Amount'--}}
                {{--]) !!}--}}
                <input type="number" name="farqa_ratti[]" class="form-control" placeholder="Farqa Ratti">
            </div>
        </td>
        <td>
            <div class="form-group" style="margin-bottom: 0px !important;">
                {{--{!! Form::text('entry_items[narration][######]', null, [--}}
                {{--'id' => 'entry_item-narration-######',--}}
                {{--'class' => 'form-control entry_items-narration########',--}}
                {{--'placeholder' => 'Narration'--}}
                {{--]) !!}--}}
                <input type="number" name="farqa_weight[]" class="form-control" placeholder="Farqa Weight">
            </div>
        </td>
        <td>
            <div class="form-group" style="margin-bottom: 0px !important;">
                <input type="text" name="narration[]" class="form-control" placeholder="Narration">
            </div>
        </td>
        <td><button id="entry_item-del_btn-######" onclick="FormControls.destroyEntryItem('######');" type="button" class="btn btn-block btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
    </tr>
    </tbody>
</table>