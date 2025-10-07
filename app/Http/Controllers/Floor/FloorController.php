<?php

namespace App\Http\Controllers\Floor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\CoreAccounts;
use App\Models\Floor;
use App\Models\Group;
use App\Models\Building;
use App\Models\Type;
use App\Rules\ValidateUnitArea;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Admin\Company;
use App\Models\Admin\Branch;

class FloorController extends Controller
{
    
    public function storeFloor(Request $request, $building_id)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'building_id' => 'required',
                'floor_type_id' => 'required',
                'area' => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', __($messages->first()));
        }

            $floor = new Floor();
            $floor->name = $request->name;
            $floor->building_id = $request->building_id;
            $floor->floor_type_id = $request->floor_type_id;
            $floor->area = $request->area;
            $floor->save();

            // $groups = Group::where('type', 'property')->where('parent_type', $request->property_id)->pluck('id');
            // $property_name = Property::where('id', $request->property_id)->value('name');
            // foreach ($groups as $parent_group) {

            //     $data['name'] = 'Floor - ' . $request->name . ' (' . $property_name . ')';;
            //     $data['parent_id'] = $parent_group;
            //     $data['parent_type'] = $floor->id;
            //     $data['type'] = 'floor';

            //     CoreAccounts::createGroup($data);
            // }

        return redirect()->back()->with('success', __('Floor successfully created.'));
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                // 'building_id' => 'required',
                'floor_type_id' => 'required',
                'area' => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', __($messages->first()));
        }

        $floor = Floor::find($id);
        $floor->name = $request->name;
        // $floor->building_id = $request->building_id;
        $floor->floor_type_id = $request->floor_type_id;
        $floor->area = $request->area;
        $floor->save();

        return $floor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $floor = Floor::find($id);
        $floor->delete();
        return redirect()->back()->with('success', __('Floor Delete Successfully'));
    }
}
