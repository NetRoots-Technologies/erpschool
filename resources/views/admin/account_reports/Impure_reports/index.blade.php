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
        <div class="col-md-3">
            <label>Date</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input id="date_range" class="form-control" name="date_range" type="text">
            </div>
        </div>
        <div class="form-group col-md-3 @if($errors->has('job_id')) has-error @endif">
            <label class="control-label">Select Vendor</label>
            <select id="job_id" name="job_id" class="form-control select2" required>
                <option value="0">All</option>
                @foreach($job_id as $job_ids)
                    <option value="{{ $job_ids->id }}"  {{ $routing_id == $job_ids->id ? 'selected' : '' }}>
                        {{ $job_ids->id }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2 @if($errors->has('search_button')) has-error @endif" >
            {!! Form::label('search_button', ' ', ['class' => 'control-label']) !!}
            <button type="submit" style="margin-top: 5px;" class="btn  btn-sm btn-flat btn-primary form-control"><b>&nbsp;Search </b> </button>
        </div>
        {{-- @if($reset)
        <div class="form-group col-md-2" >
            <button type="button" id="reset" name="action" value="reset" style="margin-top: 25px;" class="btn btn-success">Reset</button>
        </div>
        @endif --}}
        {{-- <div class="form-group col-md-5" style="margin-left: 300px;">
        <div class="form-group col-md-5" style="margin-left: 200px;">
            <button type="submit" id="excel" name="action" value="excel" style="margin-top: 25px;" class="btn btn-success">Excel</button>
            &nbsp;&nbsp;&nbsp;
            <button type="submit" id="pdf" name="action" value="pdf" style="margin-top: 25px;" class="btn btn-danger">PDF</button>
        </div>
        </div> --}}
    </form>
@stop
@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-list"></i><h3 class="box-title">List</h3>
        </div>
        <!-- /.box-header -->
        <div class="panel-body pad table-responsive">
            <table class="table table-bordered table-striped {{ count($job_id) > 0 ? 'datatable' : '' }}">
                <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Date</th>
                    <th>Gross Weight</th>
                    {{-- <th>Net Weight</th>
                    <th>Pure Weight</th>
                    <th>LDS Weight</th> --}}
                    <th>Total Amount</th>
                    {{-- <th>Total other charges</th> --}}
                    <th>Status</th>
                    <th>Action</th>

                </tr>
                </thead>

                <tbody>
                @php
                    $i = 1;
                @endphp
                @if($job_id)
                    @foreach($job_id as $item)
                        <tr>
                            <td>{{ $item->id??' - '}}</td>
                            <td>{{ $item->created_at??' - '}}</td>
                            <td>{{ $item->total_gross_weight??' - '}}</td>
                            {{-- <td>{{ $item->total_net_weight??' - '}}  </td>
                            <td>{{ $item->total_pure_weight??' - '}}</td>
                            <td>{{ $item->total_LDS_Weight??' - '}}</td> --}}
                            <td>{{$item->total_paid_amount??' - '}}</td>
                            {{-- <td>{{$item->total_other_charges??'-'}}</td> --}}
                            <td>{{ $item->entery_status??' - '}}</td>
                            <td>
                                <a href="{{ route('admin.impure_accounts_report.view',[$item->id]) }}" 
                                    class="btn btn-xs btn-danger" id="reject">view</a>

                                {{-- <button href="{{route('admin.impure_accounts_report.view',[$item->id])}}"  class="btn btn-danger">View</button> --}}
                            </td>
                          
                         
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
