<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MachineController extends Controller
{
    public function genrateEmployee()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
    }
}
