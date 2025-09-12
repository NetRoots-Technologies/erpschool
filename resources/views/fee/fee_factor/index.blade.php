@extends('admin.layouts.main')

@section('title')
    Fee Factor
@stop

@section('content')
    <style>
        #modal_name {
            margin-right: 400px;
        }
        #modal_name1 {
            margin-right: 100px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Fee Factor</h3>
        </div>
        <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1">Add Fee Factor</a>
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
                                    <th class="heading_style">
                                        <input type = "checkbox" class="select-all-checkbox" onchange="checkAll(this)">
                                    </th>
                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Fee Factor</th>
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
                                    <h4 class="modal-title">Create Fee Factor</h4>
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
                                                        <label id="modal_name"><b>Fee Factor</b></label>
                                                        <input type="text" required class="form-control" value=""
                                                               id="name" name="name">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="modal-footer">

                                                <input id="create-form-submit" type="submit" class="btn btn-primary btn btn-md"
                                                       value="Submit">

                                                <button type="button" class="btn btn-danger btn btn-md modalclose"
                                                        data-dismiss="modal">Close
                                                </button>
                                            </div>
                                        </form>
                                    </div>
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
                                <h4 class="modal-title">Edit Fee Factor</h4>
                                <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                    &times;
                                </button>
                            </div>

                            <!-- Modal body  -->

                            <div class="modal-body">
                                <form id="editform">

                                    @csrf
                                    <div class="row">

                                        <div class="col-12 mt-3">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label for="name" id="modal_name">Fee Factor</label>
                                                </div>
                                                <input type="text"  class="form-control" id="name_edit"
                                                       value="" name="name">
                                            </div>
                                        </div>

                                        <input type="hidden" name="id" id="edit_id" class="form-control">


                                        <!-- Modal footer -->
                                        <div class="modal-footer">

                                            <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm"
                                                   value="Update">

                                            <button type="button" class="btn btn-danger btn btn-sm modalclose"
                                                    data-dismiss="modal1">Close
                                            </button>
                                        </div>
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
            {{--            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">--}}
            {{--            <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>--}}
            {{--            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>--}}
            {{--            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>--}}
            {{--            <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">--}}
            {{--            <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">--}}
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
                                extend: 'collection',
                                className: "btn-light",
                                text: 'Export',
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
                                    }
                                ]
                            },
                            {
                                extend: 'collection',

                                text: 'Bulk Action',
                                className: 'btn btn-light',
                                buttons: [
                                    {
                                        text: '<i class="fas fa-trash"></i> Delete',
                                        className: 'btn btn-danger delete-button',
                                        action: function () {
                                            var selectedIds = [];

                                            $('#file-datatable').find('.select-checkbox:checked').each(function () {
                                                selectedIds.push($(this).val());
                                            });

                                            if (selectedIds.length > 0) {
                                                $ ('.dt-button-collection').hide();

                                                Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: 'You are about to perform a bulk action!',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Yes, delete it!',
                                                    cancelButtonText: 'Cancel'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $.ajax({
                                                            url: '{{ route('academic.fee-factor-bulk') }}',
                                                            type: 'POST',
                                                            data: {
                                                                ids: selectedIds,
                                                                "_token": "{{ csrf_token() }}",
                                                            },
                                                            dataType: 'json',
                                                            success: function (response) {
                                                                tableData.ajax.reload();

                                                                Swal.fire('Deleted!', 'Your data has been deleted.', 'success');

                                                            },
                                                            error: function (xhr, status, error) {
                                                                console.error(xhr.responseText);
                                                                alert('AJAX request failed: ' + error);
                                                            }
                                                        });
                                                    }
                                                });
                                            } else {
                                                alert('No checkboxes selected.');
                                            }
                                        }
                                    },
                                ],
                            },

                            {
                                extend: 'colvis',
                                columns: ':not(:first-child)'
                            }
                        ],
                        "columnDefs": [
                            { 'visible': false }
                        ],
                        ajax: {
                            "url": "{{ route('datatable.fee-factor.getfeeFactor') }}",
                            "type": "POST",
                            "data": {_token: "{{csrf_token()}}"}
                        },
                        "columns": [

                            {   data : "checkbox",
                                render : function (data,type,row) {
                                    return '<input type="checkbox" value="'+ row.id +'" class="select-checkbox">'
                                },
                                orderable: false , searchable: false
                            },
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                            // {data: 'company', name: 'company'},
                            {data: 'name', name: 'name'},
                            {data: 'status', name: 'status'},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ],
                        order : [2,'desc']
                    });
                });


                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {
                    e.preventDefault();



                    var url = "{{ route('admin.fee-factor.store') }}";
                    $.ajax({
                        type: "post",
                        "url": url,
                        data:$('#createform').serialize(),
                        success: function (response) {
                            $("#close").trigger("click");
                            $('#name').val('');
                            $('#start_date').val('');
                            $('#end_date').val('');
                            tableData.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Fee Factor Added successfully.',
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


                $('#file-datatable tbody').on('click', '.fee_factor_edit', function () {
                    var feeFactorData = $(this).data('fee-factor-edit');
                    console.log(feeFactorData);
                    $('#myModal').modal('show');
                    $("#edit_id").val(feeFactorData.id);
                    $("#name_edit").val(feeFactorData.name);

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
                    var url = "{{ route('admin.fee-factor.index') }}";
                    $.ajax({
                        type: "put",
                        "url": url + '/' + id,
                        data: $('#editform').serialize(),
                        success: function (response) {
                            $('#myModal').modal('hide');
                            tableData.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Fee Factor Updated successfully.',
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
                                    text: 'Fee Factor Deleted successfully.',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });


                $('#file-datatable tbody').on('click', '.change-status', function () {
                    var id = $(this).data('id');
                    var status = $(this).data('status');

                    $.ajax({
                        type: 'POST',
                        url: '{{route('admin.fee-factor.change-status')}}',
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function (response) {

                            console.log(response);
                            tableData.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Status Updated successfully.',
                                timer: 1000,
                                showConfirmButton: false
                            });

                        },
                        error: function (xhr, status, error) {

                            console.error(xhr.responseText);
                        }
                    });
                });

                function checkAll(source){
                    var checkboxes = $('.select-checkbox');
                    for(var i =0 ; i < checkboxes.length; i ++){
                        checkboxes[i].checked = source.checked
                    }
                }

            </script>
@endsection
