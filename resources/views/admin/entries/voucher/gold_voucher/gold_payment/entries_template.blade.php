<table style="display: none;">
    <tbody id="entry_item-container" >
    <tr id="entry_item-######">
        <td colspan="">
            <div class="form-group" style="margin-bottom: 0px !important;">
                {!! Form::hidden('entry_items[counter][######]', '######', ['id' => 'entry_item-counter-######', 'class' => 'entry_item-counter-######']) !!}
                {!! Form::select('ledger_id[]', array(), old('branch_id'), ['id' => 'entry_item-ledger_id-######', 'style' => 'width: 100%;', 'class' => 'form-control description-data-ajax######']) !!}
            </div>
            {!! Form::hidden('entry_items[cr_amount][######]', null, [
                'id' => 'entry_item-cr_amount-######',
                'class' => 'form-control entry_items-cr_amount########',
                'placeholder' => 'Dr. Amount'
                ]) !!}
        </td>
        <td>
            <div class="form-group">
                <input type="text" name="gold_weight[]" placeholder="Gold Weight" class="form-control gold_weight">
            </div>
        </td>
        <td>
            <div class="form-group" style="margin-bottom: 0px !important;">
                {{--{!! Form::number('entry_items[dr_amount][######]', null, [--}}
                {{--'onkeydown' => 'FormControls.CalculateTotal();',--}}
                {{--'onkeyup' => 'FormControls.CalculateTotal();',--}}
                {{--'onblur' => 'FormControls.CalculateTotal();',--}}
                {{--'id' => 'entry_item-dr_amount-######',--}}
                {{--'class' => 'form-control entry_items-dr_amount########',--}}
                {{--'placeholder' => 'Farqa Ratti'--}}
                {{--]) !!}--}}
                <input type="text" name="farqa_ratti[]" class="form-control farqa_ratti" onchange="FormControls.CalculateTotal()">
            </div>
        </td>
        <td>
            <div class="form-group">
                <input type="text" name="farqa_weight[]" placeholder="Farqa Weight" class="form-control farqa_weight">
            </div>
        </td>
        <td>
            <div class="form-group" style="margin-bottom: 0px !important;">
                {!! Form::text('narration[]', null, [
                'id' => 'entry_item-narration-######',
                'class' => 'form-control entry_items-narration########',
                'placeholder' => 'Narration'
                ]) !!}
            </div>
        </td>
        <td><button id="entry_item-del_btn-######" onclick="FormControls.destroyEntryItem('######');" type="button" class="btn btn-block btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
    </tr>
    </tbody>
</table>