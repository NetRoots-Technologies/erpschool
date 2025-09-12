
@inject('request', 'Illuminate\Http\Request')
@inject('Currency', '\App\Helpers\Currency')
@extends('layouts.app')
{{--<style>--}}
    {{--.legend { list-style: none; }--}}
    {{--.legend #leg { float: left; margin-right: 10px; }--}}
    {{--.legend span { border: 1px solid #ccc; float: left; width: 12px; height: 12px; margin: 2px; }--}}
    {{--/* your colors */--}}
    {{--.legend .one { background-color: #83b7f7; }--}}
    {{--.legend .two { background-color: #aaf783; }--}}
    {{--.legend .three { background-color: #f28a8a; }--}}
    {{--.legend .four { background-color: #75f0d5; }--}}
    {{--.legend .five { background-color: #fa93fa; }--}}
{{--</style>--}}
@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Ledgers List</h1>
    </section>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            {{--<i class="fa fa-list"></i><h3 class="box-title">List--}}
            {{----}}
            {{--</h3>--}}
            {{--<ul class="legend">--}}
                {{--<li id="leg"><span class="one"></span> Level One</li>--}}
                {{--<li id="leg"><span class="two"></span> Level Two</li>--}}
                {{--<li id="leg"><span class="three"></span> Level Three</li>--}}
                {{--<li id="leg"><span class="four"></span> Level Four</li>--}}
                {{--<li id="leg"><span class="five"></span> Level Five</li>--}}
            {{--</ul>--}}
           
            <h3 class="box-title">Group Name : <b>{{$group->name}}</b> </h3>
            <a href="{{route('admin.ledgers.index')}}" class="btn btn-success pull-right">Back</a>
           
        </div>
        <!-- /.box-header -->
        <div class="panel-body pad table-responsive">
            <table class="table table-bordered" id="ledger">
                <thead>
                <tr>
                    <th>Number</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th style="text-align: right;">Opening Balance</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @if (count($Ledgers) > 0)
                    @foreach ($Ledgers as $key => $data)
                        
                        {{--@if(isset($data['level']))--}}
                            {{--@if($data['level']==1)--}}
                                {{--@php ($bgcolor= '#83b7f7')--}}
                            {{--@elseif($data['level']==2)--}}
                                {{--@php ($bgcolor = '#aaf783')--}}
                            {{--@elseif($data['level']==3)--}}
                                {{--@php ($bgcolor = '#f28a8a')--}}
                            {{--@elseif($data['level']==4)--}}
                                {{--@php ($bgcolor = '#75f0d5')--}}
                            {{--@elseif($data['level']==5)--}}
                                {{--@php ($bgcolor = '#fa93fa')--}}
                            {{--@else--}}
                                {{--@php ($bgcolor = '#e7fa93')--}}
                            {{--@endif--}}
                        {{--@else--}}
                            {{--@php ($bgcolor = 'white')--}}
                        {{--@endif--}}

                        <tr>
                            <td>
                                {{ $Ledgers[$key]['number'] }}
                            </td>
                            <td>
                                <a href="{{ route('admin.ledgers.edit',$Ledgers[$key]['id']) }}">{{ $data['name'] }} </a>
                            </td>
                            <td>Ledger</td>
                            <td align="right">{{ $DefaultCurrency->symbol . ' ' . $Currency::curreny_format($Ledgers[$key]['opening_balance']) }} </td>
                            <td>
                               
                                <a href="{{ route('admin.ledgers.edit',$Ledgers[$key]['id']) }}" class="btn btn-xs btn-info">@lang('global.app_edit')</a>
                                
                                    {!! Form::open(array(
                                'style' => 'display: inline-block;',
                                'method' => 'DELETE',
                                'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",
                                'route' => ['admin.ledgers.destroy', $Ledgers[$key]['id']])) !!}
                                    {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                            </td>
                        </tr>
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
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('admin.ledgers.mass_destroy') }}';
    </script>
    <script type="text/javascript">
      
          
          $('#ledger').DataTable({
              processing: true,
             
          });
          
      
      </script>
@endsection