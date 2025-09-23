@extends('admin.layouts.main')
@section('title', 'Assignment Department List')

@section('content')
<div class="container-fluid">
    <div class="row w-100 text-center">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="assignment_list"
                            class="table table-bordered text-nowrap key-buttons border-top-0 border-bottom">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Budget Name</th>
                                    <th>Months</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Assigned Amount</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function () {
    $('#assignment_list').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    { extend: 'excel', exportOptions: { columns: ':visible' } },
                    { extend: 'pdf', exportOptions: { columns: ':visible' } },
                    { extend: 'print', exportOptions: { columns: ':visible' } }
                ]
            },
            'colvis'
        ],
        ajax: {
            url: "{{ route('inventory.List.ofAssignDepartment') }}",
            type: "GET",
            data: { _token: "{{ csrf_token() }}" }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'budget_name', name: 'budget_name' },
            { data: 'month', name: 'month' },
            { data: 'category', name: 'category' },
            { data: 'subcategory', name: 'subcategory' },
            { data: 'amount', name: 'amount' },
        ]
    });
});
</script>
@endsection
