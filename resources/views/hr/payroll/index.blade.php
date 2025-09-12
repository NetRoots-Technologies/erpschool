@extends('admin.layouts.main')

@section('title')
Payroll | Generate
@stop
@section('css')
<style>
    .error {
        color: red;
        font-weight: 500;
        font-size: 14px;
    }
</style>
@endsection

@section('content')

<style>
    .red-tooltip+.tooltip>.tooltip-inner {
        background-color: #f00;
    }
</style>

<div class="container-fluid">

    <div class="card">
        <div class="card-body">

            <form method="get" id="payrollForm">
                <div class="row mb-3">
                    <div class="col-lg-3">
                        <label for="branch"><b>Branch Name *</b></label>
                        <select class="form-select-lg select2 select2-selection--single" name="branch_id"
                            id="selectBranch" aria-label="Default select example">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">
                                {{ $branch->name }}
                            </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="employee_leave_applying"><b>Department</b></label>
                            <select class="form-select select2" name="department_id" id="selectDepartment"
                                aria-label="Default select example">


                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <label for="employee"><b>Employee Name </b></label>
                        <select class="form-select-lg select2 select2-selection--single" name="hrm_employee_id"
                            id="selectEmployee" aria-label="Default select example">

                        </select>
                    </div>

                    <div class="form-group col-md-3" id="from_date_div">
                        <label for="selectMonth"><b>Select Month/Year</b></label>
                        <input type="month" id="month_year" name="month_year" class="form-control"
                            value="{{ date('Y-m') }}">

                    </div>


                </div>

                <div class="row mb-3">
                    <div class="col" style="margin-top: 10px">
                        <button id="filter_btn" class="btn btn-primary">Load Data</button>
                    </div>
                </div>
            </form>
            <div>
                <div class="box-footer" style="float: right">
                    {!! Form::submit(trans('Generate'), ['class' => 'btn btn-danger paysalarybutton globalSaveBtn', 'id'
                    => 'generateButton']) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form id="salaryForm">
                <input type="hidden" name="branch_id" id="branch_id">
                <input type="hidden" name="generated_month" id="generated_month">
                <input type="hidden" name="department_id" id="department_id">
                <input type="hidden" name="hrm_employee_id" id="hrm_employee_id">
                <input type="hidden" name="generated_month_year" id="generated_month_year">
                <div class="row align-items-center my-3 date-div d-none">
                    <div class="col-2 text-end">
                        <label for="bank_account_ledger" class="form-label">Bank Account Ledger</label>
                    </div>
                    <div class="col-2">
                        <select class="form-control" name="bank_account_ledger" id="bank_account_ledger" required>
                            <option value="" disabled selected>Select Bank Account</option>
                            @foreach($ledgers as $bank_account)
                            <option value="{{ $bank_account->id }}">
                                {{ $bank_account->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2 text-end">
                        <label for="start_date" class="form-label">Start Date:</label>
                    </div>
                    <div class="col-2">
                        <input class="form-control" type="date" name="start_date" id="start_date" readonly>
                    </div>
                    <div class="col-2 text-end">
                        <label for="end_date" class="form-label">End Date:</label>
                    </div>
                    <div class="col-2">
                        <input class="form-control" type="date" name="end_date" id="end_date" readonly>
                    </div>

                    <div class="col-3"></div>
                </div>

                <div class="panel-body pad table-responsive">
                    <table class="table table-responsive table-striped table-bordered">
                        <tr>
                            <th>Sr.No</th>
                            <th>Name</th>
                            <th>Basic Salary</th>
                            <th>Committed Time (In Hour)</th>
                            <th>Actualized Time (In Hour)</th>
                            <th>Total Salary</th>
                            <th>Advance Installment</th>
                            <th>EOBI (Amount)</th>
                            <th>Provident Fund</th>
                            <th>Total Fund Amount</th>
                            <th>Medical Allowance</th>
                            <th>Assessment Amount</th>
                            <th>Tax Amount</th>
                            <th>Net Salary</th>
                            <th>Cash Handed Over</th>
                            <th>Bank Transfer</th>
                        </tr>
                        <tbody id="loadData">
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('js')

@include('hr.js.js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {

        const uri = @json(route('hr.salary.data'));

        // $("#salaryForm").validate({
        //     rules: {
        //         bank_account_ledger: {
        //             required: true
        //         },
        //         start_date: {
        //             required: true
        //         },
        //         end_date: {
        //             required: true
        //         },
        //         'cash_in_hand[]': {
        //             required: true,
        //             number: true,
        //             min: 0
        //         },
        //         'cash_in_bank[]': {
        //             required: true,
        //             number: true,
        //             min: 0
        //         }
        //     },
        //     messages: {
        //         bank_account_ledger: {
        //             required: 'Please select bank account.'
        //         },
        //         start_date: {
        //             required: 'Please select start date.'
        //         },
        //         end_date: {
        //             required: 'Please select end date.'
        //         },
        //         'cash_in_hand[]': {
        //             required: "Cash in Hand is required",
        //             number: "Please enter a valid number",
        //             min: "Cash in Hand must be at least 0"
        //         },
        //         'cash_in_bank[]': {
        //             required: "Cash in Bank is required",
        //             number: "Please enter a valid number",
        //             min: "Cash in Bank must be at least 0"
        //         }
        //     }
        // });

        $.validator.addMethod("checkArray", function (value, element, param) {
        const allFields = $(param);
        let isValid = true;
        allFields.each(function () {
            if ($(this).val().trim() === "") {
                isValid = false;
                return false;
            }
        });
        return isValid;
    }, "All fields are required.");
    $("#salaryForm").validate({
        rules: {
            'cash_in_hand[]': {
                checkArray: ".cash-in-hand"
            },
            'cash_in_bank[]': {
                checkArray: ".cash-in-bank"
            }
        },
        messages: {
            'cash_in_hand[]': {
                checkArray: "Please fill out all Cash in Hand fields."
            },
            'cash_in_bank[]': {
                checkArray: "Please fill out all Cash in Bank fields."
            }
        },
        submitHandler: function (form) {
            alert("Form is valid and ready to submit!");
            form.submit();
        }
    });

        $("#payrollForm").validate({
            errorPlacment: function (element, error)
            {
                error.insertAfter(element.parent());
            },
            rules: {
                branch_id: {
                    required: true
                },
                department_id: {
                    required: true
                },
            },
            messages: {
                branch_id: {
                    required: 'Please select branch.'
                },
                department_id: {
                    required: 'Please select department.'
                }
            }
        });

        $('#filter_btn').click(function (e) {
            e.preventDefault();
            if (!$('#payrollForm').valid())
            {
                return false;
            }
            var formData = $('#payrollForm').serialize();
            var loader = $('<div class="loader"></div>').appendTo('body');

            $.ajax({
                type: 'GET',
                url: '{{route('hr.payroll.filter_data')}}',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {

                    $('#branch_id').val(data.branch_id);
                    $('#generated_month').val(data.generated_month);
                    $('#department_id').val(data.department_id);
                    $('#hrm_employee_id').val(data.hrm_employee_id);
                    $('#generated_month_year').val(data.generated_month_year);
                    $('#start_date').val(data.start_date);
                    $('#end_date').val(data.end_date);
                    $(`.date-div`).removeClass('d-none');
                    $.ajax({
                        type: 'GET',
                        url: '{{route('hr.payroll.data')}}',
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (data1) {
                            loader.remove();

                            $('#loadData').html(data1);
                        },
                        error: function (xhr, status, error) {
                            loader.remove();
                            console.error('Error:', error);
                        }
                    });
                },
                error: function (xhr, status, error) {
                    loader.remove();
                    console.error('Error:', error);
                }
            });
        });

        $('#generateButton').click(function (e) {
            e.preventDefault();

            if (!$("#salaryForm").valid() || !$("#payrollForm").valid())
            {
                return false;
            }

            const total_present = $('input[name="total_present"]').map(function() {
                return $(this).val();
            }).get();
            const total_absent = $('input[name="total_absent"]').map(function() {
                return $(this).val();
            }).get();
            const employee_id = $('input[name="employee_id[]"]').map(function() {
                return $(this).val();
            }).get();
            const employee_name = $('input[name="employee_name[]"]').map(function() {
                return $(this).val();
            }).get();
            const employee_salary = $('input[name="employee_salary[]"]').map(function() {
                return $(this).val();
            }).get();
            const committedTime = $('input[name="committedTime[]"]').map(function() {
                return $(this).val();
            }).get();
            const total_worked = $('input[name="total_worked[]"]').map(function() {
                return $(this).val();
            }).get();
            const calculated_salary = $('input[name="calculated_salary[]"]').map(function() {
                return $(this).val();
            }).get();
            const advanceInstallment = $('input[name="advanceInstallment[]"]').map(function() {
                return $(this).val();
            }).get();
            const side_values = $('input[name="side_values[]"]').map(function() {
                return $(this).val();
            }).get();
            const total_values = $('input[name="total_values[]"]').map(function() {
                return $(this).val();
            }).get();
            const medicalAllowance = $('input[name="medicalAllowance[]"]').map(function() {
                return $(this).val();
            }).get();
            const total_salary = $('input[name="total_salary[]"]').map(function() {
                return $(this).val();
            }).get();
            const cash_in_hand = $('input[name="cash_in_hand[]"]').map(function() {
                return $(this).val();
            }).get();
            const cash_in_bank = $('input[name="cash_in_bank[]"]').map(function() {
                return $(this).val();
            }).get();


            const data = {
                "branch_id": $(`#branch_id`).val(),
                "generated_month": $("#month_year").val(),
                "department_id": $("#selectDepartment").val(),
                "hrm_employee_id": $("#hrm_employee_id").val(),
                "generated_month_year": $("#generated_month_year").val(),
                "bank_account_ledger": $("#bank_account_ledger").val(),
                "start_date": $("#start_date").val(),
                "end_date": $("#end_date").val(),
                "total_present": total_present,
                "total_absent": total_absent,
                "employee_id": employee_id,
                "employee_name": employee_name,
                "employee_salary": employee_salary,
                "committedTime": committedTime,
                "total_worked": total_worked,
                "calculated_salary": calculated_salary,
                "advanceInstallment": advanceInstallment,
                "side_values": side_values,
                "total_values": total_values,
                "medicalAllowance": medicalAllowance,
                "total_salary": total_salary,
                "cash_in_hand": cash_in_hand,
                "cash_in_bank": cash_in_bank
            }

            $.ajax({
                type: 'POST',
                url: uri,
                data: JSON.stringify(data),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    // loader.remove();

                    Swal.fire({
                        title: 'Generated!',
                        text: response.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function (xhr, status, error) {
                    // loader.remove();
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error('Error', xhr.responseJSON.error);
                    } else {
                        const errorMessage = 'Error saving form data: ' + error;
                        toastr.error('Error', errorMessage);
                    }
                }
            });
            console.log("🚀 ~ data>>", data)
        });
        });
</script>
@endsection
