@extends('admin.layouts.main')

@section('title')
    Advances
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Advances </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.advances.index') !!}" class="btn btn-primary btn-md "> Back </a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{!! route('hr.advances.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="employee">Employee <b>*</b></label>
                                    <select id="employee_id" required name="employee_id"
                                            class="form-select select2 basic-single">
                                        <option>Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">Name <b>*</b></label>
                                    <input type="text" class="form-control" name="name">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="amount">Amount <b>*</b></label>
                                    <input type="number" id="amount" class="form-control" name="amount">
                                    <input type="hidden" id="amount_val">
                                </div>
                                <div class="col-md-4">
                                    <label for="amount">Duration <b>*</b></label>
                                    <select id="duration" required name="duration"
                                            class="form-select select2 basic-single">
                                        <option>Select Duration</option>
                                        @foreach($durations as $duration)
                                            <option value="{{$duration}}">{{$duration}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="effective">Effective From <b>*</b></label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                            </div>


                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label for="installment">Installment Amount <b>*</b></label>
                                    <input type="number" class="form-control" readonly name="installmentAmount" id="installmentAmount">
                                </div>
                                <div class="col-md-4">
                                    <label for="amount">Amount To pay <b>*</b></label>
                                    <input type="number" class="form-control" id="amountToPay" readonly name="amount_to_pay">

                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <input type="file" class="form-control" name="image">
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary">Save</button>
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
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 1000,
                showConfirmButton: false
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
            });
            @endif
        });
    </script>

    <script>
        $(document).ready(function(){
            $('#amount, #duration').change(calculateInstallment);

            function calculateInstallment() {
                var amount = parseFloat($('#amount').val());
                var duration = parseFloat($('#duration').val());

                if (isNaN(amount) || isNaN(duration) || duration === 0) {
                    $('#installmentAmount').val('');
                    return;
                }
                $('#amountToPay').val(amount);
                var installmentAmount = Math.floor(amount / duration);
                $('#installmentAmount').val(installmentAmount);
            }

        });
    </script>
    <script>
        $(document).ready(function(){
            $('#employee_id').on('change', function () {
                var emp_id = $(this).val();

                $.ajax({
                    url: '{{route('hr.employee.salary')}}',
                    method: 'POST',
                    data: {
                        emp_id: emp_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#amount').val(response);
                        $('#amount_val').val(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <script>
        $('#amount').on('input', function(){
            let amount = parseFloat($(this).val());
            let amountVal = parseFloat($('#amount_val').val());

            if (isNaN(amount) || amount < 0) {
                amount = 0;
            }
            if (amount > amountVal) {
                alert("Cannot Add more Advance")
                $(this).val(amountVal);
            }
        });
    </script>


@endsection
