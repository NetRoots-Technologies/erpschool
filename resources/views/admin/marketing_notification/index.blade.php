@extends('admin.layouts.main')

@section('title')
    Notifications
@stop
@section('breadcrumbs')

@stop

@section('content')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Notifications</h1>
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
                        <div class="col-md-4">
                            @if (Gate::allows('students'))

                            <a class="btn btn-success mb-3 float-end" type="submit" style="margin:0px 0px 10px 192px;"
                               href="{!! route('admin.marketing_notification.create') !!}" role="button">Add notification</a>
                               @endif
                        </div>
                    </div>
                    <table id="notification_image-table" class="table table-bordered marketingbanner-table">
                        <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Notification Title</th>
                            <th scope="col">Notification Description</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">

        var tableData = null;
        $(document).ready(function () {
            tableData = $('#notification_image-table').DataTable({
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
                    "url": "{{ route('admin.marketing_notification_getdata') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'notification_title', name: 'notification_title'},
                    {data: 'notification_description', name: 'notification_description'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>

@endsection



