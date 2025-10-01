<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Bill - {{ $billing->challan_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 10px;
        }
        
        .bill-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .bill-header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
        }
        
        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .school-address {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .bill-title {
            font-size: 16px;
            margin-top: 10px;
            font-weight: 600;
        }
        
        .bill-content {
            padding: 15px;
        }
        
        .bill-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 15px;
        }
        
        .info-section {
            flex: 1;
        }
        
        .info-section h3 {
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: #34495e;
            min-width: 120px;
        }
        
        .detail-value {
            color: #2c3e50;
            font-weight: 500;
        }
        
        .amount-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 16px;
        }
        
        .amount-row.total {
            border-top: 2px solid #2c3e50;
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 15px;
            padding-top: 15px;
        }
        
        .discount-section {
            background: #d4edda;
            border: 1px solid #28a745;
            border-radius: 6px;
            padding: 8px;
            margin: 8px 0;
        }
        
        .discount-title {
            color: #155724;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        
        .discount-item {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            border-bottom: 1px solid #c3e6cb;
        }
        
        .discount-item:last-child {
            border-bottom: none;
        }
        
        .discount-name {
            color: #155724;
            font-weight: 500;
        }
        
        .discount-value {
            color: #155724;
            font-weight: 600;
        }
        
        .transport-section {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 6px;
            padding: 8px;
            margin: 8px 0;
        }
        
        .transport-title {
            color: #0d47a1;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .transport-item {
            display: flex;
            justify-content: space-between;
            padding: 1px 0;
            border-bottom: 1px solid #bbdefb;
        }
        
        .transport-item:last-child {
            border-bottom: none;
        }
        
        .transport-name {
            color: #0d47a1;
            font-weight: 500;
        }
        
        .transport-value {
            color: #0d47a1;
            font-weight: 600;
        }
        
        .transport-total {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-top: 2px solid #2196f3;
            margin-top: 3px;
            font-weight: 600;
        }
        
        .transport-total-label {
            color: #0d47a1;
        }
        
        .transport-total-value {
            color: #0d47a1;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
        }
        
        .print-button:hover {
            background: #2980b9;
        }
        
        .footer {
            background: #34495e;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 12px;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .status-paid { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-overdue { background: #f8d7da; color: #721c24; }
        .status-generated { background: #cce5ff; color: #004085; }
        
        @media print {
            body {
                background: white;
                padding: 0;
                font-size: 12px;
            }
            
            .print-button {
                display: none;
            }
            
            .bill-container {
                box-shadow: none;
                border-radius: 0;
                margin: 0;
                max-width: 100%;
            }
            
            .bill-header {
                background: #2c3e50 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                padding: 10px;
            }
            
            .bill-content {
                padding: 10px;
            }
            
            .bill-info {
                gap: 10px;
                margin-bottom: 10px;
            }
            
            .discount-section, .transport-section {
                padding: 6px;
                margin: 6px 0;
            }
            
            .discount-title, .transport-title {
                font-size: 10px;
                margin-bottom: 3px;
            }
            
            .discount-item, .transport-item {
                padding: 1px 0;
            }
            
            .amount-row {
                padding: 2px 0;
            }
            
            .amount-section {
                padding: 10px;
            }
            
            .discount-section {
                padding: 10px;
                margin: 10px 0;
            }
        }
        
        @media (max-width: 768px) {
            .bill-info {
                flex-direction: column;
                gap: 20px;
            }
            
            .bill-content {
                padding: 20px;
            }
            
            .print-button {
                top: 15px;
                right: 15px;
                padding: 10px 15px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Print Bill</button>
    
    <div class="bill-container">
        <div class="bill-header">
            <div class="school-name">School Management System</div>
            <div class="school-address">123 School Street, City, Country</div>
            <div class="bill-title">Fee Bill</div>
        </div>

        <div class="bill-content">
            <div class="bill-info">
                <div class="info-section">
                    <h3>Bill Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Bill Number:</span>
                        <span class="detail-value">{{ $billing->challan_number ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Bill Date:</span>
                        <span class="detail-value">{{ $billing->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Due Date:</span>
                        <span class="detail-value">{{ $billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>

                <div class="info-section">
                    <h3>Student Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Student Name:</span>
                        <span class="detail-value">{{ $billing->student->fullname ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Class:</span>
                        <span class="detail-value">{{ $billing->student->AcademicClass->name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Session:</span>
                        <span class="detail-value">{{ $billing->academicSession->name ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Student ID:</span>
                        <span class="detail-value">{{ $billing->student->id ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="amount-section">
                <div class="amount-row">
                    <span>Total Amount:</span>
                    <span>Rs. {{ number_format($billing->total_amount, 2) }}</span>
                </div>
                
                @if($applicableDiscounts && $applicableDiscounts->count() > 0)
                    <div class="discount-section">
                        <div class="discount-title">Applied Discounts</div>
                        @foreach($applicableDiscounts as $discount)
                            <div class="discount-item">
                                <span class="discount-name">{{ $discount->category->name ?? 'General' }} ({{ ucfirst($discount->discount_type) }})</span>
                                <span class="discount-value">
                                    @if($discount->discount_type == 'percentage')
                                        {{ $discount->discount_value }}%
                                    @else
                                        Rs. {{ number_format($discount->discount_value, 2) }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($transportFees && $transportFees->count() > 0)
                    <div class="transport-section">
                        <div class="transport-title">Transport Fees</div>
                        @foreach($transportFees as $transport)
                            <div class="transport-item">
                                <span class="transport-name">{{ $transport->vehicle->vehicle_number ?? 'N/A' }} - {{ $transport->route->route_name ?? 'N/A' }}</span>
                                <span class="transport-value">Rs. {{ number_format($transport->monthly_charges, 2) }}</span>
                            </div>
                        @endforeach
                        <div class="transport-total">
                            <span class="transport-total-label">Total Transport Fee:</span>
                            <span class="transport-total-value">Rs. {{ number_format($totalTransportFee, 2) }}</span>
                        </div>
                    </div>
                @endif
                
                @php
                    $paidAmount = $billing->paid_amount ?? 0;
                    $finalAmount = $billing->getFinalAmount() + (isset($totalTransportFee) ? $totalTransportFee : 0);
                    $outstandingAmount = $finalAmount - $paidAmount;
                @endphp
                
                @if($paidAmount > 0)
                    <div class="amount-row">
                        <span>Paid Amount:</span>
                        <span style="color: #28a745; font-weight: bold;">Rs. {{ number_format($paidAmount, 2) }}</span>
                    </div>
                @endif
                
                <div class="amount-row total">
                    <span>Amount Due:</span>
                    <span>Rs. {{ number_format($outstandingAmount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>This is a computer generated bill. No signature required.</p>
            <p>Generated on: {{ now()->format('d M Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
