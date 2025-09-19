<?php

namespace App\Services;

use Config;
use DataTables;
use App\Models\Admin\BannerAd;
use App\Models\Admin\VideoCategory;
use Illuminate\Support\Facades\Gate;


class MarketingBannerAdService
{

    public function create()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        //        $data['Categorys'] = Category::all();
//        $data['Sub_Category'] = Sub_Category::all();
        return 0;
    }

    public function store($request)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $bannerad = BannerAd::create(['banner_title' => $request->banner_title, 'banner_description' => $request->banner_description]);
    } //ok

    public function show($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return BannerAd::where('id', $id)->first();

    }

    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $data = BannerAd::get();


        return Datatables::of($data)->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = ' <form   onsubmit="return confirm(' . "'Are you sure you want to Delete this?'" . ');" method="POST"  action="' . route("admin.marketing_banner_ad.destroy", $row->id) . '"> ';
                $btn = $btn . '<a href=" ' . route("admin.marketing_banner_ad.show", $row->id) . '"  class="ml-2"><i class="fas fa-eye"></i></a>';
                $btn = $btn . ' <a href="' . route("admin.marketing_banner_ad.edit", $row->id) . '" class="ml-2">  <i class="fas fa-edit"></i></a>';
                $btn = $btn . '<button  type="submit" class="ml-2"    ><i class="fas fa-trash"></i></button>';
                $btn = $btn . method_field('DELETE') . '' . csrf_field();
                $btn = $btn . ' </form>';
                return $btn;
            })
            ->rawColumns(['action', 'user'])
            ->make(true);
    }

    public function edit($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return BannerAd::where('id', $id)->first();
    }

    public function uploadImage($path, $image)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $folderPath = $path;
        $image_parts = explode(";base64,", $image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $image_name = uniqid() . '.' . $image_type;
        $file = $folderPath . $image_name;
        file_put_contents($file, $image_base64);
        return $image_name;
    }

    public function update($request, $id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $banner = BannerAd::find($id);
        $banner->banner_title = $request->banner_title;
        $banner->banner_description = $request->banner_description;
        $banner->save();
    }

    public function destroy($id)
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        $Service = BannerAd::findOrFail($id);
        if (asset($Service))
            $Service->delete();
    }


}


