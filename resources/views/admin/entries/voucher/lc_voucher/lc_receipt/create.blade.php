@extends('layouts.app')

@section('stylesheet')
    <!-- Select2 -->
    {{--<link rel="stylesheet" href="{{ url('adminlte') }}/bower_components/select2/dist/css/select2.min.css">--}}
    {{--<!-- bootstrap datepicker -->--}}
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
{{--custom-csss-for-table-tr--}}
    <style>
        .custum-tr-con{
            width: 100%;
            float: left;
            /*margin-left: 6%;*/
        }
        .custum-td-con-1{
            width:60%;
            float:left;
        }
        .custum-td-con-2{
            width:40%;
            float:left;
        }

    </style>



@stop

@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>LC Receipt Voucher</h1>
    </section>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Create Bank Receipt Voucher</h3>
            <a href="{{ route('admin.entries.index') }}" class="btn btn-success pull-right">Back</a>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {!! Form::open(['method' => 'POST', 'route' => ['admin.voucher.lrp_store'], 'id' => 'validation-form']) !!}
            <div class="box-body">
                @include('admin.entries.voucher.lc_voucher.lc_receipt.fields')
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                {!! Form::submit(trans('global.app_save'), ['class' => 'btn btn-danger globalSaveBtn']) !!}
            </div>
        {!! Form::close() !!}
        @include('admin.entries.voucher.lc_voucher.lc_receipt.entries_template')
    </div>
@stop

@section('javascript')
    <script>
        $(document).on('keyup','.test',function () {
            calculated_amount = 0;
            rel_qty = $(this).val();
            rowId = $(this).attr('data-row-id');
            total_qty = $('#total_qty_'+rowId).val();
            balanc_qty = total_qty - rel_qty;
            $('#bal_qty_'+rowId).val(balanc_qty);
            unit_price = $('#unit_price_'+rowId).val();
            remianing_balance = unit_price * balanc_qty;
            $('#bal_amount_'+rowId).val(remianing_balance);
            total_amount = $('#total_amount_'+rowId).val();
            total_paid = total_amount - remianing_balance;
            $('#total_paid_'+rowId).val(total_paid);
            $('.cal_sum').each(function () {
                totalvalue = $(this).val();
                if(totalvalue != '' && totalvalue != '0')
                {
                  calculated_amount = parseFloat(calculated_amount) + parseFloat(totalvalue);
                  $('#entry_item-dr_amount-2').val(calculated_amount);
                  $('#cr_total').val(calculated_amount);
                  difffval = $('#entry_item-dr_amount-2').val() -  $('#cr_total').val();
                  $('#diff_total').val(difffval);
                }

            });

        })
    </script>

    <script>
        $(document).ready(function(){
           $(".product").on('change',function() {
               var feildvalue = $(this).val();
               if(feildvalue!=''){
                   var url = route('admin.saleorder.productlist');
                   url = url+'/'+feildvalue;
                   alert(url);
                   $.ajax({
                       type:"get",
                       url : url,
                       success:function(data){
                           alert(data);
                           if(data!=''){
                               //product_select= data;
                               $('#productmodel_col').html(data);
                           }else{
                               alert("There is no product against this brand.");
                           }


                       }
                   });
               }

           });
        });
    </script>


    <!-- bootstrap datepicker -->
    <script src="{{ url('public/adminlte') }}/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <!-- Select2 -->
    {{--<script src="{{ url('adminlte') }}/bower_components/select2/dist/js/select2.full.min.js"></script>--}}
    <script src="{{ url('public/js/admin/entries/voucher/lc_voucher/lc_receipt/create_modify.js') }}" type="text/javascript"></script>
@endsection

