<?php
namespace App\Http\Controllers\Inventory;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PurchaseOrder;
use App\Models\Accounts\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Exports\SupplierLedgerExport;
use App\Models\Accounts\VendorPayment;

class SupplierLedgerController extends Controller
{
    // Show Supplier Ledger Select Page
    public function index()
    {
        $suppliers = Supplier::all(); // get all suppliers
        return view('inventory.reports.index', compact('suppliers'));
    }

    // Show Specific Supplier Ledger
    public function showLedger($supplierId)
    {
        // Get supplier details
        $supplier = Supplier::findOrFail($supplierId);

        // Get all payments related to this supplier
        $vendorPayments = VendorPayment::where('vendor_id', $supplierId)
            ->with(['invoice', 'preparedByUser', 'approvedByUser']) // Load related data if necessary
            ->orderBy('payment_date', 'desc')
            ->get();

        // Get supplier ledger adjustments (if any)
        $adjustments = PurchaseOrder::where('supplier_id', $supplierId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get the purchases and payments
        $purchases = PurchaseOrder::with('supplier')->where('supplier_id', $supplierId)->get();
        $payments = VendorPayment::where('vendor_id', $supplierId)->get();

        // Optional: Calculate outstanding balance
        $totalPaid = $vendorPayments->sum('payment_amount');
        $totalOrdered = $purchases->sum('total_amount');
        $outstanding = $totalOrdered - $totalPaid;

        $payments = VendorPayment::where('vendor_id', $supplierId)
            ->with(['invoice', 'preparedByUser', 'approvedByUser'])
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('inventory.reports.detail', compact(
            'supplier',
            'vendorPayments',
            'adjustments',
            'purchases',
            'payments',
            'totalPaid',
            'totalOrdered',
            'outstanding',
            'supplierId',

        ));
    }

    
        public function exportSupplierLedgerPdf($supplierId)
        {
            // Find supplier or 404
            $supplier = Supplier::findOrFail($supplierId);
            // Get purchases and payments
            $purchases = PurchaseOrder::where('supplier_id', $supplierId)
            ->orderBy('created_at', 'asc')
                ->get();

            $payments = VendorPayment::where('vendor_id', $supplierId)
            ->orderBy('payment_date', 'asc')
                ->get();
                
            // Totals
            $totalOrdered = $purchases->sum('total_amount') ?: 0;
            $totalPaid = $payments->sum('payment_amount') ?: 0;
            $outstanding = $totalOrdered - $totalPaid;
            // dd($payments, $totalPaid, $totalOrdered, $outstanding);
            $pdf = Pdf::loadView('inventory.reports.supplier-ledger-pdf', [
                'supplier' => $supplier,
                'purchases' => $purchases,
                'payments' => $payments,
                'totalOrdered' => $totalOrdered,
                'totalPaid' => $totalPaid,
                'outstanding' => $outstanding,
            ]);
        return $pdf->download('supplier_ledger.pdf');
        }
        
        public function exportSupplierLedgerExcel($supplierId)
        {
            $supplier = Supplier::findOrFail($supplierId);
            
            $purchases = PurchaseOrder::where('supplier_id', $supplierId)
            ->orderBy('created_at', 'asc')
            ->get();
            
            $payments = VendorPayment::where('vendor_id', $supplierId)
            ->with('invoice')
            ->orderBy('payment_date', 'asc')
            ->get();
            
            $totalOrdered = $purchases->sum('total_amount') ?: 0;
            $totalPaid = $payments->sum('payment_amount') ?: 0;
            $outstanding = $totalOrdered - $totalPaid;
            
            
            return Excel::download(
                new SupplierLedgerExport($supplier, $purchases, $payments, $totalOrdered, $totalPaid, $outstanding),
                'supplier_ledger_' . $supplier->name . '_' . now()->format('Y-m-d') . '.xlsx'
            );
        }
        
}