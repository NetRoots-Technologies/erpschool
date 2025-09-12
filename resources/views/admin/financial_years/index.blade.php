@extends('admin.layouts.main')

@section('title')
Financial
@stop

@section('content')
<style>
    .financial_label_create {
        margin-right: 403px;
    }
</style>
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Financial Years </h3>
    </div>
    <div class="row    mt-4 mb-4 ">
@if (Gate::allows('students'))
        <div class="col-auto text-right">
            <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1">Add financial
                Year</a>
        </div>
        <div class="col-auto p-0">
            <a href="{{route('print-preview', 'financial_years')}}" class="btn btn-info btn-md">
                <b>Print Preview</b>
            </a>
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
                                    <th class="heading_style">Name</th>
                                    <th class="heading_style">Start Date</th>
                                    <th class="heading_style">End Date</th>
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
                                <h4 class="modal-title">Create Financial</h4>
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
                                                    <label for="name" class="financial_label_create">Name</label>
                                                    <input type="text" required class="form-control" value="" id="name"
                                                        name="name">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="start_date" class="financial_label_create">Start
                                                        Date</label>
                                                    <input type="date" required class="form-control" value=""
                                                        id="start_date" name="start_date">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="end_date" class="financial_label_create">End
                                                        Date</label>
                                                    <input type="date" required class="form-control" value=""
                                                        id="end_date" name="end_date">
                                                </div>
                                            </div>

                                            <div class="modal-footer justify-content-center">

                                                <input id="create-form-submit" type="submit"
                                                    class="btn btn-primary btn btn-md" value="Submit">

                                                <button type="button" class="btn btn-danger btn btn-md modalclose ms-5"
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
                                <h4 class="modal-title">Edit Financial</h4>
                                <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                    &times;
                                </button>
                            </div>

                            <!-- Modal body  -->

                            <div class="modal-body">
                                <form id="editform">

                                    @csrf
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label for="name" id="modal_name">Name</label>
                                                </div>
                                                <input type="text" class="form-control" id="name_edit" value=""
                                                    name="name">
                                            </div>

                                            <div class="form-row">
                                                <div class="col-lg-6">
                                                    <div class="input-label">
                                                        <label for="name" id="modal_name">Start Date</label>
                                                    </div>
                                                    <input type="date" class="form-control" id="start_date_edit"
                                                        value="" name="start_date">
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="input-label">
                                                        <label for="name" id="modal_name">End Date</label>
                                                    </div>
                                                    <input type="date" class="form-control" id="end_date_edit" value=""
                                                        name="end_date">
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
            {{--
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">--}}
            {{--
            <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>--}}
            {{--
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>--}}
            {{--
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>--}}
            {{--
            <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">--}}
            {{--
            <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">--}}
            <style>
                .error {
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


                    $.validator.addMethod('greaterThan', function (value, element, param) {
                        var startDate = $("#start_date").val();
                        var endDate = $('#end_date').val();

                        if (startDate && endDate) {
                            return new Date(endDate) >= new Date(startDate);
                        }
                        return true;
                    }, "End date cannot be earlier than the start date");

                    $("#createform").validate({
                        rules: {
                            name: {
                                required: true,
                            },
                            start_date: {
                                required: true,
                            },
                            end_date: {
                                required: true,
                                greaterThan: true,
                            },
                        },
                        messages: {
                            name: {
                                required: "Please enter name",
                            },
                            start_date: {
                                required: "Please enter start date",
                            },
                            end_date: {
                                required: "Please enter end date",
                                greaterThan: "End date cannot be earlier than the start date", // Custom error message for the greaterThan rule
                            },
                        },
                        submitHandler: function (form) {
                            form.submit(); // Form submission logic
                        }
                    });

                    $.validator.addMethod('greaterThanEdit', function (value, element, param) {
                        var startDate = $("#start_date_edit").val();
                        var endDate = $('#end_date_edit').val();

                        if (startDate && endDate) {
                            return new Date(endDate) >= new Date(startDate);
                        }
                        return true;
                    }, "End date cannot be earlier than the start date");


                    $("#editform").validate({
                        rules: {
                            name: {
                                required: true,
                            },
                            start_date: {
                                required: true,
                            },
                            end_date: {
                                required: true,
                                greaterThanEdit: true,
                            },
                        },
                        messages: {
                            name: {
                                required: "Please enter name",
                            },
                            start_date: {
                                required: "Please enter start date",
                            },
                            end_date: {
                                required: "Please enter end date",
                                greaterThanEdit: "End date cannot be earlier than the start date",
                            },
                        },
                    });


                    tableData = $('#file-datatable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "pageLength": 10,
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
                                className: 'btn-light',
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
                                                            url: '{{ route('hr.financialYears-bulk') }}',
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
                                                                toastr.error('AJAX request failed.', error);
                                                            }
                                                        });
                                                    }
                                                });
                                            } else {
                                                toastr.warning("No checkoxes selected")
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
                            "url": "{{ route('datatable.financial.getdata') }}",
                            "type": "POST",
                            "data": { _token: "{{csrf_token()}}" }
                        },
                        "columns": [

                            {
                                data: "checkbox",
                                render: function (data, type, row) {
                                    return '<input type="checkbox" value="' + row.id + '" class="select-checkbox">'
                                },
                                orderable: false, searchable: false
                            },
                            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                            { data: 'name', name: 'name' },
                            { data: 'start_date', name: 'start_date' },
                            { data: 'end_date', name: 'end_date' },
                            { data: 'action', name: 'action', orderable: false, searchable: false },
                        ],
                        order: [2, 'desc']
                    });
                });


                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {
                    e.preventDefault();



                    var url = "{{ route('admin.financial-years.store') }}";

                    if (!$('#createform').valid()) {
                        return false;
                    }

                    var loader = $('<div class="loader"></div>').appendTo('body');

                    $.ajax({
                        type: "post",
                        "url": url,
                        data: $('#createform').serialize(),
                        success: function (response) {
                            $("#close").trigger("click");
                            $('#name').val('');
                            $('#start_date').val('');
                            $('#end_date').val('');
                            loader.remove();

                            tableData.ajax.reload();
                            toastr.success('Financial Year Added successfully');
                        },
                        error: function () {
                            loader.remove();
                            toastr.error('Please fill all the fields');
                        }
                    });
                    return false;
                });


                $('#file-datatable tbody').on('click', '.financial_edit', function () {
                    var financialData = $(this).data('financial-edit');
                    $('#myModal').modal('show');
                    $("#edit_id").val(financialData.id);
                    $("#name_edit").val(financialData.name);
                    $("#start_date_edit").val(financialData.start_date);
                    $("#end_date_edit").val(financialData.end_date);
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
                    var url = "{{ route('admin.financial-years.index') }}";
                    var loader = $('<div class="loader"></div>').appendTo('body');

                    if (!$('#editform').valid()) {
                        loader.remove();
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
                            toastr.success('Financial Updated successfully');
                        },
                        error: function () {
                            loader.remove();
                            toastr.error('Error Updating Financial');
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
                    var loader = $('<div class="loader"></div>').appendTo('body');

                    Swal.fire({
                        title: "Are you sure to delete?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: route,
                                type: 'DELETE',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                },
                                success: function (result) {
                                    loader.remove();
                                    tableData.ajax.reload();
                                    toastr.success('Financial Year Deleted successfully');
                                },
                                error: function (xhr, status, error) {
                                    loader.remove();
                                    toastr.error('Error Deleting Financial Year');
                                }
                            });
                        }
                    });
                });

                $('#file-datatable tbody').on('click', '.change-status', function () {
                    var id = $(this).data('id');
                    var status = $(this).data('status');
                    var loader = $('<div class="loader"></div>').appendTo('body');


                    $.ajax({
                        type: 'POST',
                        url: '{{route('admin.financial-years.change-status')}}',
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
                            toastr.error('Error while updating status', xhr.responseText);
                        }
                    });
                });

                function checkAll(source) {
                    var checkboxes = $('.select-checkbox');
                    for (var i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].checked = source.checked
                    }
                }

            </script>
        @endsection