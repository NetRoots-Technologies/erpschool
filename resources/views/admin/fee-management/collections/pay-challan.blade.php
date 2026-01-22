@extends('admin.layouts.main')

@section('title', 'Pay Challan')

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
                        <h4 class="page-title mb-0">Pay Challan</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee
                                    Management</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.fee-management.collections') }}">Collections</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pay Challan</li>
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
                            Process Challan Payment
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

                        <form action="{{ route('admin.fee-management.collections.store-challan-payment') }}" method="POST"
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

                            <!-- Challan Details Display -->
                            <div id="challanDetails" style="display: none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Challan Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Challan Number:</strong> <span id="challanNumber"></span></p>
                                                <p><strong>Total Amount:</strong> <span id="totalAmount"></span></p>
                                                <p id="foodIncludedNote" style="display:none; color:#666; font-size:12px;">
                                                    Includes Food: <span id="foodIncludedAmount"></span>
                                                </p>
                                                <p><strong>Paid Amount:</strong> <span id="paidAmount"></span></p>
                                                <p><strong>Fine Amount:</strong> <span id="fineAmount"></span></p>
                                                <p><strong>Due Date:</strong> <span id="dueDate"></span></p>
                                                <input type="hidden" id="hiddenFineAmount" value="" name="fine_amount">
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Billing Month:</strong> <span id="billingMonth"></span></p>
                                            </div>
                                        </div>

                                        <!-- Discount Section -->
                                        <div id="discountSection" style="display: none;">
                                            <hr>
                                            <h6 class="text-success"><i class="fa fa-gift"></i> Applied Discounts</h6>
                                            <div id="discountList">
                                                <!-- Discounts will be loaded here -->
                                            </div>
                                            <div class="mt-2">
                                                <p><strong>Total Discount:</strong> <span id="totalDiscount"
                                                        class="text-success">Rs. 0</span></p>
                                            </div>
                                        </div>

                                        <!-- Transport Fees Section -->
                                        <div id="transportSection" style="display: none;">
                                            <hr>
                                            <h6 class="text-info"><i class="fa fa-bus"></i> Transport Fees</h6>
                                            <div id="transportList">
                                                <!-- Transport fees will be loaded here -->
                                            </div>
                                            <div class="mt-2">
                                                <p><strong>Transport Fee:</strong> <span id="transportFee"
                                                        class="text-info">Rs. 0</span></p>
                                            </div>
                                        </div>

                                        <!-- Final Amount Section -->
                                        <div class="mt-3">
                                            <hr>
                                            <p><strong>Final Amount (After Discounts & Transport & Fine):</strong> <span
                                                    id="finalAmount" class="text-primary font-weight-bold">Rs. 0</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div id="paymentDetails" style="display: none;">
                                <h5 class="mt-4">Payment Details</h5>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="collection_date" class="form-label">Payment Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date"
                                                class="form-control @error('collection_date') is-invalid @enderror"
                                                id="collection_date" name="collection_date"
                                                value="{{ old('collection_date', date('Y-m-d')) }}" required>
                                            @error('collection_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_method" class="form-label">Payment Method <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('payment_method') is-invalid @enderror"
                                                id="payment_method" name="payment_method" required>
                                                <option value="">Select Method</option>
                                                <option value="cash"
                                                    {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="bank_transfer"
                                                    {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank
                                                    Transfer</option>
                                                <option value="cheque"
                                                    {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque
                                                </option>
                                            </select>
                                            @error('payment_method')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="payment_type" class="form-label">Payment Type<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control @error('payment_type') is-invalid @enderror"
                                                id="payment_type" name="payment_type" required>
                                                <option value="Full"
                                                    {{ old('payment_type') == 'Full' ? 'selected' : '' }}>Full</option>
                                                <option
                                                    value="Partial"{{ old('payment_type') == 'Partial' ? 'selected' : '' }}>
                                                    Partial</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="paid_amount" class="form-label">Amount to Pay <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('paid_amount') is-invalid @enderror"
                                                id="paid_amount" name="paid_amount" placeholder="Enter amount"
                                                min="0" step="0.01" max="0" required>
                                            @error('paid_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <input type="hidden" id="maxAmountValue" value="0">
                                        </div>
                                    </div>


                                    <div class="col-8">
                                        <div class="form-group">
                                            <label for="remarks" class="form-label">Remarks</label>
                                            <input type="text"
                                                class="form-control @error('remarks') is-invalid @enderror" id="remarks"
                                                name="remarks" value="{{ old('remarks') }}"
                                                placeholder="e.g., Online transfer, Cash deposit">
                                            @error('remarks')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Summary -->
                                <div class="alert alert-info">
                                    <h6>Payment Summary:</h6>
                                    <p><strong>Challan Amount:</strong> <span id="summaryChallanAmount"></span></p>
                                    <p><strong>Already Paid:</strong> <span id="summaryAlreadyPaid">Rs. 0</span></p>
                                    <p id="summaryDiscountRow" style="display: none;"><strong>Total Discount:</strong>
                                        <span id="summaryTotalDiscount" class="text-success">Rs. 0</span>
                                    </p>
                                    <p id="summaryFineRow"><strong>Fine Amount:</strong>
                                        <span id="summaryTotalFine" class="text-success">Rs. 0</span>
                                    </p>
                                    <p id="summaryTransportRow" style="display: none;"><strong>Transport Fee:</strong>
                                        <span id="summaryTransportFee" class="text-info">Rs. 0</span>
                                        <input type="hidden" name="transport_amount" value="" id="transportAmount">
                                    </p>
                                    <p><strong>Final Amount:</strong> <span id="summaryFinalAmount"></span></p>
                                    <p><strong>Amount to Pay:</strong> <span id="summaryPaidAmount">Rs. 0</span></p>
                                    <p><strong>Remaining Balance:</strong> <span id="summaryRemainingAmount"></span></p>
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
                                                    <i class="fa fa-credit-card"></i> Process Payment
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
                        <h3 class="card-title">Payment Guidelines</h3>
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
                                <strong>Step 3:</strong> Select challan to pay
                            </div>
                            <div class="list-group-item">
                                <strong>Step 4:</strong> Enter payment details
                            </div>
                            <div class="list-group-item">
                                <strong>Step 5:</strong> Process payment
                            </div>
                        </div>
                        <div class="mt-3">
                            <h6>Important Notes:</h6>
                            <ul class="list-unstyled">
                                <li>• Only generated challans can be paid</li>
                                <li>• Partial payments are allowed</li>
                                <li>• Challan status will be updated automatically</li>
                                <li>• Payment cannot be edited after processing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function() {
            // (Optional) Select2 init
            $('.select2').each(function() {
                $(this).select2({
                    width: '100%'
                });
            });

            // Utility: placeholders + disable
            function lockFields() {
                $('#student_id')
                    .html('<option value="">Auto-filled when student is selected</option>')
                    .prop('disabled', true);
                $('#academic_class_id')
                    .html('<option value="">Auto-filled when student is selected</option>')
                    .prop('disabled', true);
                $('#academic_session_id')
                    .html('<option value="">Auto-filled when student is selected</option>')
                    .prop('disabled', true);
            }
            lockFields();

            $('#student_roll_id').on('change', function() {
                const student_roll_id = $(this).val();
                const $student = $('#student_id');
                const $class = $('#academic_class_id');
                const $session = $('#academic_session_id');

                if (!student_roll_id) {
                    lockFields();
                    return;
                }

                // Optional: loading state
                $student.html('<option value="">Loading…</option>').prop('disabled', true);
                $class.html('<option value="">Loading…</option>').prop('disabled', true);
                $session.html('<option value="">Loading…</option>').prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.fee-management.collections.students-by-class', ':id') }}'
                        .replace(':id', student_roll_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (!res || !res.id) {
                            lockFields();
                            return;
                        }

                        // Fill Student
                        $student.empty()
                            .append(
                                $('<option>', {
                                    value: res.id,
                                    text: res.name + (res.class_name ? ' (' + res
                                        .class_name + ')' : '')
                                })
                            )
                            .prop('disabled', false)
                            .trigger('change.select2'); // refresh Select2 if used

                        // Fill Class
                        $class.empty()
                            .append('<option value="">Select Class</option>')
                            .append(
                                $('<option>', {
                                    value: res.class_id ?? '',
                                    text: res.class_name ?? 'N/A',
                                    selected: true
                                })
                            )
                            .prop('disabled', false)
                            .trigger('change.select2');

                        // Fill Session
                        $session.empty()
                            .append('<option value="">Select Session</option>')
                            .append(
                                $('<option>', {
                                    value: res.session_id ?? '',
                                    text: res.session_name ?? 'N/A',
                                    selected: true
                                })
                            )
                            .prop('disabled', false)
                            .trigger('change.select2');
                    },
                    error: function() {
                        lockFields();
                        alert('Error loading student info. Try again.');
                    }
                });
            });
        });


        $(document).ready(function() {
            let selectedChallan = null;
            // Handle student selection to auto-fill session and load challans
            $('#student_roll_id').change(function() {

                const selectedOption = $(this).find('option:selected');
                const sessionId = selectedOption.data('session-id');
                const sessionName = selectedOption.data('session-name');
                const sessionSelect = $('#academic_session_id');
                const challanSelect = $('#challan_id');
                const studentId = $(this).val();

                if (!sessionId && !sessionName) {
                    sessionSelect.html('<option value="' + sessionId + '">' + sessionName + '</option>');
                    sessionSelect.prop('disabled', false);

                    // Load challans for the student
                    if (studentId) {
                        challanSelect.html('<option value="">Loading challans...</option>').prop('disabled',
                            true);

                        $.ajax({
                            url: '{{ route('admin.fee-management.collections.challans-by-student', ':studentId') }}'
                                .replace(':studentId', studentId),
                            type: 'GET',
                            success: function(response) {
                                challanSelect.html('<option value="">Select Challan</option>');

                                if (response.challans.length > 0) {
                                    response.challans.forEach(function(challan) {
                                        // Fix status display - only 3 statuses: paid, pending, partial
                                        let statusText = 'Pending'; // Default
                                        if (challan.status === 'paid') {
                                            statusText = 'Paid';
                                        } else if (challan.status === 'partial' ||
                                            challan.status === 'partially_paid') {
                                            statusText = 'Partial';
                                        } else {
                                            statusText =
                                                'Pending'; // For generated, empty, or any other status
                                        }

                                        challanSelect.append(
                                            '<option value="' + challan.id + '" ' +
                                            'data-challan-data=\'' + JSON.stringify(
                                                challan) + '\'>' +
                                            challan.challan_number + ' - ' + challan
                                            .billing_month + ' (' + statusText +
                                            ')' +
                                            '</option>'
                                        );
                                    });
                                    challanSelect.prop('disabled', false);
                                } else {
                                    challanSelect.html(
                                        '<option value="">No challans found for this student</option>'
                                    );
                                }
                            },
                            error: function() {
                                challanSelect.html(
                                    '<option value="">Error loading challans</option>');
                            }
                        });
                    }
                } else {
                    sessionSelect.html('<option value="">Auto-filled when student is selected</option>');
                    sessionSelect.prop('disabled', true);
                    challanSelect.html('<option value="">Select student first to load challans</option>')
                        .prop('disabled', true);
                    $('#challanDetails').hide();
                    $('#paymentDetails').hide();
                    $('#submitPayment').prop('disabled', true);
                }
            });

            // Handle challan selection
            $('#challan_id').change(function() {
                const selectedOption = $(this).find('option:selected');
                const challanData = selectedOption.data('challan-data');

                if (challanData) {
                    selectedChallan = challanData;
                    displayChallanDetails(challanData);
                    $('#paymentDetails').show();
                    updatePaymentSummary();
                } else {
                    selectedChallan = null;
                    $('#challanDetails').hide();
                    $('#paymentDetails').hide();
                    $('#submitPayment').prop('disabled', true);
                }
            });

            // Handle amount input
            $('#paid_amount').on('input', function() {
                updatePaymentSummary();
            });

            function displayChallanDetails(challan) {
                console.log(challan);
                $('#challanNumber').text(challan.challan_number);
                $('#totalAmount').text('Rs. ' + parseFloat(challan.total_amount).toLocaleString());
                if (challan.food_amount && parseFloat(challan.food_amount) > 0) {
                    $('#foodIncludedAmount').text('Rs. ' + parseFloat(challan.food_amount).toLocaleString());
                    $('#foodIncludedNote').show();
                } else {
                    $('#foodIncludedNote').hide();
                }
                
                $('#paidAmount').text('Rs. ' + parseFloat(challan.paid_amount || 0).toLocaleString());
                
               
                $('#fineAmount').text('Rs. ' + parseFloat(challan.fine_amount).toLocaleString());
                $('#summaryTotalFine').text('Rs. ' + parseFloat(challan.fine_amount).toLocaleString());

                
                $('#hiddenFineAmount').val(challan.fine_amount);


                $('#dueDate').text(new Date(challan.due_date).toLocaleDateString());
                $('#billingMonth').text(new Date(challan.billing_month + '-01').toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long'
                }));

                // Initialize payment summary with original amounts
                $('#summaryChallanAmount').text('Rs. ' + parseFloat(challan.total_amount).toLocaleString());
                
                $('#summaryFinalAmount').text('Rs. ' + parseFloat(challan.total_amount).toLocaleString());
                

                // Reset discount and transport variables
                window.totalDiscountAmount = 0;
                window.totalTransportFee = 0;
                window.finalAmount = 0;

                // Load discounts for this challan
                loadChallanDiscounts(challan.id);

                // Load transport fees for this student
                const studentId = $('#student_id').val();
                if (studentId) {
                    loadStudentTransportFees(studentId);
                } else {
                    hideTransportSection();
                }

                $('#challanDetails').show();
            }

            function loadChallanDiscounts(challanId) {
                $.ajax({
                    url: '{{ route('admin.fee-management.collections.challan-discounts', ':challanId') }}'
                        .replace(':challanId', challanId),
                    type: 'GET',
                    success: function(response) {
                        if (response.discounts && response.discounts.length > 0) {
                            displayDiscounts(response.discounts, response.totalDiscount, response
                                .finalAmount);
                        } else {
                            hideDiscountSection();
                        }
                    },
                    error: function() {
                        hideDiscountSection();
                    }
                });
            }

            function displayDiscounts(discounts, totalDiscount, finalAmount) {
                let discountHtml = '';
                discounts.forEach(function(discount) {
                    discountHtml += `
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <span class="text-muted">${discount.category_name || 'General'} (${discount.discount_type})</span>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="text-success font-weight-bold">
                                ${discount.discount_type === 'percentage' ? discount.discount_value + '%' : 'Rs. ' + parseFloat(discount.discount_value).toLocaleString()}
                            </span>
                        </div>
                    </div>
                `;
                });

                $('#discountList').html(discountHtml);
                $('#totalDiscount').text('Rs. ' + parseFloat(totalDiscount).toLocaleString());
                $('#discountSection').show();

                // Update payment summary
                $('#summaryTotalDiscount').text('Rs. ' + parseFloat(totalDiscount).toLocaleString());
                $('#summaryDiscountRow').show();

                // Store discount data for payment calculation
                window.totalDiscountAmount = parseFloat(totalDiscount);

                // Calculate final amount with transport fees
                calculateFinalAmount();
            }

            function loadStudentTransportFees(studentId) {
                $.ajax({
                    url: '{{ route('admin.fee-management.collections.student-transport-fees', ':studentId') }}'
                        .replace(':studentId', studentId),
                    type: 'GET',
                    success: function(response) {
                        if (response.transportFees && response.transportFees.length > 0) {
                            displayTransportFees(response.transportFees, response.totalTransportFee);
                        } else {
                            hideTransportSection();
                        }
                    },
                    error: function() {
                        hideTransportSection();
                    }
                });
            }

            function displayTransportFees(transportFees, totalTransportFee) {
                let transportHtml = '';
                transportFees.forEach(function(transport) {
                    transportHtml += `
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <span class="text-muted">${transport.vehicle_number} - ${transport.route_name}</span>
                        </div>
                        <div class="col-md-4 text-right">
                            <span class="text-info font-weight-bold">
                                Rs. ${parseFloat(transport.monthly_charges).toLocaleString()}
                            </span>
                        </div>
                    </div>
                `;
                });

                $('#transportList').html(transportHtml);
                $('#transportFee').text('Rs. ' + parseFloat(totalTransportFee).toLocaleString());
                $('#transportSection').show();

                // Update payment summary
                $('#summaryTransportFee').text('Rs. ' + parseFloat(totalTransportFee).toLocaleString());
                $('#summaryTransportRow').show();
                $('#transportAmount').val(parseFloat(totalTransportFee));

                // Store transport data for payment calculation
                window.totalTransportFee = parseFloat(totalTransportFee);

                // Calculate final amount with transport fees
                calculateFinalAmount();
            }

            function hideTransportSection() {
                $('#transportSection').hide();
                $('#transportFee').text('Rs. 0');

                // Update payment summary
                $('#summaryTransportFee').text('Rs. 0');
                $('#summaryTransportRow').hide();

                // Clear transport data
                window.totalTransportFee = 0;

                // Calculate final amount without transport fees
                calculateFinalAmount();
            }

            function calculateFinalAmount() {
                const originalAmount = parseFloat(selectedChallan.total_amount);
                const discountAmount = window.totalDiscountAmount || 0;
                const transportFee = window.totalTransportFee || 0;

                const fineText = parseFloat($("#hiddenFineAmount").val());  
                // const getFineAmount = parseFloat(fineText.replace(/[^0-9.-]+/g, '')) || 0;
                // alert(fineText);
                const finalAmount = originalAmount + fineText -  discountAmount + transportFee;

                $('#finalAmount').text('Rs. ' + finalAmount.toLocaleString());
                $('#summaryFinalAmount').text('Rs. ' + finalAmount.toLocaleString());

                // Store final amount for payment calculation
                window.finalAmount = finalAmount;

                // Update payment summary
                updatePaymentSummary();
            }

            function hideDiscountSection() {
                $('#discountSection').hide();
                $('#totalDiscount').text('Rs. 0');

                // Update payment summary
                $('#summaryTotalDiscount').text('Rs. 0');
                $('#summaryDiscountRow').hide();

                // Clear discount data
                window.totalDiscountAmount = 0;

                // Calculate final amount without discounts
                calculateFinalAmount();
            }

            function updatePaymentSummary() {
                if (selectedChallan) {
                    const totalAmount = parseFloat(selectedChallan.total_amount);
                    const alreadyPaid = parseFloat(selectedChallan.paid_amount || 0);
                    const newPaymentAmount = parseFloat($('#paid_amount').val()) || 0;

                    // Use final amount (after discount) if available, otherwise use total amount
                    const finalAmount = window.finalAmount || totalAmount;
                    const totalPaidAfterNewPayment = alreadyPaid + newPaymentAmount;
                    const remainingAmount = finalAmount - totalPaidAfterNewPayment;

                    $('#summaryChallanAmount').text('Rs. ' + totalAmount.toLocaleString());
                    $('#summaryAlreadyPaid').text('Rs. ' + alreadyPaid.toLocaleString());
                    $('#summaryPaidAmount').text('Rs. ' + newPaymentAmount.toLocaleString());
                    $('#summaryRemainingAmount').text('Rs. ' + remainingAmount.toLocaleString());

                    // Update max amount based on remaining amount (final amount - already paid)
                    const maxPayableAmount = finalAmount - alreadyPaid;

                    // Update paid_amount input max attribute and hidden value
                    $('#paid_amount').attr('max', maxPayableAmount);
                    $('#maxAmountValue').val(maxPayableAmount);

                    // Enable/disable submit button based on max payable amount
                    if (newPaymentAmount > 0 && newPaymentAmount <= maxPayableAmount) {
                        $('#submitPayment').prop('disabled', false);
                    } else {
                        $('#submitPayment').prop('disabled', true);
                    }
                }
            }

            function getStatusClass(status) {
                switch (status.toLowerCase()) {
                    case 'paid':
                        return 'success';
                    case 'partial':
                        return 'warning';
                    case 'pending':
                        return 'info';
                    default:
                        return 'info'; // Default to pending for any other status
                }
            }

            // Real-time validation for paid amount input
            $('#paid_amount').on('input', function() {
                const paidAmount = parseFloat($(this).val()) || 0;
                const maxPayableAmount = parseFloat($('#maxAmountValue').val()) || 0;

                if (paidAmount > maxPayableAmount) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                    $(this).after(
                        '<div class="invalid-feedback">Payment amount cannot exceed maximum payable amount (Rs. ' +
                        maxPayableAmount.toLocaleString() + ')</div>');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').remove();
                }

                // Update payment summary
                updatePaymentSummary();
            });

            // Form validation
            $('#challanPaymentForm').on('submit', function(e) {
                console.log('Form submitted');
                console.log('Selected challan:', selectedChallan);
                console.log('Form data:', $(this).serialize());

                if (!selectedChallan) {
                    e.preventDefault();
                    toastr.error('Please select a challan');
                    return false;
                }

                const paidAmount = parseFloat($('#paid_amount').val()) || 0;
                const maxPayableAmount = parseFloat($('#maxAmountValue').val()) || 0;

                console.log('Paid amount:', paidAmount);
                console.log('Max payable amount:', maxPayableAmount);

                if (paidAmount <= 0) {
                    e.preventDefault();
                    toastr.error('Please enter a valid amount');
                    return false;
                }

                if (paidAmount > maxPayableAmount) {
                    e.preventDefault();
                    toastr.error('Payment amount cannot exceed maximum payable amount (Rs. ' +
                        maxPayableAmount.toLocaleString() + ')');
                    return false;
                }

                console.log('Form validation passed, submitting...');
            });
        });
    </script>

@endsection

@section('css')
    <style>
        .badge {
            color: #212529 !important;
        }

        .badge-success {
            background-color: #28a745 !important;
            color: #212529 !important;
        }

        .badge-danger {
            background-color: #dc3545 !important;
            color: #212529 !important;
        }

        .badge-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .badge-info {
            background-color: #17a2b8 !important;
            color: #212529 !important;
        }

        .badge-secondary {
            background-color: #6c757d !important;
            color: #212529 !important;
        }

        /* Professional styling improvements */
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            border-bottom: none;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e1e5e9;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #545b62);
            border: none;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #545b62, #3d4449);
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .payment-summary {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #dee2e6;
        }

        .challan-details {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #ffc107;
        }

        /* Button layout fixes */
        .btn-block {
            width: 100%;
            margin-bottom: 10px;
        }

        @media (min-width: 768px) {
            .btn-block {
                margin-bottom: 0;
            }
        }

        /* Force button styling */
        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057) !important;
            border: none !important;
            color: white !important;
            font-weight: 600 !important;
        }
    </style>
@endsection
