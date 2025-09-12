<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MarketingVideoServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MarketingVideoController extends Controller
{

    public function __construct(MarketingVideoServices $MarketingVideoServices)
    {
        $this->MarketingVideoServices = $MarketingVideoServices;
    }

    public function index()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.marketing_video.index');
    }

    public function create()
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        return view('admin.marketing_video.create');
    }

    public function getdata()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return $this->MarketingVideoServices->getdata();

    }

    public function store(Request $request)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->MarketingVideoServices->store($request);

        return redirect()->route('admin.marketing_video.index')
            ->with('success', 'Video created successfully');
    }

    public function show($id)
    {

        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->MarketingVideoServices->show($id);
        return view('admin.marketing_video.view', compact('data'));
    }


    public function edit($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data = $this->MarketingVideoServices->edit($id);
        return view('admin.marketing_video.edit', compact('data'));
    }


    public function update(Request $request, $id)
    {
if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->MarketingVideoServices->update($request, $id);
        return redirect()->route('admin.marketing_video.index')
            ->with('success', 'Video Update successfully');
    }

    public function destroy($id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $this->MarketingVideoServices->destroy($id);
        return redirect()->route('admin.marketing_video.index')
            ->with('success', 'Video delete successfully');
    }
}
