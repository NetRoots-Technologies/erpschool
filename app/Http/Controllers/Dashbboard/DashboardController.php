<?php

namespace App\Http\Controllers\Dashbboard;

use Event;
use Carbon\Carbon;
use App\Events\SendMail;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Models\HRM\Employees;
use NsTechNs\JazzCMS\JazzCMS;
// use App\Models\Fee\StudentFee; // Removed - model no longer exists
use App\Services\LedgerService;
use App\Models\Student\Students;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeCollectionDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $ledgerService;
    public function __construct(ledgerService $ledgerService)
    {
        $this->ledgerService = $ledgerService;
    }


    public function index()
    {
     if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $employees_name = Employees::latest()->limit(6)->get();

        // Get today's fee collections using new fee structure
        $today_fee_collections = FeeCollection::whereDate('created_at', today())
            ->where('status', 'paid')
            ->sum('paid_amount');

        $today_fee = $today_fee_collections;

        // Get monthly fee collections using new fee structure
        $monthFee = FeeCollection::whereBetween('created_at', [date('Y-m-01'), date('Y-m-d')])
            ->where('status', 'paid')
            ->sum('paid_amount');

        $employees = Employees::with('department')->get();

        $employeesByDepartment = $employees->groupBy('department.name');

        $totalEmployees = $employees->count();

        $percentagePerDepartment = $employeesByDepartment->map(function ($employeesInDepartment) use ($totalEmployees) {
            $departmentName = $employeesInDepartment->first()->department->name ?? '';
            $numberOfEmployees = $employeesInDepartment->count();
            $percentage = ($numberOfEmployees / $totalEmployees) * 100 ;

            return [
                'department' => $departmentName,
                'percentage' => round($percentage, 2),
                'total_employees' => $numberOfEmployees,
            ];

        });

        return view('dashboard.home', compact('monthFee', 'percentagePerDepartment', 'today_fee', 'employees_name'));
    }

    public function sms()
    {
if (!Gate::allows('students')) {
            return abort(503);
        }

        //        $response = (new JazzCMS)->sendSMS("03163020616", "message text");
//        dd($response);
// OR with extra parameters


        $client = new \GuzzleHttp\Client();
        $endpoint = "https://connect.jazzcmt.com/sendsms_url.html";

        $response = Http::get($endpoint, [
            'Username' => '03018845830',
            'Password' => 'Jazz@123',
            'From' => 'onez',
            'To' => '03163020616',
            'Message' => "Test message ",
        ]);
        dd($response->body());


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       if (!Gate::allows('students')) {
            return abort(503);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }
}
