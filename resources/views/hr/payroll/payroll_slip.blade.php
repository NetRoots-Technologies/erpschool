<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { border: 1px solid #000; padding: 20px; margin-bottom: 20px; }
        .logo { text-align: center; }
        .logo img { width: 200px; height: 45px; }
        .title { text-align: center; font-size: 22px; font-weight: bold; margin-bottom: 10px; }
        .date { float: right; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #000; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .print-button { text-align: center; margin-top: 20px; }
        .print-button button {
            padding: 10px 20px; background-color: #4CAF50;
            color: white; border: none; cursor: pointer;
            border-radius: 5px; font-size: 16px;
        }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

@php
    $company = \App\Models\Admin\Company::where('status', 1)->first();
    $logoUrl = $company ? asset($company->logo) : 'https://www.netrootstech.com/wp-content/uploads/2022/08/Netroots-logo-tm-transparent.png';
@endphp

<div class="container">
    <div class="logo">
        <img src="{{ $logoUrl }}" alt="Company Logo">
    </div>
    <div class="title">Salary Slip</div>
    <div class="date">Date: {{ date('M d, Y') }}</div>

    @php
        $fundValues = $SalarySlip->fund_values;
        $totalDeductions = ($fundValues['provident_fund'] ?? 0) + ($fundValues['eobi_provident_fund'] ?? 0) + ($SalarySlip->advance ?? 0);
    @endphp

    <table>
        <tbody>
            <tr>
                <th>Employee Name:</th>
                <td>{{ $employee->name ?? 'N/A' }}</td>
                <th>Designation:</th>
                <td>{{ $SalarySlip->employee->designation->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Department:</th>
                <td>{{ $SalarySlip->employee->department->name ?? 'N/A' }}</td>
                <th>Date of Joining:</th>
                <td>{{ $SalarySlip->employee->start_date ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Month-Year:</th>
                <td>{{ $SalarySlip->generated_month ?? 'N/A' }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="2">Earnings</th>
                <th colspan="2">Deductions</th>
            </tr>
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basic Salary</td>
                <td>{{ $employee->salary ?? 'N/A' }}</td>
                <td>Advance Installment</td>
                <td>{{ $SalarySlip->advance ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Total Present</td>
                <td>{{ $SalarySlip->total_present }}</td>
                <td>EOBI</td>
                <td>{{ $fundValues['eobi_provident_fund'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Total Absence</td>
                <td>{{ $SalarySlip->total_absent }}</td>
                <td>Provident Fund</td>
                <td>{{ $fundValues['provident_fund'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><b>Total Salary</b></td>
                <td>{{ $SalarySlip->total_salary ?? 'N/A' }}</td>
                <td>Total Late</td>
                <td>{{ $SalarySlip->total_late ?? 0 }}</td>
            </tr>
            @if(isset($firstChildFee))
            <tr>
                <td colspan="2"><b>Child Fee Waiver (1st Child)</b></td>
                <td colspan="2"><b>-{{ number_format($firstChildFee, 2) }} PKR</b></td>
            </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td><b>Medical Welfare Amount</b></td>
                <td>{{ $SalarySlip->medicalAllowance ?? 'N/A' }}</td>
                <td><b>Total Deductions</b></td>
                <td>{{ number_format($totalDeductions, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <table>
        <tbody>
            <tr>
                <td colspan="4"><b>Net Salary</b></td>
                <td><b>{{ $SalarySlip->net_salary ?? 'N/A' }}</b></td>
            </tr>
            <tr>
                <td colspan="2">Cash Handed Over</td>
                <td>{{ $SalarySlip->cash_in_hand }}</td>
                <td>Bank Transfer</td>
                <td>{{ $SalarySlip->cash_in_bank }}</td>
            </tr>
        </tbody>
    </table>

    <div class="print-button no-print">
        <button onclick="window.print()">Print</button>
    </div>
</div>

</body>
</html>
