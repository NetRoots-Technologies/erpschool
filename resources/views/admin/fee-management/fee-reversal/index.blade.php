@extends('admin.layouts.main')

@section('title', 'Fee Reversal')

@push('meta')
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="page-leftheader">
                        <h4 class="page-title mb-0">Fee Reversal</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee
                                    Management</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.fee-management.collections') }}">Collections</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Fee Reversal</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fa fa-credit-card mr-2"></i>
                            Process Fee Reversal
                        </h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.fee-management.process.fee.reversal') }}" method="POST"
                            id="challanPaymentForm">
                            @csrf

                            <!-- Student Selection -->
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="student_roll_id" class="form-label">Student ID <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('student_roll_id') is-invalid @enderror"
                                            id="student_roll_id" name="student_roll_id" required>
                                            <option value="">Select Student ID</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}"
                                                    {{ old('student_roll_id') == $student->student_id ? 'selected' : '' }}>
                                                    {{ $student->student_id }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('student_roll_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="academic_class_id" class="form-label">Class <span
                                                class="text-danger">*</span></label>
                                        <select id="academic_class_id" name="academic_class_id" class="form-control select2"
                                            required disabled>
                                            <option value="">Auto-filled when student is selected</option>
                                        </select>
                                        @error('academic_class_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="student_id" class="form-label">Student <span
                                                class="text-danger">*</span></label>
                                        <select id="student_id" name="student_id" class="form-control select2" required
                                            disabled>
                                            <option value="">Auto-filled when student is selected</option>
                                        </select>
                                        @error('student_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="academic_session_id" class="form-label">Session <span
                                                class="text-danger">*</span></label>
                                        <select id="academic_session_id" name="academic_session_id" class="form-control"
                                            required disabled>
                                            <option value="">Auto-filled when student is selected</option>
                                        </select>
                                        @error('academic_session_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Challan Selection -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="challan_id" class="form-label">Select Challan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select2 @error('challan_id') is-invalid @enderror"
                                            id="challan_id" name="challan_id" required disabled>
                                            <option value="">Select student first to load challans</option>
                                        </select>
                                        @error('challan_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                
                            <!-- Submit Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-success btn-block"
                                                    id="submitPayment" disabled>
                                                    <i class="fa fa-credit-card"></i> Process Fee Reversal
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="{{ route('admin.fee-management.collections') }}"
                                                    class="btn btn-secondary btn-block">
                                                    <i class="fa fa-times"></i> Cancel
                                                </a>
                                            </div>
                                        </div>
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
                    <h3 class="card-title">Fee Reversal Guidelines</h3>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <strong>Step 1:</strong> Select student's class
                        </div>
                        <div class="list-group-item">
                            <strong>Step 2:</strong> Choose student
                        </div>
                        <div class="list-group-item">
                            <strong>Step 3:</strong> Select paid challan
                        </div>
                        <div class="list-group-item">
                            <strong>Step 4:</strong> Enter reversal amount & reason
                        </div>
                        <div class="list-group-item">
                            <strong>Step 5:</strong> Confirm fee reversal
                        </div>
                    </div>
                </ul>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection
@section('js')
<script>
$(document).ready(function () {

    $('.select2').select2({ width: '100%' });

    // Disable button initially
    $('#submitPayment').prop('disabled', true);

    // When Student ID selected → load student info + challans
    $('#student_roll_id').on('change', function () {

        let studentId = $(this).val();

        $('#submitPayment').prop('disabled', true);
        $('#challan_id').prop('disabled', true)
            .html('<option value="">Loading challans...</option>');

        if (!studentId) return;

        // 1️⃣ Load student + class + session
        $.get(
            '{{ route('admin.fee-management.collections.students-by-class', ':id') }}'
            .replace(':id', studentId),
            function (res) {

                if (!res) return;

                $('#student_id')
                    .html(`<option value="${res.id}" selected>${res.name}</option>`)
                    .prop('disabled', false);

                $('#academic_class_id')
                    .html(`<option value="${res.class_id}" selected>${res.class_name}</option>`)
                    .prop('disabled', false);

                $('#academic_session_id')
                    .html(`<option value="${res.session_id}" selected>${res.session_name}</option>`)
                    .prop('disabled', false);
            }
        );

        // 2️⃣ Load challans
        $.get(
            '{{ route('admin.fee-management.collections.challans-by-student-fee', ':id') }}'
            .replace(':id', studentId),
            function (res) {

                $('#challan_id').html('<option value="">Select Challan</option>');

                if (res.challans.length > 0) {
                    res.challans.forEach(challan => {
                        $('#challan_id').append(
                            `<option value="${challan.id}">
                                ${challan.challan_number} (${challan.status})
                            </option>`
                        );
                    });

                    $('#challan_id').prop('disabled', false);
                } else {
                    $('#challan_id')
                        .html('<option value="">No challans found</option>');
                }
            }
        );
    });

    // 3️⃣ Enable submit button when challan selected
    $('#challan_id').on('change', function () {
        if ($(this).val()) {
            $('#submitPayment').prop('disabled', false);
        } else {
            $('#submitPayment').prop('disabled', true);
        }
    });

});
</script>
@endsection
