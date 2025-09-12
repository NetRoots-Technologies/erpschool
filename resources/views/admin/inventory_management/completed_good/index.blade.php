@extends('admin.layouts.main')

@section('title')
Completed Goods
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
<script>
    $(document).ready(function () {
        'use strict';

        const uri = @json(route('datatable.data.getCompleted'));
        const currectUri = window.location.href;
        const lastIndex = currectUri.split('/').pop();


        let dt = $('#data_table').DataTable({
            ajax: {
                url: uri,
                type: 'POST',
                dataSrc: function (json) {
                    let sData = new Map();
                    json.data.forEach(element => {
                        sData.set(element.id, element);
                    });
                    localStorage.setItem(lastIndex, JSON.stringify([...sData]));
                    return json.data;
                },
                data: function (d) {
                    // d.type = type;
                },
                beforeSend: function (xhr) {
                    let token = $('meta[name="csrf-token"]').attr('content');
                    xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            columns: [{
                data: null,
                title: 'Sr No',
                width: "5%",
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'name',
                title: 'Name'
            },
            {
                data: 'quantity',
                title: 'Quantity'
            },
            {
                data: 'sale_price',
                title: 'Sale Price'
            },
            {
                data: null,
                title: 'Action',
                className: 'text-center',
                orderable: false,
                render: function (data, type, row, meta) {
                    $(".inventoryProductsName").val(row.name);
                    // return `
                    //     <div class="text-center">
                    //         <span class="btn btn-sm btn-warning edit-item" data-id="${row.id}" data-name="${row.name}">
                    //             <i class="fa fa-pencil"></i>
                    //         </span>
                    //     </div>`;
                    return "none"
                }
            }

            ],
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            language: {
                emptyTable: 'No data available in the table.'
            },
            drawCallback: function (settings) { }
        });

    });
</script>
@endsection
