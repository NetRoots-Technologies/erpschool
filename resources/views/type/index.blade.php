@extends('admin.layouts.main')

@section('title')
    Types
@stop

@section('content')
    <style>
        #modal_name , #modal_type {
            margin-right: 500px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4" > Types </h3>
        </div>
        <div class="row mt-4 mb-4 ">
           @if (Gate::allows('create types'))
        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md" style="margin-left: 15px; color: #212529 !important;" data-toggle="modal"
                data-target="#createTypeModal">
                <b>Add Type</b>
            </a>
        </div>
        @endif
        </div>
        <div class="row w-100 text-center">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="type-datatable"
                                   class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                <thead>
                                <tr>

                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Title</th>
                                    <th class="heading_style">Type</th>
                                    <th class="heading_style">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal for create -->
                    <div class="modal" id="createTypeModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->


                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group">
                                        <form id="createTypePost">
                                            @csrf

                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="modal_name">Title</label>
                                                        <input type="text" required class="form-control" value=""
                                                            id="title" name="title" placeholder="Enter Invoice / Expense / Maintainance Issue,Type Title">
                                                    </div>
                                                </div>

                                                {{-- Type Drowpdown  --}}
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="modal_type">Type</label>
                                                        <select name="type" required class="form-control">
                                                        @foreach($types as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                    </div>
                                                </div>
                                                

                                                <div class="modal-footer">

                                                    <input id="create-form-submit" type="submit" class="btn btn-primary btn btn-md"
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
                    <div class="modal modal1" id="editTypeModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Type</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal body  -->

                                <div class="modal-body">
                                    <form id="editform">

                                        @csrf
                                        @method('PUT')  
                                        <div class="row">
                                            <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="modal_name">Title</label>
                                                        <input type="text" required class="form-control" value=""
                                                            id="edit_title" name="title" placeholder="Enter Invoice / Expense / Maintainance Issue,Type Title">
                                                    </div>
                                                </div>

                                                {{-- Type Drowpdown  --}}
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="modal_type">Type</label>
                                                        <select name="type" required class="form-control" id="edit_type">
                                                        @foreach($types as $key => $value)
                                                            <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                    </div>
                                                </div>
                                        </div>

                                        <input type="hidden" name="id" id="edit_id" class="form-control">


                                        <!-- Modal footer -->
                                        <div class="modal-footer">

                                            <input id="edit-form-submit" type="submit" class="btn btn-primary btn btn-sm"
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
            @endsection
            @section('js')

                <script type="text/javascript">
                    var tableData = null;
                    $(document).ready(function () {
                        tableData = $('#type-datatable').DataTable({
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
                                    extend: 'colvis',
                                    columns: ':not(:first-child)'
                                }
                            ],
                            "columnDefs": [
                                { 'visible': false }
                            ],
                            ajax: {
                                "url": "{{ route('maintainer.type.index') }}",
                                "type": "GET",
                                "data": {_token: "{{csrf_token()}}"}
                            },
                            "columns": [

                                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                                {data: 'title', name: 'title'},
                                {data: 'type', name: 'type'},
                                {data: 'action', name: 'action', orderable: false, searchable: false},
                            ]
                        });
                    });


                    // Create Type
                      $('#create-form-submit').on('click', function (e) {
                        e.preventDefault();

                        
                        $.ajax({
                            type: "POST",
                            "url": "{{route('maintainer.type.store')}}",
                            data: $('#createTypePost').serialize(),
                            success: function (response) {
                                // console.log(response);
                                //loader.remove();
                                $('#createTypeModal').hide('modal');
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');

                                $('#createTypePost')[0].reset();
                                tableData.ajax.reload();
                                toastr.success('Type Add successfully.');
                            },
                            error: function () {
                                //loader.remove();
                                toastr.error('Something Went Wrong.');
                            }
                        });
                        return false;
                    });


                    // Update Type Using Modal 

                    $('#type-datatable tbody').on('click', '.editType', function () {
                        var id    = $(this).data('id');
                        var title = $(this).data('title');
                        var type  = $(this).data('type');

                        
                        $('#edit_id').val(id);
                        $('#edit_title').val(title);
                        $('#edit_type').val(type);

                        
                        $('#editTypeModal').modal('show');
                    });

                    $(".modalclose").click(function () {
                        $('#editTypeModal').modal('hide');
                    });

                    $(".modalclose").click(function () {
                        $('#createTypeModal').modal('hide');
                    });

                    $('#edit-form-submit').on('click', function (e) {
                        e.preventDefault();
                        var id = $('#edit_id').val();

                        $.ajax({
                            type: "POST",
                            url: "{{ route('maintainer.type.update', ':id') }}".replace(':id', id),
                            data: $('#editform').serialize(),
                            success: function (response) {
                                $('#editTypeModal').modal('hide');
                                tableData.ajax.reload(null, false);
                                toastr.success('Type updated successfully.');
                            },
                            error: function () {
                                toastr.error('Type not updated.');
                            }
                        });
                    });
    
                    
                    // Delete type for sweet alerts
                    $('#type-datatable tbody').on('click', '.deleteType', function () {
                    var id = $(this).data('id'); // row id

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this action!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('maintainer.type.destroy', ':id') }}".replace(':id', id),
                                type: "POST",
                                data: {
                                    "_token": "{{ csrf_token() }}"
                                },
                                success: function (response) {
                                    tableData.ajax.reload(null, false);
                                    Swal.fire(
                                        'Deleted!',
                                        'Type has been deleted successfully.',
                                        'success'
                                    );
                                },
                                error: function () {
                                    Swal.fire(
                                        'Error!',
                                        'Something went wrong. Please try again.',
                                        'error'
                                    );
                                }
                            });
                        }
                    });
                });


                    

                </script>
@endsection
