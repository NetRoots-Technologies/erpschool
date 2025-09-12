@inject('request', 'Illuminate\Http\Request')
@inject('currency_helper', 'App\Helpers\Currency')
@inject('helper', 'App\Helpers\UserHelper')

@extends('layouts.app')

@section('stylesheet')
    <!-- Pace style -->
    {{--<link rel="stylesheet" href="{{ url('public/adminlte') }}/plugins/pace/pace.min.css">--}}
    <link rel="stylesheet" href="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.css">
@stop
@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Profit And Loss Report</h1>
    </section>
    <form action="" type="">
    @csrf
        <div class="container">
            <div class="row text-right">
                <div class="col-md-11">
                    <a href="{{ route('admin.impure_accounts_report')}}" class="btn btn-success" 
                    style="margin-bottom: 2%;">Back</a>
                </div>
            </div>
        </div>    
    </form>
@stop
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-list"></i><h3 class="box-title">List</h3>

            @if($paid_amnt->entery_status == 'out' && $paid_amnt->melting_status == '0' )

            @else
            <div class="text-center">
                <h3>Pendding</h3>
            </div>
            @endif

        </div>
        <!-- /.box-header -->
        <div class="panel-body pad table-responsive">
            <table class="table table-bordered table-striped {{ count($entriesItem) > 0 ? 'datatable' : '' }}">
                <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Date</th>
                    <th>Ledger</th>
                    <th>Narration</th>
                    <th>Item Currency</th>
                    <th>Currency Rate</th>
                    <th>Currency Ammount</th>
                    <th>Ammount In PKR</th>
                    {{-- <th>Debit</th> --}}
                </tr>
                </thead>

                <tbody>
                @php
                    $i = 1;
                @endphp
                @if($entriesItem)
                    @foreach($entriesItem as $item)
                        <tr>
                            <?php
                                $currency=$helper->get_name('currencies','id',$item->currence_type);
                                $ledgers=$helper->get_name('ledgers','id',$item->ledger_id);
                                $currency_value =  $currency_helper->currency_convertor($item->currence_type);
                            ?>
                            <td>{{ $item->job_id??' - '}}</td>
                            <td>{{ $item->created_at??' - '}}</td>
                            <td>{{ $ledgers->name??' - '}}</td>
                            <td>{{ $item->narration??' - '}}</td>
                            <td>{{ $currency->code??' - '}}</td>
                            <td>{{ $currency_value??' - '}}</td>
                            <td>{{ $item->other_amount??' - '}}</td>
                            <td>@if($item->dc=="c")  {{ $item->amount?? 0}} @else 0 @endif   </td>
                            {{-- <td>@if($item->dc=="d")  {{ $item->amount?? 0}} @else 0 @endif   </td> --}}
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">@lang('global.app_no_entries_in_table')</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-12" style="margin-left: 500px;">
        <div class="col-md-2 debit_amnt" >
            <label for="debit_amnt">Total Paid Amount</label>
            <input type="number" name="debit_amnt" style="background-color: #3CBC8D; 
            color: white;" class="form-control debit_amnt"  
                value="{{ $paid_amnt->total_paid_amount }}" readonly id="debit_amnt">
        </div>
        <div class="col-md-2 credit_amnt" >
            <label for="credit_amnt">Total Credit Amount</label>
            <input type="number" name="credit_amnt" style="background-color: #3CBC8D; 
            color: white;" class="form-control credit_amnt" 
                value="{{ $credit_amnt->amount }}" readonly id="credit_amnt">
        </div>
        <?php
            $diff = $credit_amnt->amount - $paid_amnt->total_paid_amount;
            if($diff > 0){
                $profit_loss = 'Profit';
            }
            else{
                $profit_loss = 'Loss';
            }
        ?>
        <div class="col-md-2 credit_amnt" >
            <label for="credit_amnt">Total Difference </label>
            <input type="number" name="credit_amnt" style="background-color: #3CBC8D; 
            color: white;" class="form-control credit_amnt" 
                value="{{ $diff }}" readonly id="credit_amnt">{{$profit_loss}}
        </div>
    </div>
@stop

@section('javascript')

<script src="{{ url('public/adminlte') }}/bower_components/PACE/pace.min.js"></script>
        <!-- date-range-picker -->
        <script src="{{ url('public/adminlte') }}/bower_components/moment/min/moment.min.js"></script>
        <script src="{{ url('public/adminlte') }}/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script src="{{ url('public/js/admin/entries/voucher/journal_voucher/create_modify.js') }}" type="text/javascript"></script>
        <script src="{{ url('public/js/admin/ledger_reports/ledger_report.js') }}" type="text/javascript"></script>

    <script>
     $("#reset").click(function(){
    window.location = "{{ url('inventory/stock_in_position/stock_lds') }}";
    });
        $(document).ready(function(){
        <?php
           if(isset($date_range))
           {
               $start_date = date('m/d/Y', strtotime($date_range[0]));
               $end_date = date('m/d/Y', strtotime($date_range[1]));
               echo " $('#date_range').daterangepicker({ startDate: '{{$start_date}}', endDate: '{{$end_date}}' });";
           }      
        ?>
    $('.datatable').DataTable();       
        });
    </script>
@endsection
