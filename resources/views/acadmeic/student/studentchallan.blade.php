@extends('admin.layouts.main')

@section('title', 'Student Challans')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-3">

    <div class="row mb-2">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader"></div>
                <h4 class="page-title mb-0">Challans for {{ $studentDatabank->first_name }} {{ $studentDatabank->last_name }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('academic.studentDataBank.index') }}">Pre Admission</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Challans</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <table id="challansTable" class="table table-bordered table-striped" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Challan No</th>
                        <th>Reference No</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Issue Date</th>
                        <th>Class</th>
                        <th>Due Date</th>
                        <th>Paid Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentDatabank->challans as $challan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $challan->challan_no }}</td>
                        <td>{{ $challan->reference_no }}</td>
                        <td>{{ number_format($challan->amount, 2) }}</td>
                        <td>{{ ucfirst($challan->status) }}</td>
                        <td>{{ $challan->issue_date }}</td>
                        <td>{{ $studentDatabank->classes->name ?? 'N/A' }}</td>
                        <td>{{ $challan->due_date ?? '-' }}</td>
                        <td>{{ $challan->paid_date ?? '-' }}</td>
                        <td>
                            <button
                                class="btn btn-sm btn-info open-payment-modal"
                                data-challan-id="{{ $challan->id }}"
                                data-class-id="{{ $studentDatabank->classes->id ?? '' }}">
                                Payment
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Challan Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label><b>Payment Mode *</b></label>
                    <select id="payment_mode" class="form-control">
                        <option value="">-- Select Payment Mode --</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmPayment" class="btn btn-success">Proceed</button>
            </div>

        </div>
    </div>
</div>

@endsection

@section('js')
<script>
$(document).ready(function () {

    // Initialize DataTable
    $('#challansTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "searching": true,
        "responsive": true
    });

    let challanId = null;
    let classId = null;

    // Open modal
    $('.open-payment-modal').on('click', function () {
        challanId = $(this).data('challan-id');
        classId = $(this).data('class-id'); // Capture class ID
        $('#payment_mode').val('');
        $('#paymentModal').modal('show');
    });

    // Proceed button
    $('#confirmPayment').on('click', function () {
        let paymentMode = $('#payment_mode').val();

        if (!paymentMode) {
            alert('Please select payment mode');
            return;
        }

        // Build route URL
        let url = "{{ route('academic.studentChallans.payment', ':id') }}";
        url = url.replace(':id', challanId);

        // Redirect with payment mode and class ID
        window.location.href = url + '?payment_mode=' + paymentMode + '&class_id=' + classId;
    });

});
</script>
@endsection
