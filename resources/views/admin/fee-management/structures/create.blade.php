@extends('admin.layouts.main')

@section('title', 'Create Fee Structure')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="page-leftheader">
                        <h4 class="page-title mb-0">Create Fee Structure</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee
                                    Management</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.fee-management.structures') }}">Structures</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Fee Structure</h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('admin.fee-management.structures.store') }}" method="POST"
                            id="structureForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Structure Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="class_id">Class <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="class_id" name="class_id" required>
                                            <option value="">Select Class</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="student_id">Student <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="student_id" name="student_id" required disabled>
                                            <option value="">Select Student</option>
                                        </select>
                                        @error('student_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="session_id">Academic Session <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="session_id" name="session_id" required>
                                            <option value="">Select Session</option>
                                            @foreach ($sessions as $session)
                                                <option value="{{ $session->id }}">{{ $session->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('session_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="factor_id">Fee Factor <span class="text-danger">*</span></label>
                                        <select class="form-control select2" id="factor_id" name="factor_id" required>
                                            <option value="">Select Factor</option>
                                            @foreach ($factors as $factor)
                                                <option value="{{ $factor->id }}">{{ $factor->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('factor_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Fee Categories Section -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Fee Categories</h5>
                                        <button type="button" class="btn btn-outline-success btn-sm" id="addCategory">
                                            <i class="fa fa-plus"></i> Add Another Category
                                        </button>
                                    </div>
                                    <div id="feeCategories">
                                        <div class="row fee-category-row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select class="form-control category-select select2"
                                                        name="categories[0][category_id]" required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" class="form-control amount-input"
                                                        name="categories[0][amount]" step="0.01" min="0"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Notes</label>
                                                    <input type="text" class="form-control"
                                                        name="categories[0][notes]" placeholder="Optional notes">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-danger btn-sm remove-category"
                                                        style="display: none; margin-top: 30px;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Create Structure</button>
                                    <a href="{{ route('admin.fee-management.structures') }}"
                                        class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            let categoryIndex = 1;


            // Add new category row when "Add Another Category" button is clicked
            $('#addCategory').click(function() {
                const newRow = `
                <div class="row fee-category-row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control category-select" name="categories[${categoryIndex}][category_id]" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" class="form-control amount-input" name="categories[${categoryIndex}][amount]" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Notes</label>
                            <input type="text" class="form-control" name="categories[${categoryIndex}][notes]" placeholder="Optional notes">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger btn-sm remove-category" style="margin-top: 30px;">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

                $('#feeCategories').append(newRow);
                categoryIndex++;
                updateRemoveButtons();
            });

            // Remove category row
            $(document).on('click', '.remove-category', function() {
                $(this).closest('.fee-category-row').remove();
                updateRemoveButtons();
            });

            // Update remove buttons visibility
            function updateRemoveButtons() {
                const rows = $('.fee-category-row');
                if (rows.length > 1) {
                    $('.remove-category').show();
                } else {
                    $('.remove-category').hide();
                }
            }

            // Calculate total amount
            $(document).on('input', '.amount-input', function() {
                let total = 0;
                $('.amount-input').each(function() {
                    const value = parseFloat($(this).val()) || 0;
                    total += value;
                });
                // You can display the total somewhere if needed
            });

            // Form validation
            $('#structureForm').on('submit', function(e) {
                const categoryRows = $('.fee-category-row');
                if (categoryRows.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one fee category.');
                    return false;
                }
            });

        });

       $(function () {
        const $class   = $('#class_id');
        const $student = $('#student_id');

        function resetStudents(placeholder) {
            $student.prop('disabled', true)
                    .empty()
                    .append('<option value="">' + (placeholder || 'Select Student') + '</option>');
        }

        $class.on('change', function () {
            const classId = $(this).val();
            resetStudents('Loading...');

            if (!classId) {
            resetStudents('Select Student');
            return;
            }

            // Build URL via route helper safely
            const url = "{{ route('admin.fee-management.class.students', ':id') }}".replace(':id', encodeURIComponent(classId));

            $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                
                $student.empty().append('<option value="">Select Student</option>');
                if (Array.isArray(res) && res.length) {
                res.forEach(function (s) {
                    $student.append('<option value="'+ s.id +'">'+ s.name +'</option>');
                });
                $student.prop('disabled', false);
                } else {
                resetStudents('No students found');
                }

                // If using Select2:
                // $student.trigger('change.select2');
            },
            error: function () {
                resetStudents('Unable to load students');
            }
            });
        });

  // (Optional) For edit forms with preselected values:
  // const preClassId = '{{ old('class_id') }}';
  // const preStudentId = '{{ old('student_id') }}';
  // if (preClassId) {
  //   $class.val(preClassId).trigger('change');
  //   // set selected student after AJAX completes
  //   $(document).one('ajaxStop', function(){ $student.val(preStudentId); });
  // }
});
    </script>
@endsection
