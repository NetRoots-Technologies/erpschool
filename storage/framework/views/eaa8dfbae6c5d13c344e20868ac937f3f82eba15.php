<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Bill - <?php echo e($billing->challan_number); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .bill-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        }
        .bill-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        .bill-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .bill-details {
            flex: 1;
        }
        .student-details {
            flex: 1;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
            color: #333;
        }
        .detail-value {
            color: #666;
        }
        .amount-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .amount-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 5px 0;
        }
        .total-amount {
            border-top: 2px solid #333;
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-button:hover {
            background-color: #0056b3;
        }
        @media  print {
            .print-button {
                display: none;
            }
            body {
                background-color: white;
            }
            .bill-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Print Bill</button>
    
    <div class="bill-container">
        <div class="header">
            <div class="school-name">School Management System</div>
            <div class="school-address">123 School Street, City, Country</div>
        </div>

        <div class="bill-title">Fee Bill</div>

        <div class="bill-info">
            <div class="bill-details">
                <div class="detail-row">
                    <span class="detail-label">Bill Number:</span>
                    <span class="detail-value"><?php echo e($billing->challan_number ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Bill Date:</span>
                    <span class="detail-value"><?php echo e($billing->created_at->format('d M Y')); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Due Date:</span>
                    <span class="detail-value"><?php echo e($billing->due_date ? \Carbon\Carbon::parse($billing->due_date)->format('d M Y') : 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        <span style="color: <?php echo e($billing->status == 'paid' ? 'green' : ($billing->status == 'pending' ? 'orange' : 'red')); ?>">
                            <?php echo e(ucfirst($billing->status)); ?>

                        </span>
                    </span>
                </div>
            </div>

            <div class="student-details">
                <div class="detail-row">
                    <span class="detail-label">Student Name:</span>
                    <span class="detail-value"><?php echo e($billing->student->fullname ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Class:</span>
                    <span class="detail-value"><?php echo e($billing->student->AcademicClass->name ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Session:</span>
                    <span class="detail-value"><?php echo e($billing->academicSession->name ?? 'N/A'); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Student ID:</span>
                    <span class="detail-value"><?php echo e($billing->student->id ?? 'N/A'); ?></span>
                </div>
            </div>
        </div>

        <div class="amount-section">
            <div class="amount-row">
                <span>Total Amount:</span>
                <span>Rs. <?php echo e(number_format($billing->total_amount, 2)); ?></span>
            </div>
            <div class="amount-row total-amount">
                <span>Amount Due:</span>
                <span>Rs. <?php echo e(number_format($billing->total_amount, 2)); ?></span>
            </div>
        </div>

        <div class="footer">
            <p>This is a computer generated bill. No signature required.</p>
            <p>Generated on: <?php echo e(now()->format('d M Y H:i:s')); ?></p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\erpschool\resources\views/admin/fee-management/billing/print.blade.php ENDPATH**/ ?>