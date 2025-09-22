<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Course;
use App\Services\MarketingNotificationService;
use App\Services\VideoCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class MarketingNotificationController extends Controller
{
    public function __construct(MarketingNotificationService $MarketingNotificationService)
    {
        $this->MarketingNotificationService = $MarketingNotificationService;
    }


    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.marketing_notification.index');
    }

    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->MarketingNotificationService->getdata();
    }


    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $this->MarketingNotificationService->create();
        return view('admin.marketing_notification.create', compact('data'));
    }


    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $this->MarketingNotificationService->store($request);
        Session::flash('flash_message_sucess', 'Ad created Sucessfully Add!!!.');
        Session::flash('alert-class', 'alert-success');
        return redirect()->route('admin.marketing_notification.index');
    }


    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ad = $this->MarketingNotificationService->show($id);
        return view('admin.marketing_notification.view', compact('ad'));
    }


    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $client = $this->MarketingNotificationService->edit($id);
        $data = $this->MarketingNotificationService->create();
        return view('admin.marketing_notification.edit', compact('data', 'client'));
    }

    public function update(Request $request, $id)
    {

        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $post = $this->MarketingNotificationService->update($request, $id);
        Session::flash('flash_message_sucess', 'Notification Updated Successfully!!!.');
        Session::flash('alert-class', 'alert-success');
        return redirect()->route('admin.marketing_notification.index');
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->MarketingNotificationService->destroy($id);
        Session::flash('flash_message_sucess', 'Notification Delete Successfully!!!.');
        Session::flash('alert-class', 'bg-froly');
        return redirect()->route('admin.marketing_notification.index');
    }
}

