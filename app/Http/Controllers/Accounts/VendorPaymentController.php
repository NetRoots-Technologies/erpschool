<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Accounts\VendorPayment;
use App\Models\Vendor;
use App\Models\PurchaseInvoice;
use App\Models\Account; // optional - bank/cash accounts
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Supplier;

class VendorPaymentController extends Controller
{
    /**
     * List payments
     */
    public function index(Request $request)
    {
        $payments = VendorPayment::with(['vendor', 'invoice', 'preparedByUser', 'approvedByUser'])
                    ->orderBy('created_at','desc')
                    ->paginate(25);
                    // dd($payments);

        return view('accounts.vendor_payments.index');
    }

    /**
     * Show create form
     */
    public function create()
    {
        $vendors = Supplier::orderBy('name')->get();
        // $accounts = Account::orderBy('name')->get(); // optional - if you have accounts table
        $users = User::orderBy('name')->get();

        return view('accounts.vendor_payments.create', compact('vendors','users'));
    }

    /**
     * Store a new payment
     */
    public function store(Request $request)
    {
        $rules = [
            'payment_date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'invoice_id' => 'nullable|exists:purchase_invoices,id',
            'invoice_amount' => 'nullable|numeric|min:0',
            'pending_amount' => 'nullable|numeric|min:0',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_mode' => ['required', Rule::in(['Cash','Cheque','Bank Transfer','Other'])],
            'account_id' => 'nullable|exists:accounts,id',
            'cheque_no' => 'nullable|string|required_if:payment_mode,Cheque',
            'cheque_date' => 'nullable|date|required_if:payment_mode,Cheque',
            'remarks' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'prepared_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // If invoice_id provided, verify it's for the vendor and has pending balance
            $invoice = null;
            if (!empty($validated['invoice_id'])) {
                $invoice = PurchaseInvoice::lockForUpdate()->findOrFail($validated['invoice_id']);

                if ($invoice->vendor_id != $validated['vendor_id']) {
                    return back()->withInput()->withErrors(['invoice_id' => 'Selected invoice does not belong to chosen vendor.']);
                }

                $pending = round($invoice->total_amount - $invoice->paid_amount, 2);
                // if pending_amount sent, validate
                if (isset($validated['pending_amount']) && round($validated['pending_amount'],2) !== $pending) {
                    // not fatal: just correct it
                    $validated['pending_amount'] = $pending;
                }

                if ($validated['payment_amount'] > $pending) {
                    return back()->withInput()->withErrors(['payment_amount' => 'Payment amount cannot be greater than pending amount of selected invoice ('.$pending.').']);
                }
            }

            // create vendor payment
            $vp = new VendorPayment();
            $vp->voucher_no = $this->generateVoucherNo();
            $vp->payment_date = $validated['payment_date'];
            $vp->vendor_id = $validated['vendor_id'];
            $vp->invoice_id = $validated['invoice_id'] ?? null;
            $vp->invoice_amount = $validated['invoice_amount'] ?? ($invoice ? $invoice->total_amount : null);
            $vp->pending_amount = $validated['pending_amount'] ?? ($invoice ? round($invoice->total_amount - $invoice->paid_amount,2) : null);
            $vp->payment_amount = $validated['payment_amount'];
            $vp->payment_mode = $validated['payment_mode'];
            $vp->account_id = $validated['account_id'] ?? null;
            $vp->cheque_no = $validated['cheque_no'] ?? null;
            $vp->cheque_date = $validated['cheque_date'] ?? null;
            $vp->remarks = $validated['remarks'] ?? null;
            $vp->prepared_by = $validated['prepared_by'] ?? auth()->id();
            $vp->approved_by = $validated['approved_by'] ?? null;

            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('vendor_payments', 'public');
                $vp->attachment = $path;
            }

            $vp->save();

            // If linked to invoice -> update invoice paid_amount and status
            if ($invoice) {
                $apply = round($validated['payment_amount'],2);
                $invoice->paid_amount = round($invoice->paid_amount + $apply,2);

                if ($invoice->paid_amount >= $invoice->total_amount - 0.0001) {
                    $invoice->status = 'paid';
                } else {
                    $invoice->status = 'partially_paid';
                }

                $invoice->save();
            }

            DB::commit();

            return redirect()->route('payables.vendorPayments.index')
                             ->with('success', 'Payment saved successfully. Voucher: '.$vp->voucher_no);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('VendorPayment store error: '.$e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to save payment. '.$e->getMessage()]);
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $vp = VendorPayment::findOrFail($id);
        $vendors = Vendor::orderBy('name')->get();
        $accounts = Account::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('accounts.vendor_payments.edit', compact('vp','vendors','accounts','users'));
    }

    /**
     * Update payment
     *
     * Note: This implementation attempts to reverse previous invoice impact (if existed),
     * then apply new changes. For complex audit requirements, prefer creating reversal transactions instead.
     */
    public function update(Request $request, $id)
    {
        $vp = VendorPayment::findOrFail($id);

        $rules = [
            'payment_date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'invoice_id' => 'nullable|exists:purchase_invoices,id',
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_mode' => ['required', Rule::in(['Cash','Cheque','Bank Transfer','Other'])],
            'account_id' => 'nullable|exists:accounts,id',
            'cheque_no' => 'nullable|string|required_if:payment_mode,Cheque',
            'cheque_date' => 'nullable|date|required_if:payment_mode,Cheque',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'prepared_by' => 'nullable|exists:users,id',
            'approved_by' => 'nullable|exists:users,id',
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Reverse previous invoice effect if any
            if ($vp->invoice_id) {
                $oldInvoice = PurchaseInvoice::lockForUpdate()->find($vp->invoice_id);
                if ($oldInvoice) {
                    $oldInvoice->paid_amount = round($oldInvoice->paid_amount - $vp->payment_amount,2);
                    if ($oldInvoice->paid_amount <= 0) {
                        $oldInvoice->paid_amount = 0;
                        $oldInvoice->status = 'pending';
                    } elseif ($oldInvoice->paid_amount < $oldInvoice->total_amount) {
                        $oldInvoice->status = 'partially_paid';
                    }
                    $oldInvoice->save();
                }
            }

            // If new invoice selected, validate vendor and pending
            $newInvoice = null;
            if (!empty($validated['invoice_id'])) {
                $newInvoice = PurchaseInvoice::lockForUpdate()->findOrFail($validated['invoice_id']);
                if ($newInvoice->vendor_id != $validated['vendor_id']) {
                    return back()->withInput()->withErrors(['invoice_id' => 'Selected invoice does not belong to chosen vendor.']);
                }
                $pending = round($newInvoice->total_amount - $newInvoice->paid_amount, 2);
                if ($validated['payment_amount'] > $pending) {
                    return back()->withInput()->withErrors(['payment_amount' => 'Payment amount cannot be greater than pending amount ('.$pending.').']);
                }
            }

            // update vendor payment
            $vp->payment_date = $validated['payment_date'];
            $vp->vendor_id = $validated['vendor_id'];
            $vp->invoice_id = $validated['invoice_id'] ?? null;
            $vp->invoice_amount = $validated['invoice_id'] ? $newInvoice->total_amount : null;
            $vp->pending_amount = $validated['invoice_id'] ? round($newInvoice->total_amount - $newInvoice->paid_amount,2) : null;
            $vp->payment_amount = $validated['payment_amount'];
            $vp->payment_mode = $validated['payment_mode'];
            $vp->account_id = $validated['account_id'] ?? null;
            $vp->cheque_no = $validated['cheque_no'] ?? null;
            $vp->cheque_date = $validated['cheque_date'] ?? null;
            $vp->remarks = $validated['remarks'] ?? $vp->remarks;
            $vp->prepared_by = $validated['prepared_by'] ?? $vp->prepared_by;
            $vp->approved_by = $validated['approved_by'] ?? $vp->approved_by;

            if ($request->hasFile('attachment')) {
                // delete old file
                if ($vp->attachment && Storage::disk('public')->exists($vp->attachment)) {
                    Storage::disk('public')->delete($vp->attachment);
                }
                $path = $request->file('attachment')->store('vendor_payments', 'public');
                $vp->attachment = $path;
            }

            $vp->save();

            // Apply to new invoice if exists
            if ($newInvoice) {
                $apply = round($validated['payment_amount'],2);
                $newInvoice->paid_amount = round($newInvoice->paid_amount + $apply,2);
                if ($newInvoice->paid_amount >= $newInvoice->total_amount - 0.0001) {
                    $newInvoice->status = 'paid';
                } else {
                    $newInvoice->status = 'partially_paid';
                }
                $newInvoice->save();
            }

            DB::commit();

            return redirect()->route('payables.vendorPayments.index')->with('success','Payment updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('VendorPayment update error: '.$e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to update payment. '.$e->getMessage()]);
        }
    }

    /**
     * Delete payment (and reverse invoice impact if linked)
     */
    public function destroy($id)
    {
        $vp = VendorPayment::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($vp->invoice_id) {
                $invoice = PurchaseInvoice::lockForUpdate()->find($vp->invoice_id);
                if ($invoice) {
                    $invoice->paid_amount = round($invoice->paid_amount - $vp->payment_amount,2);
                    if ($invoice->paid_amount <= 0) {
                        $invoice->paid_amount = 0;
                        $invoice->status = 'pending';
                    } elseif ($invoice->paid_amount < $invoice->total_amount) {
                        $invoice->status = 'partially_paid';
                    }
                    $invoice->save();
                }
            }

            // delete attachment file
            if ($vp->attachment && Storage::disk('public')->exists($vp->attachment)) {
                Storage::disk('public')->delete($vp->attachment);
            }

            $vp->delete();

            DB::commit();
            return redirect()->route('payables.vendorPayments.index')->with('success','Payment deleted.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('VendorPayment delete error: '.$e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete payment. '.$e->getMessage()]);
        }
    }

    /**
     * Print voucher (simple blade)
     */
    public function print($id)
    {
        $vp = VendorPayment::with(['vendor','invoice','preparedByUser','approvedByUser'])->findOrFail($id);
        return view('accounts.vendor_payments.print', compact('vp'));
    }

    /**
     * Simple voucher generator: VP-YYYYMM-0001
     */
    protected function generateVoucherNo()
    {
        $prefix = 'VP-'.date('Ym').'-';
        $last = VendorPayment::where('voucher_no','like', $prefix.'%')->orderBy('id','desc')->first();
        if (!$last) {
            $num = 1;
        } else {
            // extract numeric suffix
            $parts = explode('-', $last->voucher_no);
            $suf = end($parts);
            $num = intval($suf) + 1;
        }
        return $prefix.str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Fetch pending invoices for a vendor (AJAX)
     */

    public function getPendingInvoices($vendorId)
    {
        $invoices = PurchaseOrder::where('supplier_id', $vendorId)->whereIn('delivery_status', ['COMPLETED', 'PARTIALLY'])->get();
        // dd($invoices);
        return response()->json($invoices);
    }
}
