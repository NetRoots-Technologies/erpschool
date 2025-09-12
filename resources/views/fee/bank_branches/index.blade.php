@extends('admin.layouts.main')

@section('title')
    Bank Branches
@stop

@section('content')
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Bank Branches </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
        @if (Gate::allows('BanksBranches-create'))
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1"><b>Create
                        Bank Branches</b></a>
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
                                    <th class="heading_style">Bank</th>
                                    <th class="heading_style">Branch Name</th>
                                    <th class="heading_style">Branch Code</th>
                                    <th class="heading_style">Status</th>
                                    <th class="heading_style">Action</th>
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
                                    <h4 class="modal-title">Create Bank Branch</h4>
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
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label>Select Bank</label>
                                                        </div>
                                                        <select name="bank_id" class="form-control company_id"
                                                                id="company_id">
                                                            <option selected disabled>Select Bank</option>
                                                            @foreach($banks as $item)
                                                                <option
                                                                    value="{{$item->id}}">{{ $item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">

                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label>Branch Name</label>
                                                        </div>
                                                        <input type="text" name="branch_name" class="form-control branch_name">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label>Branch Code</label>
                                                        </div>
                                                        <input type="text" name="branch_code" class="form-control branch_code">
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
                                    <h4 class="modal-title">Edit Bank Branch</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>
                                <!-- Modal body  -->

                                <div class="modal-body">
                                    <form id="editform">
                                        @csrf


                                        <div class="row">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label>Bank</label>
                                                        </div>
                                                        <select name="bank_id" class="form-control company_id"
                                                                id="bank_id_edit">
                                                            <option>Select Bank</option>
                                                            @foreach($banks as $key => $item)
                                                                <option value="{{$item['id']}}"
                                                                        selected>{{$item['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-4">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label>Branch Name</label>
                                                        </div>
                                                        <input type="text" name="branch_name" id="branch_name_edit"
                                                               class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="input-label">
                                                            <label>Branch Code</label>
                                                        </div>
                                                        <input type="text" name="branch_code" id="branch_code_edit"
                                                               class="form-control">
                                                    </div>
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
        <style>
            .error
            {
                color: red;
                font-size: 14px;
                font-weight: 500;
            }
        </style>
        @endsection
        @section('js')

            <script type="text/javascript">
                var tableData = null;
                $(document).ready(function () {

                    $("#createform").validate({
                        rules: {
                            bank_id: {
                                required: true,
                            },
                            branch_name: {
                                required: true,
                            },
                            branch_code: {
                                required: true,
                            },
                        },
                        messages: {
                            bank_id: {
                                required: "Please select bank",
                            },
                            branch_name: {
                                required: "Please enter branch name",
                            },
                            branch_code: {
                                required: "Please enter branch code",
                            },
                        }
                    });

                    $("#editform").validate({
                        rules: {
                            bank_id: {
                                required: true,
                            },
                            branch_name: {
                                required: true,
                            },
                            branch_code: {
                                required: true,
                            },
                        },
                        messages: {
                            bank_id: {
                                required: "Please select bank",
                            },
                            branch_name: {
                                required: "Please enter branch name",
                            },
                            branch_code: {
                                required: "Please enter branch code",
                            },
                        }
                    });

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
                            "url": "{{ route('datatable.get-data-bank-branch') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [
                            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                            {data: 'bank', name: 'bank'},
                            {data: 'branch_name', name: 'branch_name'},
                            {data: 'branch_code', name: 'branch_code'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ]
                    });
                });
                $('.company_id').on('change', function (e) {

                    var id = $(this).val();
                    var loader = $('<div class="loader"></div>').appendTo('body');

                    $.ajax({
                        type: "post",
                        "url": '{!! route('admin.departments.company.get') !!}',
                        data: {
                            id: id,
                            _token: "{{csrf_token()}}"
                        },
                        success: function (response) {
                            loader.remove();
                            $('.branch_id').html(response);
                        },
                        error: function () {
                            loader.remove();
                        }
                    });
                });
                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {
                    e.preventDefault();
                    var loader = $('<div class="loader"></div>').appendTo('body');

                    if (!$('#createform').valid()) {
                        return false;
                    }

                    var url = "{{ route('admin.banks_branches.store') }}";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $('#createform').serialize(),
                        success: function (response) {
                            loader.remove();

                            $("#close").trigger("click");
                            $('.branch_name').val('');
                            $('.branch_code').val('');
                            $('#categorySelect').val(null).trigger('change');

                            tableData.ajax.reload();

                            toastr.success('Bank Branch Added successfully.');
                        },
                        error: function (xhr, status, error) {
                            loader.remove();

                            if (xhr.responseJSON) {
                                console.error('Error Response:', xhr.responseJSON);
                                toastr.error('Error while adding bank branch: ' + xhr.responseJSON.message || 'Unknown error');
                            } else {
                                console.error('Error status:', status);
                                console.error('Error details:', error);
                                toastr.error('Error while adding bank branch. Please try again later.');
                            }
                        }
                    });
                    return false;
                });


                $('#file-datatable tbody').on('click', '.bank_branch_edit', function () {
                    //alert(2);
                    var branch_edit = $(this).data('bank_branch-edit').branch_id;
                    console.log(branch_edit);
                    var id = $(this).data('bank_branch-edit').id;
                    var branch_name = $(this).data('bank_branch-edit').branch_name;

                    var branch_code = $(this).data('bank_branch-edit').branch_code;
                    var bank_id_edit = $(this).data('bank_branch-edit').bank_id;
                    // var categorySelect_edit_id_edit = $(this).data('department-edit').category_id;
                    $('#myModal').modal('show');
                    $("#edit_id").val(id);
                    $("#branch_code_edit").val(branch_code);
                    $("#branch_name_edit").val(branch_name);
                    $("#bank_id_edit").val(bank_id_edit);

                    {{--$.ajax({--}}
                    {{--    type: "post",--}}
                    {{--    url: '{!! route('admin.banks_branches.company.get') !!}',--}}
                    {{--    data: {--}}
                    {{--        id: company_id_edit,--}}
                    {{--        _token: "{{csrf_token()}}"--}}
                    {{--    },--}}
                    {{--    success: function (response) {--}}
                    {{--        $('.branch_id').html(response);--}}

                    {{--        $('.branch_id').val(branch_edit);--}}
                    {{--    },--}}
                    {{--    error: function () {--}}
                    {{--        toastr.error('Error');--}}
                    {{--    }--}}
                    {{--});--}}

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
                    var url = "{{ route('admin.banks_branches.index') }}";
                    var loader = $('<div class="loader"></div>').appendTo('body');

                    if (!$('#editform').valid()) {
                        return false;
                    }

                    $.ajax({
                        type: "put",
                        "url": url + '/' + id,
                        data: $('#editform').serialize(),
                        success: function (response) {


                            $('#myModal').modal('hide');
                            loader.remove();

                            tableData.ajax.reload();
                            toastr.success('Bank Branch Updated successfully.');
                        },
                        error: function () {
                            loader.remove();
                            toastr.error('Error while updating bank branch');
                        }
                    });
                    return false;
                });

                $(document).on("click", ".delete", function () {
                    $(this).closest('form').submit();
                });

                $(document).on("submit", ".delete_form", function (event) {
                    event.preventDefault();
                    var route = $(this).data('route');
                    var form = this;

                    var a = confirm('Are you sure you want to delete this?');
                    if (a) {
                        $.ajax({
                            url: route,
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function (result) {
                                tableData.ajax.reload();
                                toastr.success("Bank Branch deleted successfully.")
                            },
                            error: function (xhr, status, error) {
                                toastr.error(xhr.responseText);
                                console.error("Error while deleting Bank Branch");
                            }
                        });
                    }
                });


                $('#file-datatable tbody').on('click', '.change-status', function () {
                    var id = $(this).data('id');
                    var status = $(this).data('status');
                    var loader = $('<div class="loader"></div>').appendTo('body');

                    $.ajax({
                        type: 'POST',
                        url: '{{route('admin.bank-branch.change-status')}}',
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {
                            loader.remove();

                            console.log(response);
                            tableData.ajax.reload();
                            toastr.success('Status Updated successfully.');
                        },
                        error: function (xhr, status, error) {
                            loader.remove();
                            toastr.error('Error while updating Status.');

                            console.error(xhr.responseText);
                        }
                    });
                });

            </script>
@endsection
