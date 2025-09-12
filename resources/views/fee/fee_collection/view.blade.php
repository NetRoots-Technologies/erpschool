@extends('admin.layouts.main')

@section('title')
Billing
@stop
@section('css')
<style>
    .bg-info {
        background-color: #525252 !important;
    }

    .dt-button.buttons-columnVisibility {
        background: blue !important;
        color: white !important;
        opacity: 0.5;
    }

    .dt-button.buttons-columnVisibility.active {
        background: lightgrey !important;
        color: black !important;
        opacity: 1;
    }

    .accordion-button {
        background-color: #025CD8;
        color: #ffffff;
        border: 1px solid #025CD8;
        border-radius: 0.25rem;
        padding: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .accordion-button:hover {
        background-color: white;
        color: black;
    }

    .collapse {
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-top: 0;
        border-radius: 0 0 0.25rem 0.25rem;
        background-color: #f8f9fa;
    }

    .card_header {
        border: none;
        background-color: transparent;
    }
</style>

@endsection
@section('content')

<div id="accordion">
    @if($studentsFees && $sessions)
    @foreach($sessions as $session)

    <div class="card">
        <div class="card-header card_header" id="heading{{$session->id}}">
            <h5 class="mb-0">
                <button class="accordion-button" data-toggle="collapse" type="button"
                    data-target="#collapse{{$session->id}}" aria-expanded="true" style="text-decoration: none"
                    aria-controls="collapse{{$session->id}}">
                    <b>{!! $session->name . ' ' . date('y', strtotime($session->start_date)) . '-' . date('y',
                        strtotime($session->end_date)) !!}</b>
                </button>
            </h5>
        </div>

        <div id="collapse{{$session->id}}" class="collapse @if($session->status == '1') show @endif"
            aria-labelledby="heading{{$session->id}}" data-parent="#accordion">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Father Name</th>
                                <th>Bill No</th>
                                <th>Student</th>
                                <th>Class</th>
                                <th>Billing Month</th>
                                <th>Due Date</th>
                                <th>Valid Date</th>
                                <th>Fees(RS)</th>
                                <th>Paid Amount</th>
                                <th>Status</th>
                                <th>Installment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentsFees as $fee)

                            @if($fee->AcademicSession->id == $session->id)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>

                                    {!! $fee->student->father_name ?? ''!!}
                                </td>

                                <td>

                                    {!! $fee->bill_number ?? ''!!}
                                </td>

                                <td>
                                    {!! $fee->student->fullname !!}
                                </td>
                                <td>

                                    {!! $fee->AcademicClass->name ?? ''!!}
                                </td>

                                <td>
                                    {!! \Carbon\Carbon::parse($fee->year_month)->format('F Y') !!}
                                </td>
                                <td>
                                    {!! $fee->due_date!!}
                                </td>
                                <td>
                                    {!! $fee->valid_date!!}
                                </td>
                                <td>
                                    {!! $fee->fees!!}
                                </td>

                                <td>
                                    {!! 'RS ' . ($fee->paid_amount ?? 0) !!}
                                </td>

                                <td>
                                    {!! $fee->status == 1 ? '<span style="color: green;">paid</span>' : '<span
                                        style="color: red;">unpaid</span>' !!}
                                </td>
                                <td>
                                    {!! $fee->voucher_number . '-Installment' !!}
                                </td>

                                <td>

                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropbtn" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Select
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                            style="max-height: 200px; overflow-y: auto;">
                                            <a class="dropdown-item"
                                                href="{{ route('admin.fee-collection-print',$fee->id)}}">Print
                                                Voucher</a>
                                            @if($fee->status == 0 && $fee->installment_allow == 1)
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                onclick="myFunction({{$fee->id}})" data-bs-target="#firstmodal">Make
                                                Installments</a>
                                            @endif
                                            @if($fee->status == 0)
                                            <a class="dropdown-item change-status" data-id="{{ $fee->id }}"
                                                type="button" data-status="1">Paid Voucher</a>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.fee-collection.edit',$fee->id)}}"
                                                type="button">Edit Voucher</a>
                                        </div>

                                        @endif
                                    </div>

                                </td>
                            </tr>
                            @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @else
    <div>Add Students to this Class First</div>
    @endif

    <div class="row">
        <div class="modal fade" id="firstmodal" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center p-5">
                        <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px">
                        </lord-icon>
                        <div class="mt-4 pt-4">
                            <h4>Uh oh, Make Installment!</h4>
                            <p class="text-muted">Are you sure.? Want to
                                make Installment.</p>
                            <!-- Toogle to second dialog -->
                            <button type="button" class="btn btn-warning" data-bs-target="#secondmodal"
                                data-bs-toggle="modal" data-bs-dismiss="modal">
                                Yes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="secondmodal" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center p-5">
                        <lord-icon src="https://cdn.lordicon.com/zpxybbhl.json" trigger="loop"
                            colors="primary:#405189,secondary:#0ab39c" style="width:150px;height:150px"></lord-icon>
                        <div class="mt-4 pt-3">
                            <h4 class="mb-3">Select Due Date:</h4>
                            <form id="dateForm" method="post"
                                action="{!! route('admin.fee-collection-make-installments') !!}">
                                {{ csrf_field()}}
                                <div class="form-group">
                                    <input type="date" name="due_date" class="form-control" required />
                                </div>
                                <input type="hidden" name="fc_id" id="fc_id_number" class="form-control input-sm" />
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function myFunction($id) {
            document.getElementById('fc_id_number').value = $id;
        }
</script>

<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Change Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="paid_date" name="paid_date">
                    </div>
                    <div class="col-md-6">
                        <label for="Amount">Amount</label>
                        <input type="number" class="form-control" id="paid_amount" name="paid_amount">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmStatusChange" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $('.change-status').on('click', function (e) {
                e.preventDefault();

                var id = $(this).data('id');
                var status = $(this).data('status');


                $('#confirmStatusChange').data('id', id);
                $('#confirmStatusChange').data('status', status);

                $('#statusModal').modal('show');
            });


            $('#confirmStatusChange').on('click', function () {
                var id = $(this).data('id');
                var status = $(this).data('status');
                var paidDate = $('#paid_date').val();
                var paidAmount = $('#paid_amount').val();
                var loader = $('<div class="loader"></div>').appendTo('body');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.bill-generation.change-status') }}',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        id: id,
                        status: status,
                        paid_date: paidDate,
                        paid_amount: paidAmount,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        loader.remove();
                        $('#statusModal').modal('hide');
                        location.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Status updated successfully.',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr, status, error) {
                        loader.remove();
                        $('#statusModal').modal('hide');
                        console.error(xhr.responseText);
                    }
                });
            });
</script>

@endsection