@extends('admin.layouts.main')

@section('title')
Roles
@stop
@section('content')
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Roles </h3>
    </div>
    <div class="row    mt-4 mb-4 ">
        @if (Gate::allows('Roles-create'))
        <div class="col-12 text-right">
            <a class="btn btn-primary btn-md text-white" id="create-form" data-toggle="modal"
                data-target="#createModal1">Create
                Roles</a>
        </div>
        @endif
    </div>
    <div class="row w-100 text-center">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file-datatable"
                            class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                            <thead>
                                <tr>
                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Name</th>
                                    <th class="heading_style">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for create -->
        <div class="modal fade bd-example-modal-lg" id="createModal1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Create</h4>
                        <button type="button" id="close" class="close modalclose" data-dismiss="modal">&times;
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body" id="modal-body-create">
                        <div class="form-group">
                            <div class="container" id="modal-body-create">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal for Edit -->

    <div id="editmyModal" class="modal fade bd-example-modal-lg edit-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Role</h4>
                    <button type="button" class="close modalclose" data-dismiss="modal1">&times;
                    </button>
                </div>
                <form id="editform">
                    @csrf
                    <div class="modal-body" id="modal-body">

                    </div>
                    <div class="row ">
                        <div class="col-12">
                            <div class="form-group text-center">
                                <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm"
                                    value="Submit">
                                <button type="button" class="btn btn-danger modalclose btn btn-sm"
                                    data-dismiss="modal1">
                                    Close
                                </button>

                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@stop
@section('css')

@endsection
@section('js')

<script type="text/javascript">
    var tableData = null;

        $(document).ready(function () {

            tableData = $('#file-datatable').DataTable({
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
                    "url": "{{ route('datatable.get-data-role') }}",
                    "type": "POST",
                    "data": {_token: "{{csrf_token()}}"}
                },
                "columns": [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
        $(document).on("submit", "#rolecreate", function (event) {
            event.preventDefault();


            var name = $('#name').val();

            var permisions = $('#permisions').val();


            var url = "{{ route('roles.store') }}";
            $.ajax({
                type: "post",
                "url": url,
                data: $('#rolecreate').serialize(),

                success: function (response) {
                    $("#close").trigger("click");
                    // $('#createModal1').modal('hide');
                    tableData.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Role Added successfully.',
                        timer: 1000,
                        showConfirmButton: false
                    });
                },
                error: function () {
                    $('#createModal1').modal('hide');
                    alert('Error');
                }
            });
            return false;
        });


        $('#file-datatable tbody').on('click', '.role_edit', function () {
            var loader = $('<div class="loader"></div>').appendTo('.edit-modal');

            var url = @json(route('roles.index'));
            var id = $(this).data('id');


            $.ajax({
                type: "get",
                "url": `${url}/${id}/edit`,
                success: function (response) {
                    loader.remove();
                    $("#modal-body").html(' ');
                    $("#modal-body").html(response);
                },
                error: function () {
                    alert('Error');
                }
            });


            $('#editmyModal').modal('show');


        });

        $(".modalclose").click(function () {

            $('#editmyModal');
        });

        $(".modalclose").click(function () {

            $('#createModal1').modal('hide');
        });


        $('#tag-form-submit').on('click', function (e) {


            var loader = $('<div class="loader"></div>').appendTo('body');

            e.preventDefault();
            var id = $('#id').val();

            var url = @json(route('roles.index'));
            $.ajax({
                type: "post",
                "url": url + '/' + id,
                data: $('#editform').serialize() + '&_method=PUT',
                success: function (response) {
                    loader.remove();
                    $('.modal').each(function () {
                        $(this).modal('hide');
                    });
                    // $('#myModal').modal('hide');
                    tableData.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Role Updated successfully.',
                        timer: 1000,
                        showConfirmButton: false
                    });
                },
                error: function () {
                    loader.remove();

                    alert('Error');
                }
            });
            return false;
        });

        $('#file-datatable tbody').on('click', '.delete', function () {

            var data = $(this).data('id');

            $('#' + data).submit();

        });

        $(document).on("submit", ".delete_form", function (event) {

            event.preventDefault();
            var route = $(this).data('route');


            var a = confirm('Are you sure you want to Delete this?');
            if (a) {

                $.ajax({
                    url: route,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (result) {

                        tableData.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Role Deleted successfully.',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                });

            }
        });


        $('#create-form').on('click', function (e) {

            var url = "   {{ route('roles.create') }}";


            $.ajax({
                type: "get",
                "url": url,


                success: function (response) {

                    $("#modal-body-create").html(' ');
                    $("#modal-body-create").html(response);

                },
                error: function () {
                    alert('Error');
                }
            });


            $('#myModal').modal('show');


        });

</script>
@endsection
