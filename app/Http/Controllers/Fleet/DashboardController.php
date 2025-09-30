<?php

namespace App\Http\Controllers\Fleet;

use App\Http\Controllers\Controller;
use App\Models\Fleet\Vehicle;
use App\Models\Fleet\Driver;
use App\Models\Fleet\Route;
use App\Models\Fleet\Maintenance;
use App\Models\Fleet\Fuel;
use App\Models\Fleet\Expense;
// Transportation model removed - using simple checkbox approach
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $stats = [
            'total_vehicles' => Vehicle::count(),
            'active_vehicles' => Vehicle::where('status', 'active')->count(),
            'maintenance_vehicles' => Vehicle::where('status', 'maintenance')->count(),
            'active_drivers' => Driver::where('status', 'active')->count(),
            'active_routes' => Route::where('status', 'active')->count(),
            'students_transported' => \App\Models\Student\Students::where('transport_required', true)->count(),
        ];

        // Get recent maintenance records
        $recent_maintenance = Maintenance::with('vehicle')
            ->orderBy('maintenance_date', 'desc')
            ->limit(5)
            ->get();

        // Get monthly expenses data for chart
        $monthly_expenses = $this->getMonthlyExpenses();

        return view('fleet.dashboard', compact('stats', 'recent_maintenance', 'monthly_expenses'));
    }

    private function getMonthlyExpenses()
    {
        $months = [];
        $fuel_expenses = [];
        $maintenance_expenses = [];

        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $fuel_expenses[] = Fuel::whereYear('fuel_date', $date->year)
                ->whereMonth('fuel_date', $date->month)
                ->sum('total_cost');
            
            $maintenance_expenses[] = Maintenance::whereYear('maintenance_date', $date->year)
                ->whereMonth('maintenance_date', $date->month)
                ->sum('cost');
        }

        return [
            'labels' => $months,
            'fuel' => $fuel_expenses,
            'maintenance' => $maintenance_expenses,
        ];
    }
}
