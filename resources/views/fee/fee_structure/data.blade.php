    @php
        $totalMonthlyAmount = 0;
        $totalDiscount = 0;
        $totalDiscountRupees = 0;
        $totalClaim1 = 0;
        $totalClaim2 = 0;
    @endphp
    @foreach ($feeHeads as $feeHead)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <input type="hidden" name="fee_head_id[]" value="{{ $feeHead->id }}">
                {{ $feeHead->fee_head }}
            </td>
            <input type="hidden" name="fee_structure_id[]" value="{{ $feeHead->feeStructureVal->id ?? '' }}">
            <td><input type="number" name="monthly_amount[]" class="form-control total-monthly-amount" value="{{ $feeHead->feeStructureVal->monthly_amount ?? '' }}"></td>
            <td>
                <div class="form-group">
                    <input type="number" name="discount[]" min="0" max="100" value="{{  $feeHead->feeStructureVal->discount_percent   ?? $discountPercent }}" class="form-control discount-percentage change-amount">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" name="discount_rupees[]" value="{{ $feeHead->feeStructureVal->discount_rupees ?? 0 }}" class="form-control discount-rupees change-amount">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" name="claim_1[]" value="{{ $feeHead->feeStructureVal->claim1 ?? 0 }}" class="form-control claim-1 change-amount">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" name="claim_2[]" value="{{ $feeHead->feeStructureVal->claim2 ?? 0 }}" class="form-control claim-2 change-amount">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" name="total_amount_after_discount[]" value="{{ $feeHead->feeStructureVal->total_amount_after_discount ?? 0 }}" class="form-control total-amount-after-discount" readonly>
                </div>
            </td>
            @php
                $totalMonthlyAmount += $feeHead->feeStructureVal->total_amount_after_discount ?? 0;
                $totalDiscount += (int) $discountPercent ?? 0;
                $totalDiscountRupees += $feeHead->feeStructureVal->discount_rupees ?? 0;
                $totalClaim1 += $feeHead->feeStructureVal->claim1 ?? 0;
                $totalClaim2 += $feeHead->feeStructureVal->claim2  ?? 0;
            @endphp
        </tr>
    @endforeach

    <tr>
        <td colspan="2">Total Amount</td>
        <td id="totalMonthlyAmount">{{ $totalMonthlyAmount }}</td>
        <td ></td>
        <td id="totalDiscountRupees">{{ $totalDiscountRupees }}</td>
        <td id="claim1total">{{ $totalClaim1 }}</td>
        <td id="claim2total">{{ $totalClaim1 }}</td>
        <td id="total_amount_after_discount">{{ $totalMonthlyAmount }}</td>
    </tr>

    <input type="hidden" name="total_monthly_amount" id="hiddenTotalMonthlyAmount" value="{{ $totalMonthlyAmount }}">
    <input type="hidden" name="total_monthly_amount" id="hiddenTotalDiscount" value="{{ $totalDiscountRupees }}">
    <input type="hidden" name="total_discount" id="hiddenTotalDiscount" value="{{ $totalDiscount }}">
    <input type="hidden" name="total_discount_amount" id="hiddenTotalDiscountMonthly" value="{{ $totalMonthlyAmount }}">
    <input type="hidden" name="total_claim1" id="hiddenTotalClaim1" value="{{ $totalClaim1 }}">
    <input type="hidden" name="total_claim2" id="hiddenTotalClaim2" value="{{ $totalClaim2 }}">


    @include('fee.fee_js.js')

