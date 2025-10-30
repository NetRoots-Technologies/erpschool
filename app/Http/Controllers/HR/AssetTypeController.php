<?php

namespace App\Http\Controllers\HR;


use App\Models\HR\AssetType;
use Illuminate\Http\Request;
use App\Helpers\CoreAccounts;
use App\Services\ledgerService;
use App\Services\AssetTypeService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class AssetTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $AssetTypeService;
    protected $coreAccounts;
    protected $fixedGroups;
    public function __construct(AssetTypeService $AssetTypeService, CoreAccounts $coreAccounts)
    {
        $this->AssetTypeService = $AssetTypeService;
        $this->coreAccounts = $coreAccounts;
        $this->fixedGroups = config('constants.FixedGroups');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('AssetType-list')) {
            return abort(503);
        }
        return view('hr.asset_type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('AssetType-create')) {
            return abort(503);
        }
        return view('hr.asset_type.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('AssetType-create')) {
            return abort(503);
        }
        DB::beginTransaction();
        try {

            $asetType = $this->AssetTypeService->store($request);

            foreach (config('constants.FixedGroups.both_asset_heads') as $groupId) {
                $data['parent_id'] = $groupId;
                $data['name'] = $request->name;
                $data['parent_type_id'] = $asetType->id;
                $data['parent_type'] = AssetType::class;
                // $this->coreAccounts->createGroup($data);

            }
            DB::commit();
            return redirect()->route('hr.asset_type.index')->with('success', 'Asset type created');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('hr.asset_type.index')
                ->with('error', 'An error occurred while creating asset type: ' . $e->getMessage());
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
        if (!Gate::allows('AssetType-list')) {
            return abort(503);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('AssetType-edit')) {
            return abort(503);
        }
        $asset_type = AssetType::find($id);
        return view('hr.asset_type.edit', compact('asset_type'));
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
        if (!Gate::allows('AssetType-edit')) {
            return abort(503);
        }
        try {
            $this->AssetTypeService->update($request, $id);
            return redirect()->route('hr.asset_type.index')->with('success', 'Asset type Updated');
        } catch (\Exception $e) {
            return redirect()->route('hr.asset_type.index')->with('error', 'An error occurred while updating asset type');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!Gate::allows('AssetType-delete')) {
            return abort(503);
        }
        $this->AssetTypeService->destroy($id);
        return redirect()->route('hr.asset_type.index')->with('success', 'Asset type deleted');
    }

    public function getdata()
    {
        if (!Gate::allows('Dashboard-list')) {
            return abort(503);
        }
        return $this->AssetTypeService->getdata();
    }
}

