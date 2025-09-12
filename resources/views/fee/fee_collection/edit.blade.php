@extends('admin.layouts.main')

@section('title')
    Student Fee
@stop

@section('content')

    @php
        $totalMonthlyAmount = 0;
        $totalDiscount = 0;
        $totalDiscountRupees = 0;
        $totalClaim1 = 0;
        $totalClaim2 = 0;
    @endphp

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Update Student Fee</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.fee-collection.index') !!}"
                                   class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('admin.fee-collection.update',$billing->id) !!}"
                              enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')

                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:10px;">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label for="Academic"><b>Academic Session </b></label>
                                            <select name="session_id"
                                                    class="form-control session_select  select2 basic-single select_option"
                                                    readonly="" id="session_id">
                                                @foreach($sessions as $key => $item)
                                                    <option
                                                        value="{!! $key !!}" {!! $billing->session_id == $key ? 'selected' : '' !!}>{!! $item !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Company </b></label>
                                            <select name="company_id"
                                                    class="form-control  select2 basic-single company_select select_option"
                                                    readonly id="companySelect">
                                                @foreach($companies as $item)
                                                    <option
                                                        value="{{$item->id}}" {!! $billing->company_id == $item->id ? 'selected' : '' !!}>{{ $item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="branch_Style"><b>Branch</b></label>
                                                </div>
                                                <select name="branch_id"
                                                        class="form-control  select2 basic-single branch_select select_option"
                                                        readonly id="branch_id">

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label for="classes"><b>Class: </b></label>
                                            <select required name="class_id"
                                                    class="form-select select2 basic-single mt-3 class_select select_option"
                                                    readonly aria-label=".form-select-lg example">

                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="month"><b>Starting Date:*</b></label>
                                            <input type="date" class="form-control" readonly
                                                   value="{{ $billing->charge_from }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="month"><b>Ending Date:*</b></label>
                                            <input type="date" class="form-control" readonly
                                                   value="{{ $billing->charge_to }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="card-body table-responsive mb-5">
                                <div>
                                    <b>Student: {{ $billing->student->first_name }} {{ $billing->student->last_name }}</b>
                                    <input type="hidden" name="student_id" value="{{ $billing->student->id ?? ''}}">
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr style="text-align:center; background-color:#025CD8;">
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Sr.No</th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Fee Head</th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Old Amount</th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">New Amount</th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Difference</th>
                                    </tr>
                                    </thead>
                                    <tbody id="loadData">
                                    @if(isset($billing->billingData))
                                        @foreach($billing->billingData as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ @$item->feeHead->fee_head }}
                                                    <input type="hidden" name="fee_head_id[]"
                                                           value="{!! $item->feeHead->id ?? '' !!}">
                                                </td>
                                                <td><input type="number" name="old_bill_amount[]"
                                                           class="form-control old-monthly-amount"
                                                           value="{{ $item->bills_amount ?? '' }}"></td>
                                                <td><input type="number" name="new_bill_amount[]"
                                                           class="form-control new-monthly-amount"
                                                           value="{{ $item->bills_amount ?? '' }}"></td>
                                                <td><span class="difference-amount">0</span></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2" style="text-align:right;"><strong>Total Old Amount: </strong></td>
                                        <td><span id="totalOldAmount">0</span></td>
                                        <td><span id="totalNewAmount">0</span></td>


                                        <input type="hidden" name="newAmount" id="inputNewAmount">


                                        <td>
                                            <input type="hidden" name="TotalDifference" id="inputTotalDifference">
                                            <span id="totalDifference">0</span>
                                            <select name="amount_type" class="form-control amount-type">
                                                <option value="discounted">Discounted</option>
                                                <option value="arrears">Arrears</option>
                                            </select>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>


                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"
                                        style="margin-bottom: 10px;margin-left: 10px;">Save
                                </button>
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
        var branch_id;
        $(document).ready(function () {


            $('#companySelect').on('change', function () {
                var selectedCompanyId = $('#companySelect').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {
                        var branchesDropdown = $('.branch_select').empty();

                        branchesDropdown.append('<option value="">Select Branch</option>');

                        data.forEach(function (branch) {

                            var selectedBranch = branch.id == '{{ $billing->branch_id }}' ? 'selected' : '';

                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectedBranch + '>' + branch.name + '</option>');

                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


            $('.branch_select').on('change', function () {
                branch_id = $(this).val();
                console.log(branch_id);
                if (branch_id == null) {
                    branch_id = {!! $billing->branch_id !!};
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.class_select').empty();

                        data.forEach(function (academic_class) {
                            var selectedClass = academic_class.id == '{{ $billing->class_id }}' ? 'selected' : '';

                            sectionDropdown.append('<option value="' + academic_class.id + '" ' + selectedClass + '>' + academic_class.name + '</option>');

                        });

                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        })

        $('.select_option').prop('disabled', true);

    </script>



    @include('fee.fee_js.js')
    <script>
        function calculateTotal(selector, totalElementId) {
            let total = 0;
            $(selector).each(function() {
                let value = parseFloat($(this).val()) || 0;
                total += value;
            });
            $('#' + totalElementId).text(total.toFixed(0));
            return total;
        }

        function calculateDifference() {
            let totalDifference = 0;
            $('tbody tr').each(function() {
                let oldAmount = parseFloat($(this).find('.old-monthly-amount').val()) || 0;
                let newAmount = parseFloat($(this).find('.new-monthly-amount').val()) || 0;
                let difference = oldAmount - newAmount;
                $(this).find('.difference-amount').text(difference.toFixed(0));
                totalDifference += difference;
            });
            $('#totalDifference').text(totalDifference.toFixed(0));
            $('#inputTotalDifference').val(totalDifference.toFixed(0));
        }

        function calculateTotals() {
            let totalOldAmount = calculateTotal('.old-monthly-amount', 'totalOldAmount');
            let totalNewAmount = calculateTotal('.new-monthly-amount', 'totalNewAmount');
            calculateDifference();

            $('#inputNewAmount').val(totalNewAmount.toFixed(0));
        }

        $(document).ready(function() {
            calculateTotals();
            $('.old-monthly-amount, .new-monthly-amount').on('input', function() {
                let oldAmount = parseFloat($(this).closest('tr').find('.old-monthly-amount').val()) || 0;
                let newAmount = parseFloat($(this).val()) || 0;
                if (newAmount > oldAmount) {
                    $(this).addClass('error');
                    $(this).closest('tr').find('.error-message').text('New amount cannot be greater than old amount');
                } else {
                    $(this).removeClass('error');
                    $(this).closest('tr').find('.error-message').text('');
                    calculateTotals();
                }
            });
        });
    </script>










@endsection
