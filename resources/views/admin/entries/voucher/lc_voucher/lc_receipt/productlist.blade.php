
<?php
$a=0;
?>
<table class="table table-condensed" id="entry_items">
    <thead>
    <tr>
        <th>Product Detail</th>
        <th>Total Qty</th>
        <th>Rel. Qty</th>
        <th>Bal. Qty</th>
        <th>Unit Price</th>
        <th>Total Amount</th>
        <th>Paid Amount</th>
        <th>Balance Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($results as $result)
        <?php
        $a++;
        ?>
    <tr>
        <td>
            {!! Form::text('product_detail[]', $result->productsModel->products_name,['id' => 'product_detail_'.$a, 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
            {!! Form::hidden('product_detail_id[]', $result->product_name,['id' => 'product_detail_id_'.$a, 'class' => 'form-control',]) !!}
            {!! Form::hidden('lc_id[]', $result->lc_no,['id' => 'lc_id_'.$a, 'class' => 'form-control',]) !!}
        </td>
        <td>
            {!! Form::text('total_qty[]',$result->total_qty,  ['id' => 'total_qty_'.$a, 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
        </td>
        <td>
           {!! Form::text('rel_qty[]', $result->rel_qty, ['id' => 'rel_qty_'.$a, 'class' => 'form-control test','data-row-id'=>"$a"]) !!}

        </td>
        <td>
           {!! Form::text('bal_qty[]', $result->balance_qty, ['id' => 'bal_qty_'.$a, 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}

        </td>
        <td>
            {!! Form::text('unit_price[]', $result->af_cp_unit, ['id' => 'unit_price_'.$a, 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
        </td>
        <td>
            {!! Form::text('total_amount[]', $result->f_total_amount, ['id' => 'total_amount_'.$a, 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
        </td>
        <td>
            {!! Form::text('total_paid[]',  $result->f_total_amount, ['id' => 'total_paid_'.$a, 'class' => 'form-control cal_sum', 'readonly' => 'true', 'readonly' => 'true']) !!}
        </td>
        <td>
            {!! Form::text('bal_amount[]', $result->balance_lc, ['id' => 'bal_amount_'.$a, 'class' => 'form-control', 'readonly' => 'true', 'readonly' => 'true']) !!}
        </td>
    </tr>
        @endforeach
    </tbody>
</table>