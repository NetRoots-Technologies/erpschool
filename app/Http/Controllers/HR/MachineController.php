<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MachineController extends Controller
{
    public function genrateEmployee()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
    }
}

