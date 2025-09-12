@extends('admin.layouts.main')

@section('title')
    Departments
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Departments </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal1">Create
                    Department</a>
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
                                    <th>No</th>
                                    <th>Comapny</th>
                                    <th>Branch</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal for create -->
                    <div class="modal" id="createModal1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Create Department</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group">
                                        <form id="createform">
                                            @csrf

                                            <div class="row mt-3">
                                                <div class="row mt-3">

                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label>Select Company</label>
                                                            </div>
                                                            <select name="company_id" class="form-control"
                                                                    id="company_id">
                                                                <option selected>Select Company</option>
                                                                @foreach($company as $item)
                                                                    <option
                                                                        value="{{$item->id}}">{{ $item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label>Select Branch</label>
                                                            </div>
                                                            <select name="branch_id" class="form-control"
                                                                    id="branch_id">
                                                                <option selected>Select Branch</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <div class="input-label">
                                                                <label>Name</label>
                                                            </div>
                                                            <input type="text" required class="form-control" value=""
                                                                   id="name" name="name">
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="row mt-5 mb-3">

                                                <div class="col-12">
                                                    <div class="form-group text-right">
                                                        <input id="create-form-submit" type="submit"
                                                               class="btn btn-primary"
                                                               value="Submit">
                                                        <a href="" class=" btn btn-sm btn-danger modalclose"
                                                           data-dismiss="modal">Cancel </a>
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
                    <div class="modal modal1" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Department</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>
                                <!-- Modal body  -->

                                <div class="modal-body">
                                    <form id="editform">
                                        @csrf
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label>Name</label>
                                                    </div>
                                                    <input type="text" required class="form-control" id="name_edit"
                                                           value="" name="name">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">

                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label>Course</label>
                                                    </div>
                                                    <select name="company_id" class="form-control" id="company_id_edit">
                                                        <option>Select Company</option>
                                                        @foreach($company as $key => $item)
                                                            <option value="{{$item['id']}}"
                                                                    selected>{{$item['name']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label>Branch</label>
                                                    </div>
                                                    <select name="branch_id" class="form-control" id="branch_id_edit">
                                                        <option>Select Branch</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <input type="hidden" name="id" id="edit_id" class="form-control">
                                    </form>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">

                                    <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm"
                                           value="Submit">

                                    <button type="button" class="btn btn-danger btn btn-sm modalclose"
                                            data-dismiss="modal1">Close
                                    </button>
                                </div>
                                </form>

                            </div>
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
                    tableData = $('#file-datatable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "pageLength": 10,
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
                            "url": "{{ route('datatable.get-data-department') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'id', name: 'id'},
                            {data: 'company', name: 'company'},
                            {data: 'branch', name: 'branch'},
                            {data: 'name', name: 'name'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });
                });
                $('#company_id').on('change', function (e) {

                    var id = $(this).val();

                    $.ajax({
                        type: "post",
                        "url": '{!! route('admin.departments.company.get') !!}',
                        data: {
                            id: id,
                            _token: "{{csrf_token()}}"
                        },
                        success: function (response) {
                            $('#branch_id').html(response);

                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                });
                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {
                    e.preventDefault();
                    var name = $('#name').val();
                    var company_id = $('company_id').val();
                    var branch_id = $('branch_id').val();


                    var url = "{{ route('admin.departments.store') }}";
                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),
                        success: function (response) {

                            $("#close").trigger("click");
                            $('#name').val('');
                            $('#branch_id').val('');
                            $('#company_id').val('');
                            tableData.ajax.reload();
                        },
                        error: function () {
                            alert('Error');
                        }
                    });
                    return false;
                });


                $('#file-datatable tbody').on('click', '.branches_edit', function () {
                    var id = $(this).data('branch-edit').id;
                    var name = $(this).data('branch-edit').name;
                    var company_id_edit = $(this).data('branch-edit').company_id;

                    $('#myModal').modal('show');
                    $("#edit_id").val(id);
                    $("#name_edit").val(name);
                    $("#company_id_edit").val(company_id_edit);

                });

                $(".modalclose").click(function () {

                    $('#myModal').modal('hide');
                });

                $(".modalclose").click(function () {

                    $('#createModal1').modal('hide');
                });


                $('#tag-form-submit').on('click', function (e) {


                    e.preventDefault();
                    var id = $('#edit_id').val();
                    var url = "{{ route('admin.branches.index') }}";
                    $.ajax({
                        type: "put",
                        "url": url + '/' + id,
                        data: $('#editform').serialize(),
                        success: function (response) {


                            $('#myModal').modal('hide');

                            tableData.ajax.reload();
                        },
                        error: function () {
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
                            }
                        });
                    }
                });
            </script>
@endsection
