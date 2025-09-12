@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('breadcrumbs')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Groups</h1>
    </section>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <i class="fa fa-list"></i><h3 class="box-title">List</h3>
@if (Gate::allows('students'))
                <a href="{{ route('admin.groups.create') }}" class="btn btn-success pull-right">Add New Group</a>
            @endif
        </div>
        <!-- /.box-header -->
        <div class="panel-body pad table-responsive">
            <table class="table table-bordered table-striped" id="groups_table">
                <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th>Level</th>
                    <th width="20%">Account Number</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function() {

            $('#groups_table').DataTable({
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

                "ajax": "{{ route('admin.groups.getData') }}",
                "columns":[
                    { "data": "id" },
                    { "data": "level" },
                    { "data": "number" },
                    { "data": "name" },
                    { "data": "action" },
                ]
            });
        });
    </script>
@endsection
