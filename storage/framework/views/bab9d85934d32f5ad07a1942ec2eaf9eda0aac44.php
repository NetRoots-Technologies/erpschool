

<?php $__env->startSection('title'); ?>
    Category
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        #modal_name {
            margin-right: 500px;
        }
    </style>
    <div class="container-fluid">
        <div class="row w-100  mt-4 ">
            <h3 class="text-22 text-center text-bold w-100 mb-4" > Category </h3>
        </div>
        <div class="row    mt-4 mb-4 ">
            

            
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

                                    <th class="heading_style">No</th>
                                    <th class="heading_style">Name</th>
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


                                <!-- Modal body -->
                                <div class="modal-body">

                                    <div class="form-group">
                                        <form>
                                            <?php echo csrf_field(); ?>

                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="modal_name">Name</label>
                                                        <input type="text" required class="form-control" value=""
                                                               id="name" name="name">
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
                    <div class="modal modal1" id="myModal">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Category</h4>
                                    <button type="button" id="close" class="close modalclose" data-dismiss="modal1">
                                        &times;
                                    </button>
                                </div>

                                <!-- Modal body  -->

                                <div class="modal-body">
                                    <form id="editform">

                                        <?php echo csrf_field(); ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="input-label">
                                                        <label for="name" id="modal_name">Name</label>
                                                    </div>
                                                    <input type="text"  class="form-control" id="name_edit"
                                                           value="" name="name">
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
                                    extend: 'colvis',
                                    columns: ':not(:first-child)'
                                }
                            ],
                            "columnDefs": [
                                { 'visible': false }
                            ],
                            ajax: {
                                "url": "<?php echo e(route('datatable.category.getdata')); ?>",
                                "type": "POST",
                                "data": {_token: "<?php echo e(csrf_token()); ?>"}
                            },
                            "columns": [

                                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                                {data: 'name', name: 'name'},
                                {data: 'action', name: 'action', orderable: false, searchable: false},
                            ]
                        });
                    });

                    $('#file-datatable tbody').on('click', '.category_edit', function () {

                        var id = $(this).data('category-edit').id;
                        var name = $(this).data('category-edit').name;
                        ``
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
                        var url = "<?php echo e(route('admin.category.index')); ?>";
                        var loader = $('<div class="loader"></div>').appendTo('body');

                        $.ajax({
                            type: "put",
                            "url": url + '/' + id,
                            data: $('#editform').serialize(),
                            success: function (response) {
                                loader.remove();

                                $('#myModal').modal('hide');
                                tableData.ajax.reload();
                                toastr.success('Category Updated successfully.');
                            },
                            error: function () {
                                loader.remove();
                                toastr.error('Category not Updated.');
                            }
                        });
                        return false;
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
                                toastr.success('Status Updated successfully.');

                            },
                            error: function (xhr, status, error) {
                                loader.remove();
                                toastr.error(xhr.responseText);
                                console.error(xhr.responseText);
                            }
                        });
                    });


                </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/category/index.blade.php ENDPATH**/ ?>