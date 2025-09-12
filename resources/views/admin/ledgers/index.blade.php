@inject('request', 'Illuminate\Http\Request')
@inject('Currency', '\App\Helpers\Currency')
@extends('admin.layouts.main')

{{-- <style>
    #ledger_table th,
    #ledger_table td {
        width: calc(100% / 4);
    }

    #ledger_table th:nth-child(1),
    #ledger_table td:nth-child(1) {
        width: 1% !important;
    }
</style> --}}

@section('content')
{{-- <div class="box box-primary">--}}
    {{-- <div class="box-header with-border">--}}
@if (Gate::allows('students'))
        {{-- <a href="{{ route('admin.ledgers.create') }}" class="btn btn-success pull-right">Add New Ledger</a>--}}
        @endif
        {{-- </div>--}}
    {{--
    <!-- /.box-header -->--}}
    {{-- <div class="panel-body pad table-responsive">--}}
        {{-- <table class="table table-bordered">--}}
            {{-- <thead>--}}
                {{-- <tr>--}}
                    {{-- <th>Number</th>--}}
                    {{-- <th>Name</th>--}}
                    {{-- <th>Type</th>--}}
                    {{-- <th style="text-align: right;">Opening Balance (PKR)</th>--}}
                    {{-- <th style="text-align: right;">Opening Balance (USD)</th>--}}
                    {{-- <th style="text-align: right;">Opening Balance (Gold)</th>--}}
                    {{-- <th>Actions</th>--}}
                    {{-- </tr>--}}
                {{-- </thead>--}}
            {{-- <tbody>--}}
                {{-- @if (count($Ledgers) > 0)--}}
                {{-- @foreach ($Ledgers as $id => $data)--}}
                {{-- @if ($id == 0) @continue; @endif--}}
                {{-- <tr>--}}
                    {{-- <td>--}}
                        {{-- @if ($id < 0)--}} {{-- <?php echo $data['number'] ?>--}}
                            {{-- @else--}}
                            {{--
                            <?php echo $data['number'] ?>--}}
                            {{-- @endif--}}
                            {{-- </td>--}}
                    {{-- <td>--}}

                        {{-- @if ($id < 0)--}} {{-- <?php echo $data['name'] ?>--}}
                            {{-- @else--}}
                            {{-- --}}{{--@if(Gate::check('ledgers_edit'))--}}
                            {{-- <a href="{{ route('admin.ledgers.edit',[$id]) }}">
                                <?php echo $data['name'] ?>
                            </a>--}}
                            {{-- --}}{{--@endif--}}
                            {{-- @endif--}}
                            {{-- --}}{{--{{ $data['name'] }}--}}
                            {{-- </td>--}}
                    {{-- <td>@if ($id < 0) Group @else Ledger @endif</td>--}}
                            {{--
                    <td align="right">@if ($id < 0) N/A @else {{ $Currency::curreny_format($data['opening_balance']) }}
                            @endif</td>--}}
                            {{--
                    <td align="right">@if ($id < 0) N/A @else {{ $Currency::curreny_format($data['dl_opening_balance'])
                            }} @endif</td>--}}
                            {{--
                    <td align="right">@if ($id < 0) N/A @else {{ $Currency::curreny_format($data['gl_opening_balance'])
                            }} @endif</td>--}}
                            {{--
                    <td>--}}
                        {{-- @if ($id > 0)--}}
                        {{-- --}}{{--@if(Gate::check('ledgers_edit'))--}}
                        {{-- <a href="{{ route('admin.ledgers.edit',[$id]) }}"
                            class="btn btn-xs btn-info">@lang('global.app_edit')</a>--}}
                        {{-- --}}{{--@endif--}}

                        {{-- --}}{{--@if(Gate::check('ledgers_destroy'))--}}
                        {{-- {!! Form::open(array(--}}
                        {{-- 'style' => 'display: inline-block;',--}}
                        {{-- 'method' => 'DELETE',--}}
                        {{-- 'onsubmit' => "return confirm('".trans("global.app_are_you_sure")."');",--}}
                        {{-- 'route' => ['admin.ledgers.destroy', $id])) !!}--}}
                        {{-- {!! Form::submit(trans('global.app_delete'), array('class' => 'btn btn-xs btn-danger'))
                        !!}--}}
                        {{-- {!! Form::close() !!}--}}
                        {{-- --}}{{--@endif--}}
                        {{-- @else--}}
                        {{-- N/A--}}
                        {{-- @endif--}}
                        {{-- </td>--}}
                    {{--
                </tr>--}}
                {{-- @endforeach--}}
                {{-- @else--}}
                {{-- <tr>--}}
                    {{-- <td colspan="3">@lang('global.app_no_entries_in_table')</td>--}}
                    {{-- </tr>--}}
                {{-- @endif--}}
                {{-- </tbody>--}}
            {{-- </table>--}}
        {{-- </div>--}}
    {{--
</div>--}}
{{-- <div class="row w-100 text-center">
    <div class="card basic-form table-responsive">
        <div class="card-body">
            <div class="row mb-5 d-flex flex-row-reverse" id="apply_filter">
                <div class="col-lg-3 p-2">
                    <button class="btn btn-outline-primary btn">Filters</button>

                </div>
            </div>
            <div class="row">
                <div class="filter   mb-5" id="filter" style="display: none">
                    <div class="card">
                        <div class="card-body">
                            <form id="filterform" method="post">


                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="group"><b>Group</b></label>
                                        <select name="group" class="  form-control" id="group_id" required>
                                            <option value="" selected disabled>Select Group</option>
                                            {!! $Groups !!}
                                        </select>
                                    </div>

                                </div>
                                <div class="row mb-2">

                                    <div class="col-lg-6" style="    margin: 34px 0px 0px -138px;">
                                        <button type="submit" class="btn btn-sm btn-primary">Apply Filter
                                        </button>
                                        <button type="button" id="reset" class="mr-4 btn-sm btn btn-primary">Reset
                                        </button>
                                    </div>
                                    <div class="col-lg-3 mt-3">

                                    </div>
                                    @csrf
                                </div>
                                <div class="row">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- <div class="row w-100 text-center">
    <div class=" justify-content-center p-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8"></div>
                    @if (Gate::allows('Ledgers-create'))
                    <div class="col-md-4">
                        <a href="{{ route('admin.ledgers.create') }}" class="btn btn-success mb-3 float-end"
                            type="submit">Add New Ledger</a>
                    </div>
                    @endif
                </div>

                <table class="table table-bordered table-responsive" id="ledger_table">
                    <thead>
                        <tr>
                            <th class="ledger_id_col">Id</th>
                            <th>Name</th>
                            <th>Group</th>
                            <th>Number</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div> --}}

<div class="container-fluid">
    <div class="card p-4">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs mb-4" id="coaTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="all-accounts-tab" data-bs-toggle="tab" href="#general-ledger"
                            role="tab">General Ledger</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link " id="listing-tab" data-bs-toggle="tab" href="#vendors-listing"
                            role="tab">Vendor
                            Listing</a>
                    </li>
                </ul>
                <div class="tab-content overflow-visible">
                    <div class="tab-pane fade show active" id="general-ledger" role="tabpanel">
                        <form method="POST">
                            <label>Detail Type</label>

                            <div class="row">
                                <div class="col-md-6">
                                    <select name="coa" class="form-select select2" id="coa">
                                        @foreach ($coa as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="tab-pane fade " id="vendors-listing" role="tabpanel">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


@stop

@section('css')
    <style>
        .nav-tabs .nav-link.active {
            color: #0d6efd !important;
            border: 1px solid #0d6efd !important;
            background-color: #eaf4ff;
            font-weight: bold;
        }

        .accordion .dropdown .dropdown-toggle::after {
            display: none;
        }

        .accordion ul {
            list-style-type: none !important;
        }
    </style>
@endsection

@section('js')
    {{--
    <script>
        var tableData;

        $("#apply_filter").click(function () {
            $('#filter').toggle();
        });

        $(document).on("submit", "#filterform", function (event) {
            var formData = new FormData(this);
            event.preventDefault();

            $('#ledger_table').DataTable().destroy();
            tableData = $('#ledger_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                "columnDefs": [
                    { "visible": false }
                ],

                ajax: {
                    "url": "{{ route('datatable.get-data-ledger') }}",
                    "type": "get",
                    'data': {
                        group: $('#group_id').val(),

                    },

                },
                "columns": [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'groups', name: 'groups' },
                    { data: 'number', name: 'number' },
                ]

            });
        });

        $("#reset").click(function () {
            $('#ledger_table').DataTable().destroy();
            $('#group_id').val(null);
            tableData = $('#ledger_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                "columnDefs": [
                    { "visible": false }
                ],
                ajax: {
                    "url": "{{ route('datatable.get-data-ledger') }}",
                    "type": "get",
                    "data": { _token: "{{csrf_token()}}" }
                },
                "columns": [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'groups', name: 'groups' },
                    { data: 'number', name: 'number' },
                ]
            });
        });


        $(document).ready(function () {

            tableData = $('#ledger_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                "columnDefs": [
                    { "visible": false }
                ],
                ajax: {
                    "url": "{{ route('datatable.get-data-ledger') }}",
                    "type": "get",
                    "data": { _token: "{{csrf_token()}}" }
                },
                "columns": [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'groups', name: 'groups' },
                    { data: 'number', name: 'number' },

                ]
            });
        });
    </script> --}}

@endsection