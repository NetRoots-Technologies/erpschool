@extends('admin.layouts.main')

@section('title')
GRN
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center my-4">
        <div class="col-12">
            <div class="card basic-form shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped mb-0" id="data_table">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript" defer>
    $(document).ready(function () {
        'use strict';

        const type = @json($type);
        const uri = @json(route('datatable.data.purchaseOrder'));
        const grn = @json(route('inventory.grn.Detail'));

        const dt = $('#data_table').DataTable({

            ajax: {
                url: uri,
                type: 'POST',
                dataSrc: function (json) {
                    localStorage.setItem('data_table', JSON.stringify(json.data))
                    return json.data.filter(x => x.delivery_status == "COMPLETED");
                },
                data: function (d) {
                    d.type = type
                },
                beforeSend: function (xhr) {
                    var token = $('meta[name="csrf-token"]').attr('content');
                    xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            columns: [
                {
                    data: null, title: 'Sr No', width: "7%", orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'supplier', title: 'Supplier name', width: "10%",
                    render: function (data, type, row, meta) {
                        return row.supplier.name;
                    },
                },
                {
                    data: 'branch', title: 'branch name', width: "10%",
                    render: function (data, type, row, meta) {
                        return row.branch.name;
                    },
                },
                {
                    data: 'delivery_status', title: 'Delivery Status', width: "10%",
                    render: function (data, type, row, meta) {
                        return `<span class="badge bg-warning rounded-pill m-1">${row.delivery_status}</span>`;
                    },
                },
                { data: 'order_date', title: 'Order date', width: "11%", },
                { data: 'delivery_date', title: 'Delivery date', width: "11%", },
                {
                    data: null, title: 'Action', width: "10%", orderable: false,
                    render: function (data, type, row, meta) {
                        return `<a href="${grn}/${row.id}" target="_blank" class="btn btn-sm btn-primary grn">Generate GRN</a> `;
                    }
                },
            ],
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            language: {
                emptyTable: 'No data available in the table.'
            },
            // dom: `<"row"<"col-md-6"l><"col-md-6 text-end"B>>tipr`,
            // buttons: [
            //     {
            //         text: 'Add Item',
            //         className: 'btn btn-primary',
            //         action: function (e, dt, node, config) {
            //             $('#iModal').modal('show');
            //         }
            //     }
            // ],
            drawCallback: function (settings) {
            }
        });

        $('#data_table').on('click','.grn', function(){
            console.log("🚀 ~ $(this).parents('tr')>>", $(this).parents('tr'))
            let data = dt.row($(this).parents('tr')).data();
            console.log(data);
        })

})
</script>
@endsection