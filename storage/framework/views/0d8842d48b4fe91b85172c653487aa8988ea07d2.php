

<?php $__env->startSection('title'); ?>
Company
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
    #modal_name {
        margin-right: 500px;
    }

    .select-checkbox {
        margin-right: 13px !important;
    }
</style>
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Company </h3>
    </div>

    <div class="row mt-4 mb-4 justify-content-start gap-4">
        
     <?php if(Gate::allows('Company-create')): ?>

        <div class="col-auto p-0">
            <a class="btn btn-primary btn-md text-white" style="margin-left: 15px;" data-toggle="modal"
                data-target="#createModal1">
                <b>Add Company</b>
            </a>
        </div>

        
        <div class="col-auto p-0">
            <a href="<?php echo e(route('academic.company.export-file')); ?>" class="btn btn-warning btn-md">
                <b>Download Sample Bulk File</b>
            </a>
        </div>

        
        <div class="col-auto p-0">
            <a href="#" class="btn btn-success btn-md" data-bs-toggle="modal" data-bs-target="#importModal">
                <b>Import Data</b>
            </a>
        </div>
        <div class="col-auto p-0">
            <a href="<?php echo e(route('print-preview', 'company')); ?>" class="btn btn-info btn-md">
                <b>Print Preview</b>
            </a>
        </div>
        <?php endif; ?>

    </div>

    <!-- Import File Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo e(route('academic.company.import-file')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Excel File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Select File</label>
                            <input type="file" name="import_file" id="import_file" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
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
                                <h4 class="modal-title">Create Company</h4>
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
                                                    <input type="text" required class="form-control" value="" id="name"
                                                        name="name">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="logo">Logo</label>
                                                    <input type="file" required class="form-control dropify"
                                                        data-height="200" id="logo" name="logo"
                                                        data-allowed-file-extensions="jpg jpeg png gif">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="logo">Voucher Image</label>
                                                    <input type="file" required class="form-control" data-height="200"
                                                        id="logo" name="voucher_image"
                                                        data-allowed-file-extensions="jpg jpeg png gif">
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
                                <h4 class="modal-title">Edit Company</h4>
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
                                                    <label for="name" id="modal_name">Name</label>
                                                </div>
                                                <input type="text" class="form-control" id="name_edit" value=""
                                                    name="name">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label for="logo">Logo</label>
                                                </div>
                                                <input type="file" class="form-control  logo_edit" id="logo_edit"
                                                    name="logo" data-allowed-file-extensions="jpg jpeg png gif">
                                                <img src="" id="logo_preview"
                                                    style="max-width: 200px; max-height: 200px; margin-top: 10px; display: none;">
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label for="logo">Voucher Image</label>
                                                </div>
                                                <input type="file" class="form-control  voucher_edit" id="voucher_edit"
                                                    name="voucher_image"
                                                    data-allowed-file-extensions="jpg jpeg png gif">
                                                <img src="" id="voucher_preview"
                                                    style="max-width: 70px; max-height: 70px; margin-top: 10px; display: none;">
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
            
            
            
            
            
            
        <?php $__env->stopSection(); ?>
        <?php $__env->startSection('js'); ?>

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
                                                            url: '<?php echo e(route('hr.company-bulk')); ?>',
                                                            type: 'POST',
                                                            data: {
                                                                ids: selectedIds,
                                                                "_token": "<?php echo e(csrf_token()); ?>",
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
                                                toastr.error('No checkboxes selected.');
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
                            "url": "<?php echo e(route('datatable.company.getdata')); ?>",
                            "type": "POST",
                            "data": { _token: "<?php echo e(csrf_token()); ?>" }
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
                            { data: 'status', name: 'status' },
                            { data: 'action', name: 'action', orderable: false, searchable: false },
                        ]
                    });
                });

                //Create Form Submit
                $('#create-form-submit').on('click', function (e) {

                    if ($("#name").val() === "") {
                        toastr.error('Company name is required');
                        return;
                    }

                    e.preventDefault();
                    var formData = new FormData($('#createform')[0]);
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    var url = "<?php echo e(route('admin.company.store')); ?>";
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Company added successfully.',
                                timer: 1000,
                                showConfirmButton: false
                            });
                        },
                        error: function () {
                            loader.remove();

                            toastr.error('Company added failed.');

                        }
                    });
                    return false;
                });

                // if (logoUrl && !logoUrl.startsWith('http')) {
                //     logoUrl = 'data:image/png;base64,' + logoUrl;
                // }


                $('#file-datatable tbody').on('click', '.company_edit', function () {

                    var id = $(this).data('company-edit').id;
                    var name = $(this).data('company-edit').name;
                    var logoUrl = $(this).data('company-edit').logo;
                    var voucher_url = $(this).data('company-edit').voucher_image;

                    if (logoUrl && !logoUrl.startsWith('http')) {
                        logoUrl = '<?php echo e(URL::to("/")); ?>/' + logoUrl;
                    }
                    if (voucher_url && !voucher_url.startsWith('http')) {
                        voucher_url = 'data:image/png;base64,' + voucher_url;
                    }

                    $('#myModal').modal('show');
                    $("#edit_id").val(id);
                    $("#name_edit").val(name);

                    if (logoUrl) {
                        $("#logo_preview").attr("src", logoUrl).show();
                    } else {
                        $("#logo_preview").hide();
                    }
                    if (voucher_url) {
                        $("#voucher_preview").attr("src", voucher_url).show();
                    } else {
                        $("#voucher_preview").hide();
                    }
                });



                $(".modalclose").click(function () {

                    $('#myModal').modal('hide');
                });

                $(".modalclose").click(function () {

                    $('#createModal1').modal('hide');
                });

                $('#tag-form-submit').on('click', function (e) {
                    e.preventDefault();

                    if ($("#name_edit").val() == "") {
                        toastr.error('Company name is required');
                        return false;
                    }

                    var id = $('#edit_id').val();
                    var url = "<?php echo e(route('admin.company.index')); ?>";
                    var loader = $('<div class="loader"></div>').appendTo('body');


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
                            $('.logo_edit').val('');
                            $('.voucher_edit').val('');
                            $('#logo_preview').attr('src', '').hide();
                            $('#voucher_preview').attr('src', '').hide();

                            tableData.ajax.reload();
                            toastr.success('Company Updated successfully.');
                        },
                        error: function () {
                            loader.remove();

                            toastr.error('Company Updated failed.');
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
                                    "_token": "<?php echo e(csrf_token()); ?>",
                                },
                                success: function (result) {
                                    tableData.ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Company Deleted successfully.',
                                        timer: 1000,
                                        showConfirmButton: false
                                    });
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
                        url: '<?php echo e(route('hr.company.change-status')); ?>',
                        data: {
                            id: id,
                            status: status,
                            _token: '<?php echo e(csrf_token()); ?>',
                        },
                        success: function (response) {
                            loader.remove();

                            console.log(response);
                            tableData.ajax.reload();
                            toastr.success("Status Updated successfully.");

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

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/comapny/index.blade.php ENDPATH**/ ?>