@extends('admin.layouts.main')

@section('title')
Staff Lunch Served
@stop

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center my-4">
        <div class="col-12">
            <div class="card basic-form shadow-sm">
                <div class="card-body table-responsive">
                    <h3>Meal Served List</h3>
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

    const uri = @json(route('datatable.data.empGetAssigned'));
    const assignedRoute = @json(route('inventory.staff_lunch.get_assigned_employee', ['__ID__']));

    let dt = $('#data_table').DataTable({
        ajax: {
            url: uri,
            type: 'POST',
            dataSrc: function (json) {
                let sData = new Map();
                json.data.forEach(element => {
                    sData.set(element.id, element);
                });
                localStorage.setItem('lastIndex', JSON.stringify([...sData]));
                return json.data;
            },
            beforeSend: function (xhr) {
                let token = $('meta[name="csrf-token"]').attr('content');
                xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        columns: [
            {
                data: null,
                title: 'Sr No',
                width: "5%",
                orderable: false,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'user.name',
                title: 'Creator Name',
                defaultContent: 'N/A'
            },
            {
                data: 'branch.name',
                title: 'Branch Name',
                defaultContent: 'N/A'
            },
            {
                data: 'department.name',
                title: 'Department Name',
                defaultContent: 'N/A'
            },
            {
                data: 'batch_type',
                title: 'Batch Type'
            },
            {
                data: 'product.name',
                title: 'Lunch Assigned',
                defaultContent: 'N/A'
            },
            {
                data: 'date',
                title: 'Date Assign',
            },
            {
                data: null,
                title: 'View Assigned List',
                className: 'text-center',
                orderable: false,
                render: function (data, type, row, meta) {
                    let url = assignedRoute.replace('__ID__', row.id);
                    return `
                        <div class="text-center">
                            <a href="${url}" class="btn btn-sm btn-warning">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>`;
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