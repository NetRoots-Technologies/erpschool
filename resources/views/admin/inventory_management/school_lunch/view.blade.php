@extends('admin.layouts.main')

@section('title')
    Student Lunch Served
@stop

@section('content')

    <div class="container-fluid">

        <div class="row justify-content-center my-4">
            <div class="col-12">
                <div class="card basic-form shadow-sm">
                    <div class="card-body table-responsive">
                        <h3>Lunch Served List</h3>
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
        $(document).ready(function() {
            'use strict';

            const uri = @json(route('datatable.data.getAssigned'));

            // If your route URI is .../{class_id}/{section_id}/{data}
            const assignedRoute =
                "{{ route('inventory.school_lunch.get_assigned_student', [
                    'class_id' => '__CLASS__',
                    'section_id' => '__SECTION__',
                ]) }}";

            let dt = $('#data_table').DataTable({
                ajax: {
                    url: uri,
                    type: 'POST',
                    dataSrc: function(json) {
                        let sData = new Map();
                        json.data.forEach(element => sData.set(element.id, element));
                        localStorage.setItem('lastIndex', JSON.stringify([...sData]));
                        return json.data;
                    },
                    beforeSend: function(xhr) {
                        let token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
                columns: [{
                        data: null,
                        title: 'Sr No',
                        width: '5%',
                        orderable: false,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'user_name',
                        title: 'Creator Name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'branch_name',
                        title: 'Branch Name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'class_name',
                        title: 'Class Name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'section_name',
                        title: 'Section Name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'type',
                        title: 'Batch Type'
                    },
                    {
                        data: 'assign_date',
                        title: 'Date Assign'
                    },

                    {
                        data: null,
                        title: 'View Assigned List',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            // Build the URL by replacing placeholders
                            const url = assignedRoute
                                .replace('__CLASS__', row.class_id)
                                .replace('__SECTION__', row.section_id)
                                .replace('__DATA__', (row.assign_date));

                            return `
                        <div class="text-center">
                            <a href="${url}" class="btn btn-sm btn-warning view-meal">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    `;
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
                drawCallback: function(settings) {}
            });
        });

        // You don't need this click handler since <a> already has href.
        // If you want JS navigation instead, you could use data-url:

        $(document).on('click', 'a.view-meal', function(e) {
            e.preventDefault();
            window.location.href = $(this).attr('href');
        });
    </script>

@endsection
