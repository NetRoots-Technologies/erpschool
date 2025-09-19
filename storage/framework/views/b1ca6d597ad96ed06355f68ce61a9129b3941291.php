

<?php $__env->startSection('title'); ?>
Academic
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
    #modal_name {
        margin-right: 500px;
    }

    #modal_name1 {
        margin-right: 140px;
    }
</style>
<div class="container-fluid">
    <div class="row w-100  mt-4 ">
        <h3 class="text-22 text-center text-bold w-100 mb-4"> Academic Session</h3>
    </div>
    <div class="row    mt-4 mb-4 ">
        <?php if(Gate::allows('AcademicSession-create')): ?>
        <div class="col-12 text-right">
            <div class="d-flex gap-2">
                <a class="btn btn-primary btn-md mb-sm-0 mb-2 text-white" data-toggle="modal"
                    data-target="#createModal1">Add Session</a>
                <a href="<?php echo e(route('academic.session.export-file')); ?>"
                    class="btn btn-success mb-2 mb-sm-0 btn-md text-white">Download
                    Sample Bulk File</a>
                <button class="btn btn-warning mb-sm-0 btn-md mb-2 text-white" data-toggle="modal"
                    data-target="#importModal">Import Sample
                    Bulk File</button>
            </div>
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
                                <h4 class="modal-title">Create Academic Session</h4>
                                <button type="button" id="close" class="close modalclose" data-dismiss="modal">
                                    &times;
                                </button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">

                                <div class="form-group">
                                    <form id="createform">
                                        <?php echo csrf_field(); ?>

                                        
                                            
                                                
                                                  
                                                    
                                                    
                                                    
                                                    
                                                    
                                                

                                            
                                                
                                                  
                                                    
                                                    
                                                    
                                                    
                                                    
                                                
                                            


                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label id="modal_name"><b>Name</b></label>
                                                    <input type="text" required class="form-control" value="" id="name"
                                                        name="name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="start_date" class="modal_name1"><b>Start
                                                            Date</b></label>
                                                    <input type="date" class="form-control" value="" id="start_date"
                                                        name="start_date" required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="end_date" class="modal_name1"><b>End Date</b></label>
                                                    <input type="date" class="form-control" value="" id="end_date"
                                                        name="end_date" required>
                                                </div>
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
                            <h4 class="modal-title">Edit Academic Session</h4>
                            <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                &times;
                            </button>
                        </div>
                        <!-- Modal body  -->
                        <div class="modal-body">
                            <form id="editform">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    
                                        
                                            
                                              
                                                
                                                
                                                
                                                
                                                
                                            
                                        
                                            
                                              
                                                
                                                
                                                
                                                
                                                
                                            
                                        
                                    <div class="col-12 mt-3">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label for="name" id="modal_name">Name</label>
                                            </div>
                                            <input type="text" class="form-control" id="name_edit" value="" name="name">
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-6">
                                                <div class="input-label">
                                                    <label for="name" id="modal_name">Start Date</label>
                                                </div>
                                                <input type="date" class="form-control" id="start_date_edit" value=""
                                                    name="start_date">
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
    
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?php echo e(route('academic.session.import-file')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Excel File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="import_file" class="form-label">Select File</label>
                            <input type="file" name="import_file" id="import_file" class="form-control" required>
                            <?php $__errorArgs = ['import_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger mt-2"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('css'); ?>
        
        
        
        
        
        

        <style>
            .error {
                color: red;
                font-weight: 500;
            }
        </style>
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('js'); ?>
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
                            greaterThan: "End date cannot be earlier than the start date",
                        },
                    },
                })
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
                })
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
                                                        url: '<?php echo e(route('academic.academic-session-bulk')); ?>',
                                                        type: 'POST',
                                                        data: {
                                                            ids: selectedIds,
                                                            "_token": "<?php echo e(csrf_token()); ?>",
                                                        },
                                                        dataType: 'json',
                                                        success: function (response) {
                                                            tableData.ajax.reload();

                                                            Swal.fire('Deleted!', 'Your data has been deleted.', 'success');

                                                        },
                                                        error: function (xhr, status, error) {
                                                            console.error(xhr.responseText);
                                                            toastr.error('AJAX request failed: ' + error)
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
                        "url": "<?php echo e(route('datatable.academic.getsession')); ?>",
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
                        // {data: 'company', name: 'company'},
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
                var url = "<?php echo e(route('academic.academic-session.store')); ?>";

                if (!$('#createform').valid()) {
                    return false;
                }
                $.ajax({
                    type: "post",
                    "url": url,
                    data: $('#createform').serialize(),
                    success: function (response) {
                        $("#close").trigger("click");
                        $('#name').val('');
                        $('#start_date').val('');
                        $('#end_date').val('');
                        tableData.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Academic Session Added successfully.',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    },
                    error: function () {
                        toastr.error('Error');
                    }
                });
                return false;
            });
            $('#file-datatable tbody').on('click', '.academic_session_edit', function () {
                var academicSessionData = $(this).data('academic-session-edit');
                console.log(academicSessionData);
                $('#myModal').modal('show');
                $("#edit_id").val(academicSessionData.id);
                $("#name_edit").val(academicSessionData.name);
                $("#start_date_edit").val(academicSessionData.start_date);
                $("#end_date_edit").val(academicSessionData.end_date);
                // $("#company_edit").val(academicSessionData.company_id);
                // $("#school_edit").val(academicSessionData.school_id);
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
                var url = "<?php echo e(route('academic.academic-session.index')); ?>";

                if (!$('#editform').valid()) {
                    return false;
                }
                $.ajax({
                    type: "put",
                    "url": url + '/' + id,
                    data: $('#editform').serialize(),
                    success: function (response) {
                        $('#myModal').modal('hide');
                        tableData.ajax.reload();
                        toastr.success('Academic Session Updated successfully.');
                    },
                    error: function () {
                        toastr.error('Error while updating academic session');
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
                                toastr.success('Academic Session Deleted successfully.');
                            },
                            error: function (error, xhr) {
                                toastr.error('Error while deleting data');
                            }
                        });
                    }
                });
            });

            $('#file-datatable tbody').on('click', '.change-status', function () {
                var id = $(this).data('id');
                var status = $(this).data('status');
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(route('academic.academicSession.change-status')); ?>',
                    data: {
                        id: id,
                        status: status,
                        _token: '<?php echo e(csrf_token()); ?>',
                    },
                    success: function (response) {

                        console.log(response);
                        tableData.ajax.reload();
                        toastr.success('Status Updated successfully.');

                    },
                    error: function (xhr, status, error) {
                        toastr.error(xhr.responseText);
                    }
                });
            });
            function checkAll(source) {
                var checkboxes = $('.select-checkbox');
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = source.checked
                }
            }
            document.addEventListener("DOMContentLoaded", function () {
                if (document.querySelector('#importModal .text-danger')) {
                    // alert("hello");
                    var myModal = new bootstrap.Modal(document.getElementById('importModal'));
                    myModal.show();
                }
            })
        </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/acadmeic/academic_session/index.blade.php ENDPATH**/ ?>