<?php

namespace App\Http\Controllers\Reports;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Event\RequestEvent;

use Illuminate\Http\Request;

class KarigarController extends Controller
{
    public function karigar_report()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.account_reports.karigar_reports.index');
    }
    public function print_karigar_report(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.account_reports.karigar_reports.print_report');
    }
}

