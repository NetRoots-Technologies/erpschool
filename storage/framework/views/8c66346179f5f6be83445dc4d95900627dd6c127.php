

<?php $__env->startSection('title', 'Record Fee Collection'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Record Fee Collection</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.fee-management.index')); ?>">Fee Management</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.fee-management.collections')); ?>">Collections</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Collection Information</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('admin.fee-management.collections.store')); ?>" method="POST" id="collectionForm">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Student Selection -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="academic_class_id" class="form-label">Class <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['academic_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="academic_class_id" name="academic_class_id" required>
                                        <option value="">Select Class</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>" <?php echo e(old('academic_class_id') == $class->id ? 'selected' : ''); ?>>
                                                <?php echo e($class->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['academic_class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="student_id" name="student_id" required disabled>
                                        <option value="">First select a class</option>
                                    </select>
                                    <?php $__errorArgs = ['student_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="academic_session_id" class="form-label">Session <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['academic_session_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="academic_session_id" name="academic_session_id" required disabled>
                                        <option value="">Auto-filled when student is selected</option>
                                    </select>
                                    <?php $__errorArgs = ['academic_session_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Collection Details -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="collection_date" class="form-label">Collection Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control <?php $__errorArgs = ['collection_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="collection_date" name="collection_date" 
                                           value="<?php echo e(old('collection_date', date('Y-m-d'))); ?>" required>
                                    <?php $__errorArgs = ['collection_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="payment_method" name="payment_method" required>
                                        <option value="">Select Method</option>
                                        <option value="cash" <?php echo e(old('payment_method') == 'cash' ? 'selected' : ''); ?>>Cash</option>
                                        <option value="bank_transfer" <?php echo e(old('payment_method') == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                                        <option value="cheque" <?php echo e(old('payment_method') == 'cheque' ? 'selected' : ''); ?>>Cheque</option>
                                    </select>
                                    <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="remarks" name="remarks" value="<?php echo e(old('remarks')); ?>" 
                                           placeholder="e.g., Online transfer, Cash deposit">
                                    <?php $__errorArgs = ['remarks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Fee Categories -->
                        <div class="row">
                            <div class="col-12">
                                <h5>Fee Categories</h5>
                                <div id="feeCategories">
                                    <div class="row mb-2" id="categoryRow0">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <div class="input-group">
                                                    <select class="form-control category-select" name="collections[0][category_id]" required>
                                                        <option value="">Select Category</option>
                                                        <option value="1">Monthly Tuition</option>
                                                        <option value="2">Admission Fee</option>
                                                        <option value="3">Security</option>
                                                        <option value="4">Books & Stationery</option>
                                                    </select>
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#categoryModal">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Amount</label>
                                                <input type="number" class="form-control amount-input" name="collections[0][amount]" 
                                                       placeholder="Amount" min="0" step="0.01" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm remove-category" style="display: none;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Amount Display -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <strong>Total Amount: <span id="totalAmount">Rs. 0</span></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Record Collection
                                    </button>
                                    <a href="<?php echo e(route('admin.fee-management.collections')); ?>" class="btn btn-secondary">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Collection Guidelines</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <strong>Cash:</strong> Physical cash payment
                        </div>
                        <div class="list-group-item">
                            <strong>Bank Transfer:</strong> Online/ATM transfer
                        </div>
                        <div class="list-group-item">
                            <strong>Cheque:</strong> Cheque payment
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Important Notes:</h6>
                        <ul class="list-unstyled">
                            <li>• Verify student details before recording</li>
                            <li>• Double-check amounts</li>
                            <li>• Add remarks for clarity</li>
                            <li>• Collection cannot be edited after saving</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Creation Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Create New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="category_name">Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="category_name" required>
                </div>
                <div class="form-group">
                    <label for="category_description">Description</label>
                    <textarea class="form-control" id="category_description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="category_type">Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="category_type" required>
                        <option value="">Select Type</option>
                        <option value="admission">Admission</option>
                        <option value="monthly">Monthly</option>
                        <option value="annual">Annual</option>
                        <option value="one_time">One Time</option>
                        <option value="allocation">Allocation</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_mandatory">
                        <label class="form-check-label" for="is_mandatory">Mandatory</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="affects_financials" checked>
                        <label class="form-check-label" for="affects_financials">Affects Financials</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCategory">Save Category</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    $(document).ready(function() {
        let categoryCount = 0;
        
        // Add new category row when + button is clicked
        $(document).on('click', '[data-toggle="modal"][data-target="#categoryModal"]', function() {
            // Store reference to the button that opened the modal
            window.currentCategoryButton = $(this);
        });
        
        // Remove category row
        $(document).on('click', '.remove-category', function() {
            $(this).closest('.row').remove();
            updateRemoveButtons();
            calculateTotal();
        });

        // Update remove buttons visibility
        function updateRemoveButtons() {
            const rows = $('.row[id^="categoryRow"]');
            if (rows.length > 1) {
                $('.remove-category').show();
            } else {
                $('.remove-category').hide();
            }
        }
        
        // Calculate total amount
        $(document).on('input', '.amount-input', function() {
            calculateTotal();
        });
        
        function calculateTotal() {
            let total = 0;
            $('.amount-input').each(function() {
                const amount = parseFloat($(this).val()) || 0;
                total += amount;
            });
            $('#totalAmount').text('Rs. ' + total.toLocaleString());
        }
        
        // Handle class selection to load students
        $('#academic_class_id').change(function() {
            const classId = $(this).val();
            const studentSelect = $('#student_id');
            const sessionSelect = $('#academic_session_id');
            
            // Clear and disable student and session selects
            studentSelect.html('<option value="">Loading students...</option>').prop('disabled', true);
            sessionSelect.html('<option value="">Auto-filled when student is selected</option>').prop('disabled', true);
            
            if (classId) {
                // Fetch students for the selected class
                $.ajax({
                    url: '<?php echo e(route("admin.fee-management.collections.students-by-class", ":classId")); ?>'.replace(':classId', classId),
                    type: 'GET',
                    success: function(response) {
                        studentSelect.html('<option value="">Select Student</option>');
                        
                        if (response.students.length > 0) {
                            response.students.forEach(function(student) {
                                studentSelect.append(
                                    '<option value="' + student.id + '" ' +
                                    'data-session-id="' + (student.session_id || '') + '" ' +
                                    'data-session-name="' + student.session_name + '">' +
                                    student.name + ' (' + student.class_name + ')' +
                                    '</option>'
                                );
                            });
                            studentSelect.prop('disabled', false);
                        } else {
                            studentSelect.html('<option value="">No students found in this class</option>');
                        }
                    },
                    error: function() {
                        studentSelect.html('<option value="">Error loading students</option>');
                    }
                });
            } else {
                studentSelect.html('<option value="">First select a class</option>');
            }
        });
        
        // Handle student selection to auto-fill session
        $('#student_id').change(function() {
            const selectedOption = $(this).find('option:selected');
            const sessionId = selectedOption.data('session-id');
            const sessionName = selectedOption.data('session-name');
            const sessionSelect = $('#academic_session_id');
            
            if (sessionId && sessionName) {
                sessionSelect.html('<option value="' + sessionId + '">' + sessionName + '</option>');
                sessionSelect.prop('disabled', false);
            } else {
                sessionSelect.html('<option value="">Auto-filled when student is selected</option>');
                sessionSelect.prop('disabled', true);
            }
        });
        
        // Form validation
        $('#collectionForm').on('submit', function(e) {
            if ($('.category-select').length === 0) {
                e.preventDefault();
                toastr.error('Please add at least one fee category');
                return false;
            }
            
            let hasValidCategory = false;
            $('.category-select').each(function() {
                const categoryValue = $(this).val();
                const amountValue = $(this).closest('.row').find('.amount-input').val();
                if (categoryValue && amountValue) {
                    hasValidCategory = true;
                }
            });
            
            if (!hasValidCategory) {
                e.preventDefault();
                toastr.error('Please fill in at least one complete fee category');
                return false;
            }
        });
        
        // Handle category creation
        $('#saveCategory').click(function() {
            const formData = {
                name: $('#category_name').val(),
                description: $('#category_description').val(),
                type: $('#category_type').val(),
                is_mandatory: $('#is_mandatory').is(':checked'),
                affects_financials: $('#affects_financials').is(':checked'),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            if (!formData.name || !formData.type) {
                alert('Please fill in all required fields.');
                return;
            }

            $.ajax({
                url: '<?php echo e(route("admin.fee-management.categories.store")); ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Add new option to all category selects
                    const newOption = `<option value="${response.category.id}">${response.category.name}</option>`;
                    $('.category-select').each(function() {
                        $(this).append(newOption);
                    });
                    
                    // If this was opened from a + button, add a new row
                    if (window.currentCategoryButton && window.currentCategoryButton.length) {
                        categoryCount++;
                        const newRow = `
                            <div class="row mb-2" id="categoryRow${categoryCount}">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <div class="input-group">
                                            <select class="form-control category-select" name="collections[${categoryCount}][category_id]" required>
                                                <option value="">Select Category</option>
                                                <option value="1">Monthly Tuition</option>
                                                <option value="2">Admission Fee</option>
                                                <option value="3">Security</option>
                                                <option value="4">Books & Stationery</option>
                                                <option value="${response.category.id}">${response.category.name}</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#categoryModal">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" class="form-control amount-input" name="collections[${categoryCount}][amount]" 
                                               placeholder="Amount" min="0" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm remove-category">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        $('#feeCategories').append(newRow);
                        // Select the newly created category in the new row
                        $('#feeCategories .row').last().find('.category-select').val(response.category.id);
                        updateRemoveButtons();
                    } else {
                        // Select the newly created category in the current row
                        const currentRow = $('.row[id^="categoryRow"]').last();
                        currentRow.find('.category-select').val(response.category.id);
                    }
                    
                    // Close modal and reset form
                    $('#categoryModal').modal('hide');
                    $('#category_name, #category_description, #category_type').val('');
                    $('#is_mandatory, #affects_financials').prop('checked', false);
                    $('#affects_financials').prop('checked', true);
                    
                    // Clear the reference
                    window.currentCategoryButton = null;
                    
                    toastr.success('Category created successfully!');
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        Object.values(xhr.responseJSON.errors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += '- ' + error + '\n';
                            });
                        });
                        alert(errorMessage);
                    } else {
                        alert('Error creating category. Please try again.');
                    }
                }
            });
        });

        // Reset modal form when closed
        $('#categoryModal').on('hidden.bs.modal', function() {
            $('#category_name, #category_description, #category_type').val('');
            $('#is_mandatory, #affects_financials').prop('checked', false);
            $('#affects_financials').prop('checked', true);
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/fee-management/collections/create.blade.php ENDPATH**/ ?>