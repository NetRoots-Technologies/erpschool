@extends('admin.layouts.main')

@section('title')
Permissions
@stop

@section('content')
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Permissions </h3>
    </div>
    <div class="row    mt-4 mb-4 ">
        @if (Gate::allows('Permissions-create'))
        <div class="col-12 text-right">
            <button type="button" id="create-form" class="btn btn-primary btn-md">Create Permission</button>
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
                                    <th class="heading_style">Main</th>
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
        <div class="modal" id="createModal1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Create</h4>
                        <button type="button" class="close modalclose" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="form-group">
                            <form id="createform">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Name</label>
                                            </div>
                                            <input type="text" required class="form-control" value="" id="name"
                                                name="name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Main</label>
                                            </div>
                                            <select required id="main_check" value="main" name="main"
                                                class="form-control">
                                                <option value="">Select Option</option>
                                                <option value="1">Mian</option>
                                                <option value="0">Child</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12" id="parrent">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Main Permission</label>
                                            </div>
                                            <select required id="parrent_val" name="parrent" class="form-control">
                                                <option value="">Select Option</option>
                                                @foreach($mainpermissions as $item)
                                                <option value="{!! $item->id !!}">{!! $item->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">
                                            <input id="create-form-submit" type="submit" data-bs-dismiss="modal"
                                                class="btn btn-primary" value="Submit">
                                            <a class=" btn btn-danger modalclose" data-dismiss="modal">Cancel
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal for Edit -->

        <div id="myModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
            aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Permission</h4>
                        <button type="button" class="close modalclose" data-dismiss="modal1">&times;
                        </button>
                    </div>
                    <form id="editform">
                        @csrf
                        <div class="modal-body" id="modal-body">


                        </div>

                    </form>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm"
                            data-dismiss="modal" value="Submit">
                        <button type="button" class="btn btn-danger modalclose btn btn-sm" data-dismiss="modal1">
                            Close
                        </button>
                    </div>
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
                        $('#main_check').change(function () {
                            if ($('#main_check').val() == 1) {
                                $("#parrent").addClass("d-none");
                                $("#parrent_val").prop('required', false);
                            } else {
                                $("#parrent").removeClass("d-none");
                                $("#parrent_val").prop('required', true);
                            }
                        });
                    });


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
                                "url": "{{ route('datatable.get-data-permission') }}",
                                "type": "POST",
                                "data": {_token: "{{csrf_token()}}"}
                            },
                            "columns": [
                                {data: 'id', name: 'id'},
                                {data: 'name', name: 'name'},
                                {data: 'main', name: 'main'},
                                {data: 'action', name: 'action', orderable: false, searchable: false},
                            ]
                        });
                    });


                    //Create Form Open
                    $('#create-form').on('click', function (e) {

                        $('#createModal1').modal('show');

                        {{--var url = "{{ route('permissions.create')}}";--}}
                        {{--$.ajax({--}}
                        {{--    type: "get",--}}
                        {{--    "url": url,--}}
                        {{--    success: function (response) {--}}
                        {{--        $("#modal-body-create").html('');--}}
                        {{--        $("#modal-body-create").html(response);--}}
                        {{--    },--}}
                        {{--    error: function () {--}}
                        {{--        alert('Error');--}}
                        {{--    }--}}
                        {{--});--}}
                    });


                    $('#create-form-submit').on('click', function (e) {

                        e.preventDefault();
                        var url = "{{ route('permissions.store') }}";
                        $.ajax({
                            type: "post",
                            "url": url,
                            data: $('#createform').serialize(),
                            success: function (response) {
                                var name = $('#name').val('');


                                var main_check = $('#main_check').val('');

                                var parrent_val = $('#parrent_val').val('Select Option');
                                $('#createModal1').modal('hide');
                                $('#createModal1').removeData('modal');
                                tableData.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Permission Added successfully.',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            },
                            error: function () {
                                alert('Error');
                            }
                        });
                        return false;
                    });


                    $('#file-datatable tbody').on('click', '.permission_edit', function () {


                        var url = "   {{ route('permissions.index') }}";
                        var id = $(this).data('permission-edit').id;
                        var name = $(this).data('permission-edit').name;
                        var main = $(this).data('permission-edit').main;
                        var parent_id = $(this).data('permission-edit').parent_id;

                        $("#permission_id_edit").val(id);
                        $("#permission_name_edit").val(name);
                        $("#permission_main_edit").val(main);
                        $("#permission_parent_id_edit").val(parent_id);


                        $.ajax({
                            type: "get",
                            "url": url + '/' + id + '/edit',

                            success: function (response) {

                                $("#modal-body").html(' ');
                                $("#modal-body").html(response);

                            },
                            error: function () {
                                alert('Error');
                            }
                        });
                        $('#myModal').modal('show');


                    });


                    $('#tag-form-submit').on('click', function (e) {

                        e.preventDefault();
                        var route_edit = $('#route_edit').val();


                        $.ajax({
                            type: "put",
                            "url": route_edit,
                            data: $('#editform').serialize(),
                            success: function (response) {
                                // alert(response['response']);


                                $('#myModal').modal('hide');

                                tableData.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Permission Updated successfully.',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            },
                            error: function () {
                                alert('Error');
                            }
                        });
                        return false;
                    });

                    $(".modalclose").click(function () {

                        $('#myModal').modal('hide');
                    });

                    $(".modalclose").click(function () {

                        $('#createModal1').modal('hide');
                    });

                    $('#file-datatable tbody').on('click', '.delete', function (e) {
                        e.preventDefault();
                        var route = $(this).data('route');

                        var a = confirm('Are you sure you want to Delete this permission?');
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
                                        text: 'Permission deleted successfully.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                },
                                error: function (xhr, status, error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'Failed to delete permission: ' + (xhr.responseJSON?.message || error),
                                        showConfirmButton: true
                                    });
                                }
                            });
                        }
                    });
                    //Modal CLose
                    $(".modalclose").click(function () {

                        $('#createModal1').modal('hide');
                    });
                    $(".modalclose").click(function () {

                        $('#myModal').modal('hide');
                    });
    </script>

    @endsection
