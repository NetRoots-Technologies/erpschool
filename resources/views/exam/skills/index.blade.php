@extends('admin.layouts.main')

@section('title')
Skills
@stop

@section('content')
<style>
    #modal_name {
        margin-right: 500px;
    }
    .error {
   margin-right: 297px;
}

</style>
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4">Skills</h3>
    </div>
    <div class="row    mt-4 mb-4 ">
@if (Gate::allows('Skills-create'))
        <div class="col-12 text-right">
            <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1"><b>Add Skill</b></a>
        </div>
        @endif
    </div>
    <div class="row w-100 text-center">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file-datatable" class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                            <thead>
                                <tr>
                                    <th class="heading_style">
                                        <input type="checkbox" class="select-all-checkbox" onchange="checkAll(this)">
                                    </th>
                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Name</th>
                                    <th class="heading_style">Class</th>
                                    <th class="heading_style">Subject</th>
                                    <th class="heading_style">Component</th>
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
                                <h4 class="modal-title">Create Skill</h4>
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
                                                    <label id="modal_name">Name</label>
                                                    <input type="text" class="form-control" value="" id="name" name="name" required>
                                                </div>
                                            </div>

                                    <div class="form-group">
                                        <label id="modal_name">Class</label>
                                        <select class="form-control" id="class_id" name="class_id" required>
                                            <option value="">Select Class</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12">
                                    <div class="form-group">
                                             <label id="modal_name">Subject</label>
                                        <select class="form-control" id="subject_id" name="course_id" required>
                                            <option value="">Select Subject</option>
                                        </select>
                                    </div>
                                </div>

                                      <div class="col-12">
                                    <div class="form-group">
                                             <label id="modal_name">Component</label>
                                              <select class="form-control" id="component_id" name="component_id" required>
                                            <option value="">Select Component</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                                <input id="create-form-submit" type="submit" class="btn btn-primary btn btn-md" value="Submit">
                                                <button type="button" class="btn btn-danger btn btn-md modalclose" data-dismiss="modal">Close
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
                                <h4 class="modal-title">Edit Skill</h4>
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
                                                    <label for="name_edit" id="modal_name">Name</label>
                                                </div>
                                                <input type="text" class="form-control" id="name_edit" value="" name="name" required>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="id" id="edit_id" class="form-control">
                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <input id="tag-form-submit" type="submit" class="btn btn-primary btn btn-sm" value="Update">
                                        <button type="button" class="btn btn-danger btn btn-sm modalclose" data-dismiss="modal1">Close
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
                    name: {
                        required: true,
                    },
                },
                messages: {
                    name: {
                        required: "Please enter the name",
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
                                                    url: '{{ route('exam.skills-bulk') }}',
                                                    type: 'POST',
                                                    data: {
                                                        ids: selectedIds,
                                                        "_token": "{{ csrf_token() }}",
                                                    },
                                                    dataType: 'json',
                                                    success: function (response) {
                                                        toastr.success("Your data has been deleted.");
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
                    { 'visible': false }
                ],
                ajax: {
                    "url": "{{ route('datatable.skills.getdata') }}",
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
                { data: 'class', name: 'class' },
                { data: 'subject', name: 'subject' },
                { data: 'component', name: 'component' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]

            });
        });

   $(document).ready(function () {
    // Validation for Create Form
    $("#createform").validate({
        rules: {
            name: {
                required: true,
                minlength: 2
            },
            class_id: {
                required: true
            },
            subject_id: {
                required: true
            },
            component_id: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter skill name",
                minlength: "Name must be at least 2 characters"
            },
            class_id: {
                required: "Please select a class"
            },
            subject_id: {
                required: "Please select a subject"
            },
            component_id: {
                required: "Please select a component"
            }
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("error");
            error.insertAfter(element);
        }
    });

            // AJAX Submit for Create Form
            $('#create-form-submit').on('click', function (e) {
                e.preventDefault();

                if (!$('#createform').valid()) {
                    return false; // stop submit if validation fails
                }

                var formData = new FormData($('#createform')[0]);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    type: "POST",
                    url: "{{ route('exam.skills.store') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#createform')[0].reset();
                        $('#close').trigger('click');
                        tableData.ajax.reload();
                        toastr.success("Skill added successfully.");
                    },
                    error: function () {
                        toastr.error('Error while adding skill');
                    }
                });
            });
        });


        $('#file-datatable tbody').on('click', '.skills_edit', function () {

            var id = $(this).data('skills-edit').id;
            var name = $(this).data('skills-edit').name;

            $('#myModal').modal('show');
            $("#edit_id").val(id);
            $("#name_edit").val(name);
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
            var url = "{{ route('exam.skills.index') }}";
            var loader = $('<div class="loader"></div>').appendTo('body');

            if (!$('#editform').valid()) {
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
                    toastr.success('Test Type Updated Successfully');
                },
                error: function () {
                    loader.remove();
                    toastr.error('Error while updating test type');
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
                        toastr.success("Skills Deleted successfully.")
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
                url: '{{route('exam.skills.change-status')}}',
                data: {
                    id: id,
                    status: status,
                    _token: '{{ csrf_token() }}',
                },
                success: function (response) {
                    loader.remove();

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
                    loader.remove();
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

    <script>
    $(document).ready(function() {
        $('#class_id').change(function() {
            var classId = $(this).val();
            console.log('hello');

            if(classId) {
                $.ajax({
                  url: "{{ url('get-subjects') }}/" + classId,
                    type: 'GET',
                    success: function(data) {
                        $('#subject_id').empty(); // clear old options
                        $('#subject_id').append('<option value="">Select Subject</option>');

                        $.each(data, function(key, subject) {
                            $('#subject_id').append('<option value="'+ subject.id +'">'+ subject.name +'</option>');
                        });
                    }
                });
            } else {
                $('#subject_id').empty();
                $('#subject_id').append('<option value="">Select Subject</option>');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Load subjects when class changes
        $('#class_id').change(function() {
            var classId = $(this).val();

            if(classId) {
                $.ajax({
                    url: "{{ url('get-subjects') }}/" + classId,
                    type: 'GET',
                    success: function(data) {
                        $('#subject_id').empty().append('<option value="">Select Subject</option>');
                        $('#component_id').empty().append('<option value="">Select Component</option>');

                        $.each(data, function(key, subject) {
                            $('#subject_id').append('<option value="'+ subject.id +'">'+ subject.name +'</option>');
                        });
                    }
                });
            } else {
                $('#subject_id').empty().append('<option value="">Select Subject</option>');
                $('#component_id').empty().append('<option value="">Select Component</option>');
            }
        });

        // Load components when subject changes
        $('#subject_id').change(function() {
            var subjectId = $(this).val();

            if(subjectId) {
                $.ajax({
                    url: "{{ url('get-components') }}/" + subjectId,
                    type: 'GET',
                    success: function(data) {
                        $('#component_id').empty().append('<option value="">Select Component</option>');

                        $.each(data, function(key, component) {
                            $('#component_id').append('<option value="'+ component.id +'">'+ component.name +'</option>');
                        });
                    }
                });
            } else {
                $('#component_id').empty().append('<option value="">Select Component</option>');
            }
        });
    });
</script>



@endsection
