@extends('admin.layouts.main')

@section('title')
    Session Video
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4">Session Videos </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                @if (Gate::allows('course-video-create'))
                    <a href="{!! route('admin.video_category.index') !!}" class="btn btn-primary btn-sm ">Create Video
                        Category</a>
                    <a href="{!! route('admin.video.create') !!}?id={!! $id !!}" class="btn btn-primary btn-sm ">Create
                        Video Links</a>
                @endif
                <a href="{!! route('admin.course.index') !!}" class="btn btn-primary btn-sm ">Back</a>
            </div>
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <table class="w-100 table border-top-0 table-bordered   border-bottom " id="data_table">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Video ID</th>
                                <th>Video Description</th>
                                <th>Session</th>
                                <th>Video Category</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('js')

    <script type="text/javascript">

        $(document).ready(function () {
            $('#data_table').DataTable({
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
                    {"visible": false}
                ],
                ajax: {
                    "url": "{{ route('datatable.get_data_video') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}", id:{!! $id !!}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'video_id', name: 'video_id'},
                    {data: 'video_description', name: 'video_description'},
                    {data: 'session', name: 'session'},
                    {data: 'video_heading', name: 'video_heading'},

                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection
