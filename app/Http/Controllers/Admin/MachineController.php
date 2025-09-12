<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laradevsbd\Zkteco\Http\Library\ZktecoLib;



class MachineController extends Controller
{
    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $zk = new ZktecoLib(config('192.168.99.124'), 4370);
        dd($zk->connect());
    }
}
