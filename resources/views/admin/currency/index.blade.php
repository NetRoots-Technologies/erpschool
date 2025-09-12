

@extends('admin.layouts.main')


@section('breadcrumbs')

@stop

@section('content')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Currency</h1>
        @if(Session::get('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{Session::get("status")}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </section>
    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"></div>
                        @if (Gate::allows('students'))

                        <div class="col-md-4">
                            <a class="btn btn-success mb-3 float-end" type="submit" style="margin:0px 0px 10px 192px;" href="/admin/currency/add" role="button">Add Currency</a>
                        </div>
                        @endif
                    </div>
                    <table  class="table table-bordered currencies-table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Code</th>
                            <th scope="col">Decimal</th>
                            <th scope="col">Symbols</th>
                            <th scope="col">Rate</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>

{{--                        @foreach($data as $item)--}}
{{--                            <tr>--}}
{{--                                <th scope="row">{{$item->id}}</th>--}}
{{--                                <td>{{$item->name}}</td>--}}
{{--                                <td>{{$item->code}}</td>--}}
{{--                                <td>{{$item->decimal}}</td>--}}
{{--                                <td>{{$item->symbols}}</td>--}}
{{--                                <td>{{$item->rate}}</td>--}}
{{--                                <td>{{$item->status}}</td>--}}
{{--                                <td>--}}
{{--                                    <a href="/admin/currency/delete/{{$item->id}}"><i class="fa fa-trash"></i></a>--}}
{{--                                    <a href="/admin/currency/edit/{{$item->id}}"><i class="fa fa-edit"></i></a>--}}
{{--                                </td>--}}
{{--                            </tr>--}}

{{--                        @endforeach--}}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(function () {
            var table = $('.currencies-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('currencies.getData') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'code', name: 'code'},
                    {data: 'decimal', name: 'decimal'},
                    {data: 'symbols', name: 'symbols'},
                    {data: 'rate', name: 'rate'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });
    </script>

@endsection


{{--@section('content')--}}
{{--    <div class="container">--}}
{{--        <div class="row justify-content-center p-4">--}}
{{--            <i class="fa fa-list"></i>--}}
{{--            <h3 class="box-title">{{__('messages.common.list')}}</h3>--}}
{{--            @if(Gate::check('currencies_create'))--}}
{{--                <button type="button" class="btn-shadow dropdown-toggle btn btn-success pull-right" data-toggle="modal"--}}
{{--                        data-target="#addCurrency">--}}
{{--                            <span class="btn-icon-wrapper pr-2 opacity-7">--}}
{{--                            <i class="fa fa-plus fa-w-20"></i>--}}
{{--                            </span>--}}
{{--                    {{__('messages.currency.title')}}--}}
{{--                </button>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--        <!-- /.box-header -->--}}
{{--        <div class="panel-body pad table-responsive">--}}
{{--            <table class="mb-0 table table-hover currency" id="currency_table">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th>{{__('messages.common.hash')}}</th>--}}
{{--                    <th>{{__('messages.common.name')}}</th>--}}
{{--                    <th>{{__('messages.currency.code')}}</th>--}}
{{--                    <th>{{__('messages.currency.decimal')}}</th>--}}
{{--                    <th>{{__('messages.currency.symbols')}}</th>--}}
{{--                    <th>{{__('messages.currency.rate')}}</th>--}}
{{--                    <th>{{__('messages.common.status')}}</th>--}}
{{--                    <th>{{__('messages.common.created_by')}}</th>--}}
{{--                    <th>{{__('messages.common.date_time')}}</th>--}}

{{--                    <th>Action</th>--}}

{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}

{{--                </tbody>--}}

{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}


