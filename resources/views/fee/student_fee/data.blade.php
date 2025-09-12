<style>
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


<div id="accordion">
    @if($students->isNotEmpty())
    <input type="checkbox" id="select-all-checkbox">
    <label for="select-all-checkbox">Select All</label>
    @foreach($students as $key => $student)
    <?php  $totalAmountAfterDiscount = $total_monthly_amount = $total_discount_percent = $total_discount_rupees = $total_claim1 = $total_claim2 = 0; ?>

    <div class="card">
        <div class="card-header card_header" id="heading{{$student->id}}">
            <h5 class="mb-0">
                <button class="accordion-button" data-toggle="collapse" type="button"
                    data-target="#collapse{{$student->id}}" aria-expanded="true" style="text-decoration: none"
                    aria-controls="collapse{{$student->id}}">
                    <b>{!! $student->first_name !!} {!! $student->last_name !!}</b>
                    <input type="hidden" name="student[]" value="{!! $student->id !!}">
                </button>
            </h5>
        </div>

        <div id="collapse{{$student->id}}" class="collapse @if($loop->first) show @endif"
            aria-labelledby="heading{{$student->id}}" data-parent="#accordion">
            <td>
                <div class="form-group">
                    <input type="checkbox" name="student_checkbox[{!! $student->id !!}]"
                        class="student-checkbox check_if_checked" value="{!! $student->id !!}">
                </div>
            </td>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="branch_Style"><b>Fee Factor*</b></label>
                        <select name="fee_factor_id[{{$student->id}}]" class="form-control select2 fee_factor" required>
                            @foreach($feeFactors as $key => $item)
                            <option {{$key==1 ? 'selected' : '' }} value="{{ $item }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <table class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Fee Head</th>
                            <th>Month Amount</th>
                            <th>Discount(%)</th>
                            <th>Discount(RS)</th>
                            <th>Claim1</th>
                            <th>Claim2</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($feeStructure->feeStructureValue))
                        @foreach($feeStructure->feeStructureValue as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ @$item->feeHead->fee_head }}
                                <input type="hidden" name="fee_head_id[{!! $student->id !!}][]"
                                    value="{!! $item->feeHead->id ?? '' !!}">
                            </td>
                            <td><input type="number" name="monthly_amount[{!! $student->id !!}][]"
                                    class="form-control total-monthly-amount" value="{{ $item->monthly_amount ?? '' }}">
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="discount[{!! $student->id !!}][]"
                                        value="{{ $item->discount_percent ?? 0 }}"
                                        class="form-control discount-percentage change-amount">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="number" name="discount_rupees[{!! $student->id !!}][]"
                                        value="{{ $item->discount_rupees ?? 0 }}"
                                        class="form-control discount-rupees change-amount">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="number" name="claim_1[{!! $student->id !!}][]"
                                        value="{{ $item->claim1 ?? 0 }}" class="form-control claim-1 change-amount">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="number" name="claim_2[{!! $student->id !!}][]"
                                        value="{{ $item->claim2 ?? 0 }}" class="form-control claim-2 change-amount">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="number" name="total_amount_after_discount[{!! $student->id !!}][]"
                                        value="{{ $item->total_amount_after_discount ?? 0 }}"
                                        class="form-control total-amount-after-discount" readonly>
                                </div>
                            </td>
                        </tr>
                        <?php
                                    $total_monthly_amount += $item->monthly_amount ?? 0;
                                    $totalAmountAfterDiscount += $item->total_amount_after_discount ?? 0;
                                    $total_discount_percent += $item->discount_percent ?? 0;
                                    $total_discount_rupees += $item->discount_rupees ?? 0;
                                    $total_claim1 += $item->claim1 ?? 0;
                                    $total_claim2 += $item->claim2 ?? 0;
                                    ?>
                        @endforeach
                        @endif
                    </tbody>
                    <tr>
                        <td colspan="2"><b>Total Amount</b></td>
                        <td><span id="total_monthly_amount{{$student->id}}">{{$total_monthly_amount}}</span>
                            <input type="hidden" name="total_amount_of_month[{{$student->id}}]"
                                id="total_amount_of_month{{$student->id}}">
                        </td>
                        <td><span id="total_discount_percent{{$student->id}}">{{$total_discount_percent}}</span>
                        </td>
                        <td><span id="total_discount_rupees{{$student->id}}">{{$total_discount_rupees}}</span>
                        </td>
                        <td><span id="total_claim1{{$student->id}}">{{$total_claim1}}</span></td>
                        <td><span id="total_claim2{{$student->id}}">{{$total_claim2}}</span></td>
                        <td><span id="total_amount_after_discount{{$student->id}}">{{$totalAmountAfterDiscount}}</span>
                            <input type="hidden" name="total_amount_of_discount[{{$student->id}}]"
                                id="total_amount_of_discount{{$student->id}}">

                        </td>
                    </tr>
                </table>
                <div>
                    <br>
                    <br>
                    <br>

                </div>
            </div>
        </div>
    </div>
    @endforeach

    @else
    <div>Add Students to this Class First</div>
    @endif


</div>
<script>
    $(document).ready(function () {
        function updateTotals(studentId) {
            let totalMonthlyAmount = 0;
            let totalDiscountPercent = 0;
            let totalDiscountRupees = 0;
            let totalClaim1 = 0;
            let totalClaim2 = 0;
            let totalAmountAfterDiscount = 0;

            $('.total-monthly-amount[name="monthly_amount[' + studentId + '][]"]').each(function () {
                totalMonthlyAmount += parseFloat($(this).val()) || 0;
            });
            $('.discount-percentage[name="discount[' + studentId + '][]"]').each(function () {
                totalDiscountPercent += parseFloat($(this).val()) || 0;
            });
            $('.discount-rupees[name="discount_rupees[' + studentId + '][]"]').each(function () {
                totalDiscountRupees += parseFloat($(this).val()) || 0;
            });
            $('.claim-1[name="claim_1[' + studentId + '][]"]').each(function () {
                totalClaim1 += parseFloat($(this).val()) || 0;
            });
            $('.claim-2[name="claim_2[' + studentId + '][]"]').each(function () {
                totalClaim2 += parseFloat($(this).val()) || 0;
            });

            $('.total-amount-after-discount[name="total_amount_after_discount[' + studentId + '][]"]').each(function () {
                totalAmountAfterDiscount += parseFloat($(this).val()) || 0;
            });

            $('#total_monthly_amount' + studentId).text(totalMonthlyAmount);
            $('#total_amount_of_month' + studentId).val(totalMonthlyAmount);
            $('#total_discount_percent' + studentId).text(totalDiscountPercent);
            $('#total_discount_rupees' + studentId).text(totalDiscountRupees);
            $('#total_claim1' + studentId).text(totalClaim1);
            $('#total_claim2' + studentId).text(totalClaim2);
            $('#total_amount_after_discount' + studentId).text(totalAmountAfterDiscount);
            $('#total_amount_of_discount' + studentId).val(totalAmountAfterDiscount);
        }

    
        $('.card').each(function () {
            let studentId = $(this).find('.total-monthly-amount').attr('name').match(/\[(.*?)\]/)[1];
            updateTotals(studentId);
        });

        $('.change-amount').on('input', function () {
            let studentId = $(this).closest('.card').find('.total-monthly-amount').attr('name').match(/\[(.*?)\]/)[1];
            updateTotals(studentId);
        });
    
        function calculateDiscountedAmount(row) {
            var studentId = row.find('.total-monthly-amount').attr('name').match(/\[(.*?)\]/)[1];
            var totalMonthlyAmount = parseFloat(row.find('.total-monthly-amount').val());
            var discountPercentage = parseFloat(row.find('.discount-percentage').val());
            var discountedAmount = totalMonthlyAmount - (totalMonthlyAmount * (discountPercentage / 100));

            var discountRupees = parseFloat(row.find('.discount-rupees').val()) || 0;
            var claim1 = parseFloat(row.find('.claim-1').val()) || 0;
            var claim2 = parseFloat(row.find('.claim-2').val()) || 0;
            var currentDeductedAmount = discountRupees + claim1 + claim2;
            var newTotalAmountAfterDiscount = discountedAmount - currentDeductedAmount;
            row.find('.total-amount-after-discount').val(newTotalAmountAfterDiscount.toFixed(0));
            updateTotals(studentId);
        }

        $('.discount-percentage').each(function () {
            calculateDiscountedAmount($(this).closest('tr'));
        });

        $('.discount-percentage').on('input', function () {
            calculateDiscountedAmount($(this).closest('tr'));
        });

        $('.total-monthly-amount').on('input', function () {
            calculateDiscountedAmount($(this).closest('tr'));
        });

        $('.discount-rupees, .claim-1, .claim-2').on('input', function () {
            calculateDiscountedAmount($(this).closest('tr'));
        });
   

        // function calculateTotalAmountAfterDiscount(row) {
        //     var studentId = row.find('.total-monthly-amount').attr('name').match(/\[(.*?)\]/)[1];
        //     var totalAmountAfterDiscount = parseFloat(row.find('.total-amount-after-discount').val()) || 0;
        //     var discountRupees = parseFloat(row.find('.discount-rupees').val()) || 0;
        //     var claim1 = parseFloat(row.find('.claim-1').val()) || 0;
        //     var claim2 = parseFloat(row.find('.claim-2').val()) || 0;
        //     var previousDeductedAmount = parseFloat(row.attr('data-previous-deducted-amount')) || 0;
        //     var currentDeductedAmount = discountRupees + claim1 + claim2;
        //     var newTotalAmountAfterDiscount = totalAmountAfterDiscount + previousDeductedAmount - currentDeductedAmount;
        //     row.attr('data-previous-deducted-amount', currentDeductedAmount);
        //     row.find('.total-amount-after-discount').val(newTotalAmountAfterDiscount.toFixed(0));
        //     updateTotals(studentId);
        // }

        // $('.discount-rupees, .claim-1, .claim-2').each(function () {
        //     calculateTotalAmountAfterDiscount($(this).closest('tr'));
        // });
        //
        // $('.discount-rupees, .claim-1, .claim-2').on('input', function () {
        //     calculateTotalAmountAfterDiscount($(this).closest('tr'));
        // });
    });

    $('.fee_factor').select2();

    document.getElementById('select-all-checkbox').addEventListener('change', function () {
        var checkboxes = document.getElementsByClassName('student-checkbox');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
        }
    });

    var studentCheckboxes = document.getElementsByClassName('student-checkbox');
    for (var i = 0; i < studentCheckboxes.length; i++) {
        studentCheckboxes[i].addEventListener('change', function () {
            if (!this.checked) {
                document.getElementById('select-all-checkbox').checked = false;
            }
        });
    }
</script>