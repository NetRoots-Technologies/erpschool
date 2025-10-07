<?php

namespace App\Http\Controllers\Building;

use Auth;
use Config;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Floor;
use App\Models\Group;
use App\Models\Building;
use App\Helper\CoreAccounts;
use App\Models\Admin\Branch;
use Illuminate\Http\Request;
use App\Models\Admin\Company;
use App\Rules\ValidateUnitArea;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // dd("d");
        // if (\Auth::user()->can('manage property')) {
        $parentId = Auth::user()->id;
        $buildings = Building::where('parent_id', $parentId)->get();

        return view('building.index', compact('buildings'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    public function create()
    {
        // dd("D");
        // if (\Auth::user()->can('create property')) {
        $companies  = Company::where('status', 1)->get()->pluck('name', 'id');
        return view('building.create', compact('companies'));
        // } else {
        //     return redirect()->back()->with('error', __('Permission Denied!'));
        // }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'area' => 'required',
                    'description' => 'required',
                    'company_id' => 'required',
                    'branch_id' => 'required',
                    'thumbnail' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),

                ]);
            }

            if ($request->hasFile('thumbnail')) {
                $thumbnailFilenameWithExt = $request->file('thumbnail')->getClientOriginalName();
                $thumbnailFilename = pathinfo($thumbnailFilenameWithExt, PATHINFO_FILENAME);
                $thumbnailExtension = $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnailFileName = $thumbnailFilename . '_' . time() . '.' . $thumbnailExtension;

                $dir = public_path('upload/thumbnail');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                // Move file to public/upload/thumbnail
                $request->file('thumbnail')->move($dir, $thumbnailFileName);
            }

            $bg = new Building();
            $bg->name = $request->name;
            $bg->area = $request->area;
            $bg->description = $request->description;
            $bg->company_id = $request->company_id;
            $bg->branch_id = $request->branch_id;
            $bg->image = 'upload/thumbnail/' . $thumbnailFileName;;
            $bg->parent_id = auth()->user()->id;
            $bg->save();

            // $parent_groups = Config::get('constants.property_parent_groups');
            // foreach ($parent_groups as $parent_group) {

            //     $data['name'] = 'Property - ' . $property->name;
            //     $data['parent_id'] = $parent_group;
            //     $data['parent_type'] = $property->id;
            //     $data['type'] = 'property';

            //     CoreAccounts::createGroup($data);
            // }

            // $parent_groups = Config::get('constants.levy_group_ids');
            // $groups = Group::whereIn('parent_id', $parent_groups)->where('type', 'levy')->pluck('id');
            // foreach ($groups as $group) {

            //     $data['name'] = 'Property - ' . $property->name;
            //     $data['parent_id'] = $group;
            //     $data['parent_type'] = $property->id;
            //     $data['type'] = 'property';

            //     CoreAccounts::createGroup($data);
            // }

            // $parent_groups = Config::get('constants.utility_group_ids');
            // $groups = Group::whereIn('parent_id', $parent_groups)->where('type', 'utility')->pluck('id');

            // foreach ($groups as $parent_group) {

            //     $data['name'] = 'Property - ' . $property->name;
            //     $data['parent_id'] = $parent_group;
            //     $data['parent_type'] = $property->id;
            //     $data['type'] = 'property';

            //     CoreAccounts::createGroup($data);
            // }

            return redirect()->route('maintainer.building.index')->with('success', 'Building successfully created.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $buildings = Building::findOrFail($id);
        $companies = Company::where('status', 1)->pluck('name', 'id');
        $floor_type = Type::where('type', 'floor_type')->pluck('title', 'id');
        $floors = Floor::with('floor_type')->where('building_id', $buildings->id)->get();
        $dataFloors = Floor::where('building_id', $buildings->id)->pluck('name', 'id');

        $units = Unit::with('floors')->where('building_id', $id)->get();
        return view('building.show', compact('companies', 'buildings', 'floor_type' , 'floors' , 'dataFloors' , 'units'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $buildings = Building::findOrFail($id);
        $companies = Company::where('status', 1)->pluck('name', 'id');
        return view('building.edit', compact('companies', 'buildings'));
    }

    public function update(Request $request, $id)
    {
        $building = Building::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'area' => 'nullable|numeric',
            'company_id' => 'required|integer',
            'branch_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $building->name = $request->name;
        $building->area = $request->area;
        $building->company_id = $request->company_id;
        $building->branch_id = $request->branch_id;
        $building->description = $request->description;

        // Handle image upload
        if ($request->hasFile('thumbnail')) {
            $fileName = time() . '.' . $request->thumbnail->extension();
            $request->thumbnail->move(public_path('upload/thumbnail'), $fileName);
            $building->image = 'upload/thumbnail/' . $fileName;
        }

        $building->save();

        return redirect()->route('maintainer.building.index')->with('success', 'Building updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $building = Building::findOrFail($id);
        $building->delete();
        return redirect()->back()->with('success', 'Building successfully deleted.');
    }

    public function getBuildingsByCompany(Request $request)
    {
        $building = DB::table("branches")->where('company_id', $request->company_id)->pluck('name', 'id');
        return response()->json($building);
    }



   
}
