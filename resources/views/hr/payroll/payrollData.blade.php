<?php
$index = 1;
$presentCount = 0;
$absentCount = 0;
$LeaveCount = 0;
$lateCount = 0;
$offDayCount = 0;

?>

@foreach ($employees_data as $key => $employeeData)
    <tr>
        <input type="hidden" name="employee_id[]" value="{!! $key !!}">
        <td>{{ $index++ }}</td>
        <td>{!! $employeeData['name'] ?? 'N/A' !!}
            <input type="hidden" name="employee_name[]" value="{!! $employeeData['name'] == null ? 'N/A' : $employeeData['name'] !!}">
        </td>
        <td>
            {!! $employeeData['employeeSalary'] == null ? 0 : $employeeData['employeeSalary'] !!}
            <input type="hidden" name="employee_salary[]" value="{!! $employeeData['employeeSalary'] == null ? 0 : $employeeData['employeeSalary'] !!}">
        </td>


        <td>
            {!! $employeeData['committedTime'] == null ? 0 : floatval(number_format($employeeData['committedTime'] / 60, 2)) !!}
            <input type="hidden" name="committedTime[]" value="{!! $employeeData['committedTime'] == null ? 0 : floatval(number_format($employeeData['committedTime'] / 60, 2)) !!}">
        </td>

        @php
            if ($employeeData['totalWorked'] != null) {
                $totalWorkedMinutes = $employeeData['totalWorked'];
                $totalWorkedHours = floatval(number_format($totalWorkedMinutes / 60, 2));
            } else {
                $totalWorkedHours = 0;
            }
        @endphp
        <td>
            {!! $totalWorkedHours !!}
            <input type="hidden" name="total_worked[]" value="{!! $totalWorkedHours !!}">
        </td>

        <td>
            {!! $employeeData['calculatedSalary'] == null ? 0 : $employeeData['calculatedSalary'] !!}
            <input type="hidden" name="calculated_salary[]" value="{!! $employeeData['calculatedSalary'] == null ? 0 : $employeeData['calculatedSalary'] !!}">
        </td>

        <td>
            <input type="number" name="advanceInstallment[]" value="{!! $employeeData['advanceInstallment'] == null ? 0 : $employeeData['advanceInstallment'] !!}">
        </td>

        @foreach ($employeeData['sideValues'] as $key1 => $value)
            <td>
                {!! $value == null ? 0 : $value !!}
                <input type="hidden" name="side_values[]" value="{!! $value == null ? 0 : $value !!}">
            </td>
        @endforeach

        <td>
            {!! $employeeData['totalValues'] == null ? 0 : $employeeData['totalValues'] !!}
        </td>

        <td>
            {!! $employeeData['medicalAllowance'] == null ? 0 : $employeeData['medicalAllowance'] !!}
        </td>

        <td>
            {!! $employeeData['lateJoin'] == null ? 0 : $employeeData['lateJoin'] !!}
        </td>

        <td>
            {!! $employeeData['Tax Salary'] == null ? 0 : $employeeData['Tax Salary'] !!}
        </td>

        <td class="net_salary">
            {!! $employeeData['totalSalary'] == null ? 0 : $employeeData['totalSalary'] !!}
        </td>

        <td>
            <input type="hidden" name="total_values[]" value="{!! $employeeData['totalValues'] == null ? 0 : $employeeData['totalValues'] !!}">

            <input type="hidden" name="medicalAllowance[]" value="{!! $employeeData['medicalAllowance'] == null ? 0 : $employeeData['medicalAllowance'] !!}">

            <input type="hidden" name="total_salary[]" value="{!! $employeeData['totalSalary'] == null ? 0 : $employeeData['totalSalary'] !!}">

            <div class="payment-method-wrapper" style="display: flex; align-items: center; gap: 8px;">
                <select name="payment_method[]" class="form-select form-select-sm payment-method-select" style="min-width: 110px; font-size: 13px; padding: 6px 10px;">
                    <option value="cash">Cash</option>
                    <option value="bank" selected>Bank</option>
                </select>
                <input type="number" 
                       name="payment_amount[]" 
                       value="{!! $employeeData['totalSalary'] == null ? 0 : $employeeData['totalSalary'] !!}" 
                       class="form-control form-control-sm payment-amount" 
                       style="flex: 1; min-width: 120px; font-size: 13px; padding: 6px 10px; text-align: right;" 
                       placeholder="0.00"
                       step="0.01"
                       min="0">
            </div>
            <input type="hidden" name="cash_in_hand[]" class="cash-in-hand" value="0">
            <input type="hidden" name="cash_in_bank[]" class="cash-in-bank" value="{!! $employeeData['totalSalary'] == null ? 0 : $employeeData['totalSalary'] !!}">
        </td>
    </tr>


    @foreach ($employeeData['attendance'] as $key => $attendance)
        @if ($attendance['present'] == true)
            {{ $presentCount++ }};

            <input type="hidden" name="total_present" value="{{ $presentCount ?? 0 }}">
        @else
            <input type="hidden" name="total_present" value="{{ 0 }}">
        @endif

        @if ($attendance['absent'] == true)
            {{ $absentCount++ }};
            <input type="hidden" name="total_absent" value="{{ $absentCount ?? 0 }}">
        @else
            <input type="hidden" name="total_absent" value="{{ 0 }}">
        @endif

        @if ($attendance['leave'] == true)
            @php
                $LeaveCount++;
            @endphp
            <input type="hidden" name="total_absent" value="{{ $LeaveCount ?? 0 }}">
        @endif
        @if ($attendance['late'] == true)
            @php
                $lateCount++;
            @endphp
            <input type="hidden" name="total_late" value="{{ $lateCount ?? 0 }}">
        @endif
    @endforeach
@endforeach

<script>
    $(document).ready(function() {

        const employeesData = @json($employees_data);
        localStorage.setItem("employeesData", JSON.stringify(employeesData));

        // Initialize default values for payment method (Bank) - set cash_in_bank = net_salary by default
        $('.payment-method-select').each(function() {
            var $row = $(this).closest('tr');
            var netSalary = parseFloat($row.find('.net_salary').text().replace(/,/g, '')) || 0;
            var paymentMethod = $(this).val();
            
            // Format number with 2 decimal places
            netSalary = parseFloat(netSalary.toFixed(2));
            
            // Set default amount to net salary when bank is selected
            if (paymentMethod === 'bank' && netSalary > 0) {
                $row.find('.payment-amount').val(netSalary);
                $row.find('.cash-in-hand').val(0);
                $row.find('.cash-in-bank').val(netSalary);
            } else if (paymentMethod === 'cash' && netSalary > 0) {
                $row.find('.payment-amount').val(netSalary);
                $row.find('.cash-in-hand').val(netSalary);
                $row.find('.cash-in-bank').val(0);
            }
        });

        // Handle payment method and amount
        $(document).on('input', '.payment-amount', function() {
            var $row = $(this).closest('tr');
            var netSalaryText = $row.find('.net_salary').text().replace(/,/g, '');
            var netSalary = parseFloat(netSalaryText) || 0;
            var paymentAmount = parseFloat($(this).val()) || 0;
            var paymentMethod = $row.find('.payment-method-select').val();

            // Format payment amount
            paymentAmount = parseFloat(paymentAmount.toFixed(2));

            if (paymentAmount > netSalary) {
                toastr.warning("Value Exceeds Net Salary!");
                $(this).val(parseFloat(netSalary.toFixed(2)));
                paymentAmount = parseFloat(netSalary.toFixed(2));
            }

            // Update hidden fields based on payment method
            if (paymentMethod === 'cash') {
                $row.find('.cash-in-hand').val(paymentAmount);
                $row.find('.cash-in-bank').val(0);
            } else {
                $row.find('.cash-in-hand').val(0);
                $row.find('.cash-in-bank').val(paymentAmount);
            }
        });

        $(document).on('change', '.payment-method-select', function() {
            var $row = $(this).closest('tr');
            var paymentAmount = parseFloat($row.find('.payment-amount').val()) || 0;
            var paymentMethod = $(this).val();
            var netSalaryText = $row.find('.net_salary').text().replace(/,/g, '');
            var netSalary = parseFloat(netSalaryText) || 0;

            // If no amount is set or amount is 0, set it to net salary
            if (paymentAmount <= 0 && netSalary > 0) {
                paymentAmount = parseFloat(netSalary.toFixed(2));
                $row.find('.payment-amount').val(paymentAmount);
            }

            // Format payment amount
            paymentAmount = parseFloat(paymentAmount.toFixed(2));

            // Update hidden fields based on payment method
            if (paymentMethod === 'cash') {
                $row.find('.cash-in-hand').val(paymentAmount);
                $row.find('.cash-in-bank').val(0);
            } else {
                $row.find('.cash-in-hand').val(0);
                $row.find('.cash-in-bank').val(paymentAmount);
            }
        });

    });
</script>
