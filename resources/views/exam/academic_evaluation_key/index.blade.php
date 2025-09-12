@extends('admin.layouts.main')

@section('title')
   Academic Evaluation Key
@stop

@section('content')
    <style>
        #modal_name {
            margin-right: 500px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4"> Academic Evaluation Key</h3>
        </div>
        <div class="row    mt-4 mb-4 ">
        @if (Gate::allows('AcademicEvaluationsKey-create'))
            <div class="col-12 text-right">
                <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1"><b>Add  Academic Evaluation Key</b></a>
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
                                        <input type="checkbox" class="select-all-checkbox" onchange="checkAll(this)">
                                    </th>
                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Abbr</th>
                                    <th class="heading_style">Key</th>
                                    <th class="heading_style">User</th>
                                    <th class="heading_style">Status</th>
                                    <th class="heading_style">Log</th>
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
                                    <h4 class="modal-title">Create Academic Evaluation Key</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group">
                                        <form id="createform" enctype="multipart/form-data">
                                            @csrf

                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="modal_name">Abbr</label>
                                                        <input type="text" required class="form-control" value=""
                                                               id="name" name="abbr">
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="modal_name">Key</label>
                                                        <input type="text" required class="form-control" value=""
                                                               id="key" name="key">
                                                    </div>
                                                </div>


                                                <div class="modal-footer">

                                                    <input id="create-form-submit" type="submit"
                                                           class="btn btn-primary btn btn-md"
                                                           value="Submit">

                                                    <button type="button" class="btn btn-danger btn btn-md modalclose"
                                                            data-dismiss="modal">Close
                                                    </button>
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
                                    <h4 class="modal-title">Edit  Academic Evaluation Key</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal body  -->

                                <div class="modal-body">
                                    <form id="editform" enctype="multipart/form-data">

                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label for="name" id="modal_name">Abbr</label>
                                                    </div>
                                                    <input type="text" class="form-control" id="name_edit"
                                                           value="" name="abbr">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label for="name" id="modal_name">Key</label>
                                                    </div>
                                                    <input type="text" class="form-control" id="key_edit"
                                                           value="" name="key">
                                                </div>
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

                <style>
                    .error
                    {
                        color: red;
                        font-weight: 500;
                        font-size: 14px;
                    }
                </style>

                @endsection
            @section('js')

                <script type="text/javascript">
                    var tableData = null;
                    $(document).ready(function () {

                        $("#editform").validate({
                            rules: {
                                abbr: {
                                    required: true,
                                },
                                key: {
                                    required: true,
                                }
                            },
                            messages: {
                                abbr: {
                                    required: "Abbr is required.",
                                },
                                key: {
                                    required: "Key is required.",
                                }
                            },
                        });

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
                                                    $('.dt-button-collection').hide();

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
                                                                url: '{{ route('exam.academic-evaluation-bulk') }}',
                                                                type: 'POST',
                                                                data: {
                                                                    ids: selectedIds,
                                                                    "_token": "{{ csrf_token() }}",
                                                                },
                                                                dataType: 'json',
                                                                success: function (response) {
                                                                    Swal.fire('Deleted!', 'Your data has been deleted.', 'success');
                                                                    tableData.ajax.reload();

                                                                },
                                                                error: function (xhr, status, error) {
                                                                    console.error(xhr.responseText);
                                                                    toastr.error('AJAX request failed: ' + error);
                                                                }
                                                            });
                                                        }
                                                    });
                                                } else {
                                                    toastr.warning('No checkboxes selected.');
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
                                {'visible': false}
                            ],
                            ajax: {
                                "url": "{{ route('datatable.academic-evaluation.getdata') }}",
                                "type": "POST",
                                "data": {_token: "{{csrf_token()}}"}
                            },
                            "columns": [

                                {
                                    data: "checkbox",
                                    render: function (data, type, row) {
                                        return '<input type="checkbox" value="' + row.id + '" class="select-checkbox">'
                                    },
                                    orderable: false, searchable: false
                                },
                                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                                {data: 'abbr', name: 'abbr'},
                                {data: 'key', name: 'key'},
                                {data: 'user', name: 'user'},
                                {data: 'status', name: 'status'},
                                {data: 'created_at', name: 'created_at'},
                                {data: 'action', name: 'action', orderable: false, searchable: false},
                            ]
                        });
                    });

                    //Create Form Submit
                    $('#create-form-submit').on('click', function (e) {
                        e.preventDefault();
                        var formData = new FormData($('#createform')[0]);
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                        var url = "{{ route('exam.academic_evaluations_key.store') }}";
                        var loader = $('<div class="loader"></div>').appendTo('body');

                        $.ajax({
                            type: "POST",
                            url: url,
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                loader.remove();

                                $('#createform')[0].reset();
                                $('#close').trigger('click');
                                tableData.ajax.reload();
                                toastr.success('Skill Evaluation added successfully.');
                            },
                            error: function () {
                                loader.remove();
                                toastr.error('Error while adding Academic Evaluation');
                            }
                        });
                        return false;
                    });

                    // if (logoUrl && !logoUrl.startsWith('http')) {
                    //     logoUrl = 'data:image/png;base64,' + logoUrl;
                    // }


                    $('#file-datatable tbody').on('click', '.academicEvaluation_edit', function () {
                        var id = $(this).data('academic_evaluation-edit').id;
                        var abbr = $(this).data('academic_evaluation-edit').abbr;
                        var key = $(this).data('academic_evaluation-edit').key;

                        $('#myModal').modal('show');
                        $("#edit_id").val(id);
                        $("#name_edit").val(abbr);
                        $("#key_edit").val(key);
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
                        var url = "{{ route('exam.academic_evaluations_key.index') }}";
                        var loader = $('<div class="loader"></div>').appendTo('body');

                        if(!$("#editform").valid())
                        {
                            return false;
                        }

                        var formData = new FormData($('#editform')[0]);
                        formData.append('_method', 'PUT');
                        formData.append('_token', $('input[name="_token"]').val());

                        $.ajax({
                            type: "POST",
                            url: url + '/' + id,
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                loader.remove();

                                $('#myModal').modal('hide');

                                $('#name_edit').val('');


                                tableData.ajax.reload();
                                toastr.success("Evaluation Updated successfully.");
                            },
                            error: function () {
                                loader.remove();

                                toastr.error('Error while updating academic evaluation');
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
                                    toastr.success('Academic Evaluation Deleted successfully.');
                                },
                                error: function (xhr, status, error) {
                                    toastr.error("Error while deleitng Academic Evaluation.")
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
                            url: '{{route('exam.academicEvaluation.change-status')}}',
                            data: {
                                id: id,
                                status: status,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {
                                loader.remove();

                                console.log(response);
                                tableData.ajax.reload();
                                toastr.success("Status Updated successfully.");
                            },
                            error: function (xhr, status, error) {
                                loader.remove();
                                toastr.error("Error while updating Status.");
                                console.error(xhr.responseText);
                            }
                        });
                    });

                    function checkAll(source) {
                        var checkboxes = $('.select-checkbox');
                        for (var i = 0; i < checkboxes.length; i++) {
                            checkboxes[i].checked = source.checked;
                        }
                    }
                </script>

                <script>
                    $(document).ready(function () {
                        $('.dropify').dropify();
                    });
                </script>

@endsection
