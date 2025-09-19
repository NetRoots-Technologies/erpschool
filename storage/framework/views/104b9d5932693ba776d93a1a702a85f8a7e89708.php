

<?php $__env->startSection('title'); ?>
Test Type
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
    #modal_name {
        margin-right: 500px;
    }
</style>
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Test Type </h3>
    </div>
    <div class="row    mt-4 mb-4 ">
<?php if(Gate::allows('TestTypes-create')): ?>
        <div class="col-12 text-right">
            <a class="btn btn-primary btn-md text-white" data-toggle="modal" data-target="#createModal1"><b>Add Test
                    Type</b></a>
        </div>
        <?php endif; ?>
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
                                <h4 class="modal-title">Create Test Type</h4>
                                <button type="button" id="close" class="close modalclose" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">

                                <div class="form-group">
                                    <form id="createform" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>

                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label id="modal_name">Name</label>
                                                    <input type="text" class="form-control" value="" id="name"
                                                        name="name" required>
                                                </div>
                                            </div>


                                            <div class="modal-footer">

                                                <input id="create-form-submit" type="submit"
                                                    class="btn btn-primary btn btn-md" value="Submit">

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
                                <h4 class="modal-title">Edit Test Type</h4>
                                <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                    &times;
                                </button>
                            </div>

                            <!-- Modal body  -->

                            <div class="modal-body">
                                <form id="editform" enctype="multipart/form-data">

                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label for="name_edit" id="modal_name">Name</label>
                                                </div>
                                                <input type="text" class="form-control" id="name_edit" value=""
                                                    name="name" required>
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
        <?php $__env->stopSection(); ?>
        <?php $__env->startSection('css'); ?>
        
        
        
        
        
        
        <style>
            .error {
                color: red;
                font-weight: 500;
                font-size: 14px;
            }
        </style>
        <?php $__env->stopSection(); ?>
        <?php $__env->startSection('js'); ?>

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
                                                                url: '<?php echo e(route('exam.test-type-bulk')); ?>',
                                                                type: 'POST',
                                                                data: {
                                                                    ids: selectedIds,
                                                                    "_token": "<?php echo e(csrf_token()); ?>",
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
                                {'visible': false}
                            ],
                            ajax: {
                                "url": "<?php echo e(route('datatable.test_type.getdata')); ?>",
                                "type": "POST",
                                "data": {_token: "<?php echo e(csrf_token()); ?>"}
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
                                {data: 'name', name: 'name'},
                                {data: 'status', name: 'status'},
                                {data: 'action', name: 'action', orderable: false, searchable: false},
                            ]
                        });
                    });

                    //Create Form Submit
                    $('#create-form-submit').on('click', function (e) {
                        e.preventDefault();
                        var formData = new FormData($('#createform')[0]);
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                        var url = "<?php echo e(route('exam.test_types.store')); ?>";
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
                                toastr.success("Test Type added successfully.")
                            },
                            error: function () {
                                loader.remove();
                                toastr.error('Error while adding test type');
                            }
                        });
                        return false;
                    });

                    // if (logoUrl && !logoUrl.startsWith('http')) {
                    //     logoUrl = 'data:image/png;base64,' + logoUrl;
                    // }


                    $('#file-datatable tbody').on('click', '.test_type_edit', function () {

                        var id = $(this).data('test-type-edit').id;
                        var name = $(this).data('test-type-edit').name;

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
                        var url = "<?php echo e(route('exam.test_types.index')); ?>";
                        var loader = $('<div class="loader"></div>').appendTo('body');

                        if (!$('#editform').valid())
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
                                    "_token": "<?php echo e(csrf_token()); ?>",
                                },
                                success: function (result) {
                                    tableData.ajax.reload();
                                    toastr.success("Test Type Deleted successfully.")
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
                            url: '<?php echo e(route('exam.test-type.change-status')); ?>',
                            data: {
                                id: id,
                                status: status,
                                _token: '<?php echo e(csrf_token()); ?>',
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

        <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/exam/test_type/index.blade.php ENDPATH**/ ?>