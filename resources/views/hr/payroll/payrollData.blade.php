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

            <input type="number" name="cash_in_hand[]" value="" class="cash-in-hand">
        </td>
        <td>
            <input type="number" name="cash_in_bank[]" value="" class="cash-in-bank">
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

        $(document).on('input', '.cash-in-hand', function() {
            var $row = $(this).closest('tr');
            var netSalary = parseFloat($row.find('.net_salary').text()) || 0;
            var cashInHand = parseFloat($(this).val()) || 0;

            if (cashInHand > netSalary) {
                toastr.warning("Value Exceeds!");
                $(this).val(netSalary);
                cashInHand = netSalary;
            }

            var cashInBank = netSalary - cashInHand;
            cashInBank = parseFloat(cashInBank.toFixed(2));
            $row.find('.cash-in-bank').val(cashInBank);
        });

        $(document).on('input', '.cash-in-bank', function() {
            var $row = $(this).closest('tr');
            var netSalary = parseFloat($row.find('.net_salary').text()) || 0;
            var cashInBank = parseFloat($(this).val()) || 0;

            if (cashInBank > netSalary) {
                toastr.warning("Value Exceeds!");
                $(this).val(netSalary);
                cashInBank = netSalary;
            }

            var cashInHand = netSalary - cashInBank;
            cashInHand = parseFloat(cashInHand.toFixed(2));
            $row.find('.cash-in-hand').val(cashInHand);
        });

    });
</script>
