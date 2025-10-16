<?php

namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Fleet\Driver;
use App\Models\Fleet\Expense;
use App\Models\Fleet\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Fleet-expense-list')) {
            return abort(503);
        }
        $expenses = Expense::with(['vehicle', 'driver', 'company', 'branch'])->paginate(10);
        return view('fleet.expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Fleet-expense-create')) {
            return abort(503);
        }
        return view('fleet.expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Fleet-expense-create')) {
            return abort(503);
        }
        $request->validate([
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'driver_id' => 'nullable|exists:fleet_drivers,id',
            'expense_type' => 'required|in:fuel,maintenance,toll,parking,repair,other',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'receipt_number' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $expense = Expense::create($request->all());

        // ✅ ACCOUNTING INTEGRATION
        try {
            $vehicle = Vehicle::find($request->vehicle_id);
            $integrationController = new \App\Http\Controllers\Accounts\IntegrationController();
            $integrationRequest = new \Illuminate\Http\Request([
                'vehicle_id' => $expense->vehicle_id,
                'expense_amount' => $expense->amount,
                'expense_date' => $expense->expense_date,
                'expense_type' => $expense->expense_type,
                'reference' => 'EXP-' . $expense->id . ' - ' . ($vehicle->registration_number ?? 'Vehicle'),
            ]);
            $integrationController->recordFleetExpense($integrationRequest);
        } catch (\Exception $e) {
            \Log::error('Fleet expense accounting failed: ' . $e->getMessage());
        }

        return redirect()->route('fleet.expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fleet\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {

        if (!Gate::allows('Fleet-expense-view')) {
            return abort(503);
        }
        $expense->load(['vehicle', 'driver', 'company', 'branch']);
        return view('fleet.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fleet\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        if (!Gate::allows('Fleet-expense-edit')) {
            return abort(503);
        }
        return view('fleet.expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fleet\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
          if (!Gate::allows('Fleet-expense-edit')) {
            return abort(503);
        }
        $request->validate([
            'vehicle_id' => 'required|exists:fleet_vehicles,id',
            'driver_id' => 'nullable|exists:fleet_drivers,id',
            'expense_type' => 'required|in:fuel,maintenance,toll,parking,repair,other',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'receipt_number' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $expense->update($request->all());

        return redirect()->route('fleet.expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fleet\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
          if (!Gate::allows('Fleet-expense-delete')) {
            return abort(503);
        }
        $expense->delete();

        return redirect()->route('fleet.expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}