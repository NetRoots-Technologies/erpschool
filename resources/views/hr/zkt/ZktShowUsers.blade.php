@extends('admin.layouts.main')

@section('title')
    Users
@stop

@section('content')
    <div class="container-fluid">
{{--        <div class="row w-100  mt-4 ">--}}
{{--            <h3 class="text-22 text-center text-bold w-100 mb-4"> Show Users </h3>--}}
{{--        </div>--}}
        <div class="row    mt-4 mb-4 ">
            <div class="col-12 text-right">
                {{--                <a href="{!! route('hr.agent.create') !!}" class="btn btn-primary btn-sm ">Create Agent</a>--}}
                {{--                <a href="#" class="btn btn-primary btn-sm ">Create Agent</a>--}}
                <a class="btn btn-primary btn-sm " id="create-form" data-toggle="modal" data-target="#createModal1">Create
                    User </a>
            </div>
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                            <thead>
                            <tr>
                                <th class="heading_style">No</th>
                                <th>Uid</th>
                                <th>User Id</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Password</th>
                                <th>Cardno</th>
                                <th>Actions</th>
                            </tr>

                            </thead>
                            <tbody>
                            @php $i = 1;@endphp
                            @foreach($users as $item)
                                <tr>
                                    <td>{!! $i++ !!}</td>
                                    <td>{!! $item['uid'] !!}</td>
                                    <td>{!! $item['userid'] !!}</td>
                                    <td>{!! $item['name'] !!}</td>
                                    <td>{!! $item['role'] !!}</td>
                                    <td>{!! $item['password'] !!}</td>
                                    <td>{!! $item['cardno'] !!}</td>
                                    <td>
                                        <form methode="post" action="{!! route('zkt-delete-user')!!}">
                                            <input name="uid" value="{!! $item['uid'] !!}" hidden/>
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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

            //  deleteing Attendance
            <script>
                $('#datatable tbody').on('click', '.delete', function () {
                    {
                        alert(hello);
                        var route = $(this).data('route');
                        if (confirm('Are you sure ,you want to delete Attendance?')) {
                            $.ajax({
                                url: route,
                                type: "delete",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function (result) {
                                    tableData.ajax.reload();
                                }
                            });
                        }
                    }
                );
            </script>
@endsection
