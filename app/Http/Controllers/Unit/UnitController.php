<?php

namespace App\Http\Controllers\Unit;

use App\Models\Unit;
use App\Models\Floor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function floorArea($floor_id)
    {

        // Floor area
        $floor = Floor::select('id', 'area')->findOrFail($floor_id);
        $occupied = Unit::where('floor_id', $floor_id)->sum('area');
        $floorArea     = (float) ($floor->area ?? 0);
        $occupiedArea  = (float) ($occupied ?? 0);
        $remainingArea = max(0.0, $floorArea - $occupiedArea);

        return response()->json([
            'floor_area'     => $floorArea,
            'occupied_area'  => $occupiedArea,
            'remaining_area' => $remainingArea,
        ]);
    }
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unitStore(Request $request)
    {
            $validator = Validator::make($request->all(), [

                    'name' => 'required|string|max:100',
                    'floor_id' => 'required',
                    'area'=> 'required|numeric',
                    'building_id'=> 'required',
                    'remarks' => 'nullable|string|max:255',
            ]);

            $unit = new Unit();
            $unit->name = $request->name;
            $unit->area = $request->area;
            $unit->floor_id = $request->floor_id;
            $unit->building_id = $request->building_id;
            $unit->remarks = $request->remarks;
            $unit->save();

            // $parent_groups = Group::where('type', 'property')->where('parent_type', $property_id)->pluck('id');
            // $groups = Group::whereIn('parent_id', $parent_groups)->where('type', 'floor')->where('parent_type', $floor_id)->pluck('id');
            // $property_name = Property::where('id', $property_id)->value('name');
            // $floor_name = Floor::where('id', $floor_id)->value('name');
            // foreach ($groups as $group_id) {
            //     $ledger_name = 'Unit - ' . $request->name . ' (' . $property_name . ' - ' . $floor_name . ')';
            //     CoreAccounts::create_ledger($group_id, $ledger_name, $property_id, $unit->id);
            // }

            return redirect()->back()->with('success', __('Unit successfully created.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

                    'name' => 'required|string|max:100',
                    'floor_id' => 'required',
                    'area'=> 'required|numeric',
                    'building_id'=> 'required',
                    'remarks' => 'nullable|string|max:255',
            ]);

            $unit = Unit::find( $id );
            $unit->name = $request->name;
            $unit->area = $request->area;
            $unit->floor_id = $request->floor_id;
            $unit->building_id = $request->building_id;
            $unit->remarks = $request->remarks;
            $unit->save();


            return redirect()->back()->with('success', __('Unit Update successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Unit::find($id);
        $unit->delete();
        return redirect()->back()->with('success', __('Unit Delete Successfully'));
    }
}
