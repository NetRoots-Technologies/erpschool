<?php

namespace App\Http\Controllers\Type;

use App\Http\Controllers\Controller;
use App\Helper\CoreAccounts;
use App\Models\Type;
use Illuminate\Http\Request;
use Config;
use App\Helper\Helpers;
use DataTables;
class TypeController extends Controller
{

    public function index()
    {   
        // dd(\Auth::user()->can('manage types'));
        // if (\Auth::user()->can('manage types') ) {

            if (request()->ajax()) {
                $data = Type::where('parent_id', Helpers::parentId());

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $btn  = '<a href="javascript:void(0)" data-id="'.$row->id.'" data-type="'.$row->type.'" data-title="'.$row->title.'" class="editType btn btn-primary btn-sm mr-1">Edit</a>';
                        $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="deleteType btn btn-danger btn-sm">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $types = Type::$types;
            return view('type.index' , compact('types'));
        // } 
    }


    public function create()
    {
        $types = Type::$types;
        return view('type.create', compact('types'));
    }


    public function store(Request $request)
    {

        if (\Auth::user()->can('create types')) {
            $validator = \Validator::make(
                $request->all(), [
                'title' => 'required',
                'type' => 'required',

            ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $type = new Type();
            $type->title = $request->title;
            $type->type = $request->type;
            $type->parent_id = Helpers::parentId();
            $type->save();

            if ($type->type == 'utility') {
                $parent_groups = Config::get('constants.utility_group_ids');
                foreach ($parent_groups as $parent_group) {

                    $data['name'] = Type::$types[$type->type] . ' - ' . $type->title;
                    $data['parent_id'] = $parent_group;
                    $data['parent_type'] = $type->id;
                    $data['type'] = 'utility';

                    CoreAccounts::createGroup($data);
                }
            } elseif ($type->type == 'levy') {
                $parent_groups = Config::get('constants.levy_group_ids');
                foreach ($parent_groups as $parent_group) {

                    $data['name'] = Type::$types[$type->type] . ' - ' . $type->title;
                    $data['parent_id'] = $parent_group;
                    $data['parent_type'] = $type->id;
                    $data['type'] = 'levy';

                    CoreAccounts::createGroup($data);
                }
            }
            return response()->json([
                'status' => 'success',
                'message'=> 'Type successfully created',
            ]);
        }
    }


    public function show(Type $type)
    {
        //
    }


    public function edit(Type $type)
    {
        $types = Type::$types;
        return view('type.edit', compact('types'));
    }


    public function update(Request $request, Type $type)
    {
        // dd($request->all() , $type);
        if (\Auth::user()->can('edit types')) {
            $validator = \Validator::make(
                $request->all(), [
                'title' => 'required',
                'type' => 'required',

            ],
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

//            $type = new Type();
            $type->title = $request->title;
            $type->type = $request->type;
            $type->parent_id = auth()->user()->id;
            $type->save();

            return redirect()->back()->with('success', __('Type successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function destroy(Type $type)
    {
        if (\Auth::user()->can('delete types')) {
            $type->delete();
            return redirect()->back()->with('success', 'Type successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
}

