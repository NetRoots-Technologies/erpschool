@inject('request', 'Illuminate\Http\Request')
@inject('Currency', '\App\Helpers\Currency')
@extends('admin.layouts.main')


@section('content')
<div class="container">
    <div class="row justify-content-center p-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="mb-2 pull-right">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="voucherDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Select a Voucher
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="voucherDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.voucher.gjv_create') }}">GJV - Journal Voucher</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.voucher.crv_create') }}">CRV - Cash Receipt Voucher</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.voucher.cpv_create') }}">CPV - Cash Payment Voucher</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
                <table class="table table-bordered permissions-table" id="entries_table">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Entry Type</th>
                        <th>Voucher Date</th>
                        <th>Number</th>
                        <th width="30%">Narration</th>
                        {{-- <th>Currency</th> --}}
                        <th style="text-align: right;">Dr Amt/Wt</th>
                        <th style="text-align: right;">Cr Amt/Wt</th>
                        <th width="10%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>







@stop

@section('css')

@endsection
@section('js')

    <script>
        $(document).ready(function() {
            $('#entries_table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                ajax: '{!! route('entries.getData') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'entry_type', name: 'entry_type' },
                    { data: 'voucher_date', name: 'voucher_date' },
//                    { data: 'number', name: 'number' },
                    { data: 'number', render: function ( data, type, row ) {
                        return row.voucher_month +'/'+ row.number + '';
                    }},
                    { data: 'narration', name: 'narration' },
                    { data: 'dr_total', name: 'dr_total' },
                    { data: 'cr_total', name: 'cr_total' },
                    { data: 'action', name: 'action' , orderable: false, searchable: false},

                ]
            });

        });

        {{--$(function () {--}}
        {{--    var table = $('#entries_table').DataTable({--}}
        {{--        processing: true,--}}
        {{--        serverSide: true,--}}
        {{--        ajax: "{{ route('entries.getData') }}",--}}
        {{--        columns: [--}}
        {{--            {data: 'id', name: 'id'},--}}
        {{--            {data: 'name', name: 'name'},--}}
        {{--            {data: 'action', name: 'action', orderable: false, searchable: false},--}}
        {{--        ]--}}
        {{--    });--}}

        {{--});--}}
    </script>
@endsection
