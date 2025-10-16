@extends('admin.layouts.main')

@section('title', 'Exam Schedule')

@section('css')
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">

<style>
    .dt-button.buttons-columnVisibility {
        background: blue !important;
        color: white !important;
        opacity: 0.5;
    }

    .dt-button.buttons-columnVisibility.active {
        background: lightgrey !important;
        color: black !important;
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Exam Schedule</h3>
    </div>

    <div class="row mt-4 mb-4">
        <div class="col-12 text-right">
            @if (Gate::allows('ExamSchedules-create'))

            <a href="{{ route('exam.exam_schedules.create') }}" class="btn btn-primary btn-md"><b>Add Exam Schedule</b></a>
            @endif
        </div>
    </div>

    <div class="row w-100 text-center">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body table-responsive">
                    <table class="w-100 table table-bordered" id="data_table">
                        <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Company</th>
                            <th>Branch</th>
                            <th>Exam Term</th>
                            <th>Test Type</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Component</th>
                            <th>Marks</th>
                            <th>Grade</th>
                            <th>Pass</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection
@section('js')
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    {{--<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>--}}
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    {{--<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>--}}

<script>
    $(document).ready(function () {
        let dataTable = $('#data_table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'collection',
                    text: 'Export',
                    className: 'btn-light',
                    buttons: [
                        { extend: 'excel', exportOptions: { columns: ':visible' }},
                        { extend: 'pdf', exportOptions: { columns: ':visible' }},
                        { extend: 'print', exportOptions: { columns: ':visible' }}
                    ]
                },
                {
                    extend: 'collection',
                    text: 'Bulk Action',
                    className: 'btn-light',
                    buttons: [
                        {
                            text: '<i class="fas fa-trash"></i> Delete',
                            className: 'btn btn-danger delete-button',
                            action: function () {
                                let selectedIds = [];

                                $('.select-checkbox:checked').each(function () {
                                    selectedIds.push($(this).val());
                                });

                                if (selectedIds.length > 0) {
                                    $('.dt-button-collection').hide();
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: 'You are about to delete selected records!',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes, delete!',
                                        cancelButtonText: 'Cancel'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $.ajax({
                                                url: '{{ route("exam.exam_schedules.bulk_delete") }}',
                                                type: 'POST',
                                                data: {
                                                    ids: selectedIds,
                                                    _token: "{{ csrf_token() }}"
                                                },
                                                success: function () {
                                                    dataTable.ajax.reload();
                                                    toastr.success('Selected records deleted.');
                                                },
                                                error: function () {
                                                    toastr.error('Failed to delete records.');
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    toastr.warning('No items selected.');
                                }
                            }
                        }
                    ]
                },
                {
                    extend: 'colvis',
                    columns: ':not(:first-child)'
                }
            ],
            ajax: {
                url: '{{ route("datatable.getExamSchedule") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' }
            },
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'company', name: 'company' },
                { data: 'branch', name: 'branch' },
                { data: 'exam_term', name: 'exam_term' },
                { data: 'test_type', name: 'test_type' },
                { data: 'class', name: 'class' },
                { data: 'subject', name: 'subject' },
                { data: 'component', name: 'component' },
                { data: 'marks', name: 'marks' },
                { data: 'grade', name: 'grade' },
                { data: 'pass', name: 'pass' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });

    function checkAll(source) {
        $('.select-checkbox').prop('checked', source.checked);
    }
</script>
@endsection
