<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BannerAd;
use App\Services\MarketingBannerAdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class MarketingBannerAdController extends Controller
{

    public function __construct(MarketingBannerAdService $MarketingBannerAdService)
    {
        $this->MarketingBannerAdService = $MarketingBannerAdService;
    }



    public function index()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return view('admin.marketing_banner_ad.index');
    }

    public function getdata()
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->MarketingBannerAdService->getdata();
    }


    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $this->MarketingBannerAdService->create();
        return view('admin.marketing_banner_ad.create', compact('data'));
    }


    public function store(Request $request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = $this->MarketingBannerAdService->store($request);
        Session::flash('flash_message_sucess', 'Ad created Sucessfully Add!!!.');
        Session::flash('alert-class', 'alert-success');
        return redirect()->route('admin.marketing_banner_ad.index');
    }


    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $ad = $this->MarketingBannerAdService->show($id);
        return view('admin.marketing_banner_ad.view', compact('ad'));
    }


    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $client = $this->MarketingBannerAdService->edit($id);
        $data = $this->MarketingBannerAdService->create();
        return view('admin.marketing_banner_ad.edit', compact('data', 'client'));
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $post = $this->MarketingBannerAdService->update($request, $id);
        Session::flash('flash_message_sucess', 'Client Profile Updated Successfully!!!.');
        Session::flash('alert-class', 'alert-success');
        return redirect()->route('admin.marketing_banner_ad.index');
    }

    public function destroy($id)
    {
if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $this->MarketingBannerAdService->destroy($id);
        Session::flash('flash_message_sucess', 'Banner Ad Delete Successfully!!!.');
        Session::flash('alert-class', 'bg-froly');
        return redirect()->route('admin.marketing_banner_ad.index');
    }




}

