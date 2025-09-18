<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Challan - {{ $voucher->voucher_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .challan-container {
            background-color: white;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            border: 2px solid #333;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .school-address {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .challan-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-top: 15px;
        }
        
        .voucher-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .voucher-left, .voucher-right {
            width: 48%;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #333;
        }
        
        .info-value {
            flex: 1;
            border-bottom: 1px dotted #333;
            padding-bottom: 2px;
        }
        
        .fee-details {
            margin: 30px 0;
        }
        
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .fee-table th,
        .fee-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: left;
        }
        
        .fee-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .fee-table .amount-col {
            text-align: right;
            width: 120px;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        
        .payment-info {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #333;
            background-color: #f9f9f9;
        }
        
        .payment-title {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .bank-details {
            display: flex;
            justify-content: space-between;
        }
        
        .bank-left, .bank-right {
            width: 48%;
        }
        
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: end;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 12px;
        }
        
        .due-date-notice {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-issued { background-color: #007bff; color: white; }
        .status-paid { background-color: #28a745; color: white; }
        .status-expired { background-color: #dc3545; color: white; }
        .status-cancelled { background-color: #6c757d; color: white; }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            
            .challan-container {
                box-shadow: none;
                border: 2px solid #333;
                margin: 0;
                padding: 20px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="challan-container">
        <!-- Header -->
        <div class="header">
            <div class="school-name">{{ $voucher->company->name ?? 'School Name' }}</div>
            <div class="school-address">
                {{ $voucher->company->address ?? 'School Address' }}<br>
                Phone: {{ $voucher->company->phone ?? 'Phone Number' }} | Email: {{ $voucher->company->email ?? 'Email' }}
            </div>
            <div class="challan-title">FEE CHALLAN</div>
        </div>

        <!-- Voucher Information -->
        <div class="voucher-info">
            <div class="voucher-left">
                <div class="info-row">
                    <span class="info-label">Challan No:</span>
                    <span class="info-value">{{ $voucher->voucher_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Student Name:</span>
                    <span class="info-value">{{ $voucher->student->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Roll Number:</span>
                    <span class="info-value">{{ $voucher->student->roll_no ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Class:</span>
                    <span class="info-value">{{ $voucher->student->class ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="voucher-right">
                <div class="info-row">
                    <span class="info-label">Issue Date:</span>
                    <span class="info-value">{{ $voucher->issue_date->format('d-M-Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Due Date:</span>
                    <span class="info-value">{{ $voucher->due_date->format('d-M-Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $voucher->status }}">{{ ucfirst($voucher->status) }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Branch:</span>
                    <span class="info-value">{{ $voucher->branch->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Fee Details -->
        <div class="fee-details">
            <table class="fee-table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Fee Head</th>
                        <th>Description</th>
                        <th class="amount-col">Amount (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @if($voucher->feeCollection && $voucher->feeCollection->feeCollectionDetails)
                        @foreach($voucher->feeCollection->feeCollectionDetails as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->feeHead->name ?? 'N/A' }}</td>
                            <td>{{ $detail->description ?? $detail->feeHead->description ?? '-' }}</td>
                            <td class="amount-col">{{ number_format($detail->amount, 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>1</td>
                            <td>Fee Payment</td>
                            <td>Total Fee Amount</td>
                            <td class="amount-col">{{ number_format($voucher->total_amount, 2) }}</td>
                        </tr>
                    @endif
                    
                    @if($voucher->discount_amount > 0)
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">Discount:</td>
                        <td class="amount-col" style="color: red;">-{{ number_format($voucher->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right; font-weight: bold;">Total Amount:</td>
                        <td class="amount-col">{{ number_format($voucher->net_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Due Date Notice -->
        @if($voucher->due_date->isFuture())
        <div class="due-date-notice">
            <strong>Notice:</strong> Please pay this fee before {{ $voucher->due_date->format('d-M-Y') }} to avoid late fee charges.
        </div>
        @elseif($voucher->due_date->isPast() && $voucher->status !== 'paid')
        <div class="due-date-notice" style="background-color: #f8d7da; border-color: #f5c6cb;">
            <strong>Overdue Notice:</strong> This fee was due on {{ $voucher->due_date->format('d-M-Y') }}. Late fee charges may apply.
        </div>
        @endif

        <!-- Payment Information -->
        <div class="payment-info">
            <div class="payment-title">Payment Information</div>
            <div class="bank-details">
                <div class="bank-left">
                    <div class="info-row">
                        <span class="info-label">Bank Name:</span>
                        <span class="info-value">{{ $voucher->company->bank_name ?? 'Bank Name' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Account Title:</span>
                        <span class="info-value">{{ $voucher->company->account_title ?? 'Account Title' }}</span>
                    </div>
                </div>
                <div class="bank-right">
                    <div class="info-row">
                        <span class="info-label">Account No:</span>
                        <span class="info-value">{{ $voucher->company->account_number ?? 'Account Number' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Branch Code:</span>
                        <span class="info-value">{{ $voucher->company->branch_code ?? 'Branch Code' }}</span>
                    </div>
                </div>
            </div>
            <p style="margin-top: 15px; font-size: 12px; color: #666;">
                Please mention challan number ({{ $voucher->voucher_number }}) and student name in payment reference.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="signature-box">
                <div class="signature-line">Student Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Parent Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Cashier Signature</div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Print Challan
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; background-color: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                Close
            </button>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>