<?php

namespace App\Http\Controllers;

use Config;
use App\Models\Type;
use App\Models\User;
use App\Models\Unit;
use App\Models\Group;
use App\Models\Ledger;
use App\Models\Property;
use App\Models\Maintainer;
use App\Helper\CoreAccounts;
use App\Models\PropertyUnit;
use Illuminate\Http\Request;
use App\Models\RequestApproval;
use Illuminate\Validation\Rule;
use App\Models\MaintainerPayment;
use App\Models\MaintenanceRequest;
use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;



class MaintenanceRequestController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $user_id = Auth::user()->id;
            $data = MaintenanceRequest::with(['buildings', 'units', 'types', 'maintainers', 'users'])->where('user_id', $user_id);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('building_name', function ($row) {
                    return $row->buildings->name;
                })
                ->addColumn('unit_name', function ($row) {
                    return $row->units->name;
                })
                ->addColumn('type_name', function ($row) {
                    return $row->types->title;
                })

                ->addColumn('request_date', function ($row) {
                    return $row->request_date;
                })

                ->addColumn('request_creater', function ($row) {
                    return $row->users->name;
                })

                ->addColumn('maintenance_name', function ($row) {
                    return $row->maintainers->name;
                })

                ->addColumn('status', function ($row) {
                    if ($row->status == 'Pending') {
                        return '<span class="badge badge-info" style = "background: #00b8ff;"> Pending </span>';
                    } elseif ($row->status == 'in_progress') {
                        return '<span class="badge badge-success" style = "background: #22c03c;"> In Prograass </span>';
                    } elseif ($row->status == 'completed') {
                        return '<span class="badge badge-primary" style = "background: #0014ff;"> Complate </span>';
                    } elseif ($row->status == 'reject') {
                        return '<span class="badge badge-danger" style = "background: #ff0000;"> Reject </span>';
                    } elseif ($row->status == 'approval') {
                        return '<span class="badge badge-warning" style = "background: #ffc800;"> Approval </span>';
                    } else {
                        return "--";
                    }
                })

                ->addColumn('action', function ($row) {
                    $editUrl = route('maintenance-request.edit', $row->id);
                    $showUrl = route('maintenance-request.show', $row->id);
                    $btn  = '<a href="' . $showUrl . '" class="viewDetails btn btn-warning btn-sm">view</a> ';
                    $btn  .= '<a href="' . $editUrl . '" class="editType btn btn-primary btn-sm">Edit</a> ';
                    $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger deleteType">Delete</button> ';

                    return $btn;
                })
                ->rawColumns(['action', 'building_name', 'unit_name', 'type_name', 'request_date', 'request_creater', 'maintenance_name', 'status'])
                ->make(true);
        }
        return view('maintenance_request.index');
    }

    public function create()
    {

        // dd(auth()->user());
        if (\Auth::user()->can('create maintainer')) {
            $buildings = Building::where('parent_id', auth()->id())->pluck('name', 'id');
            $issueTypes = Type::where('parent_id', auth()->id())->where('type', 'issue')->pluck('title', 'id');
            $maintainerUsers = User::whereHas('maintainerUsers', fn($q) => $q->where('name', 'maintainer'))->pluck('name', 'id');
            return view('maintenance_request.create', compact('buildings', 'maintainerUsers', 'issueTypes'));
            // } else {
            //     return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    // Controller method
    public function unitsByBuilding(Building $building)
    {
        $units = Unit::where('building_id', $building->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($units);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'building_id'    => ['required', 'exists:buildings,id'],
            'unit_id'        => ['required', 'exists:units,id'],
            'request_date'   => ['required', 'date'],
            'issue_type'  => ['required', 'exists:types,id'],
            'maintainer_id'    => ['required', 'exists:users,id'],
            'issue_attachment' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'notes'          => ['nullable', 'string', 'max:2000'],
        ]);

        $imageName = null;
        if ($request->hasFile('issue_attachment')) {
            $file = $request->file('issue_attachment');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('issue_attachment'), $imageName);
        }

        $req = MaintenanceRequest::create([
            'building_id'        => $data['building_id'],
            'unit_id'            => $data['unit_id'],
            'maintainer_id'     => $data['maintainer_id'],
            'issue_type'      => $data['issue_type'],
            'title'              => 'Maintenance Request',
            'notes'              => $data['notes'] ?? null,
            'status'             => 'Pending',
            'issue_attachment'  =>  $imageName,
            'request_date'  =>  $data['request_date'],
            'user_id' =>  Auth::user()->id
        ]);

        return redirect()->route('maintenance-request.index')->with('success', 'Maintenance Request created');
    }

    public function edit(MaintenanceRequest $maintenance_request)
    {

        $buildings = Building::where('parent_id', auth()->id())->pluck('name', 'id');

        $units = Unit::where('building_id', $maintenance_request->building_id)->pluck('name', 'id');

        $issueTypes = Type::where('parent_id', auth()->id())->where('type', 'issue')->pluck('title', 'id');

        $maintainerUsers = User::whereHas('maintainerUsers', fn($q) => $q->where('name', 'maintainer'))->pluck('name', 'id');

        return view('maintenance_request.edit', [
            'model'           => $maintenance_request,
            'buildings'       => $buildings,
            'units'           => $units,
            'issueTypes'      => $issueTypes,
            'maintainerUsers' => $maintainerUsers,
        ]);
    }


    public function update(Request $request, MaintenanceRequest $maintenance_request)
    {

        $data = $request->validate([
            'building_id'    => ['required', 'exists:buildings,id'],
            'unit_id'        => ['required', 'exists:units,id'],
            'request_date'   => ['required', 'date'],
            'issue_type'  => ['required', 'exists:types,id'],
            'maintainer_id'    => ['required', 'exists:users,id'],
            'issue_attachment' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'notes'          => ['nullable', 'string', 'max:2000'],
        ]);

        $imageName = $maintenance_request->issue_attachment;
        if ($request->hasFile('issue_attachment')) {
            $file = $request->file('issue_attachment');
            $newName = time() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('issue_attachment'), $newName);

            if (!empty($imageName)) {
                $oldPath = public_path('issue_attachment/' . $imageName);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $imageName = $newName;
        }


        $maintenance_request->update([
            'building_id'     => $data['building_id'],
            'unit_id'         => $data['unit_id'],
            'issue_type'   => $data['issue_type'],
            'maintainer_id'     => $data['maintainer_id'],
            'request_date'    => $data['request_date'],
            'notes'           => $data['notes'] ?? null,
            'status'          => $maintenance_request->status,
            'issue_attachment' => $imageName,
        ]);

        return redirect()
            ->route('maintenance-request.index')
            ->with('success', 'Maintenance request updated successfully.');
    }

    public function destroy(MaintenanceRequest $maintenance_request)
    {

        $maintenance_request->delete();
        return response()->json();
    }

    public function pendingRequest()
    {
        if (request()->ajax()) {
            $user_id = Auth::user()->id;
            $data = MaintenanceRequest::with(['buildings', 'units', 'types', 'maintainers', 'users'])
                ->where('status', 'Pending')
                ->where('user_id', $user_id);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('building_name', function ($row) {
                    return $row->buildings->name;
                })
                ->addColumn('unit_name', function ($row) {
                    return $row->units->name;
                })
                ->addColumn('type_name', function ($row) {
                    return $row->types->title;
                })

                ->addColumn('request_date', function ($row) {
                    return $row->request_date;
                })

                ->addColumn('request_creater', function ($row) {
                    return $row->users->name;
                })

                ->addColumn('maintenance_name', function ($row) {
                    return $row->maintainers->name;
                })

                ->addColumn('status', function ($row) {
                    if ($row->status == 'Pending') {
                        return '<span class="badge badge-info" style = "background: #00b8ff;"> Pending </span>';
                    } elseif ($row->status == 'in_progress') {
                        return '<span class="badge badge-success" style = "background: #22c03c;"> In Prograass </span>';
                    } elseif ($row->status == 'completed') {
                        return '<span class="badge badge-primary" style = "background: #0014ff;"> Complate </span>';
                    } elseif ($row->status == 'reject') {
                        return '<span class="badge badge-danger" style = "background: #ff0000;"> Reject </span>';
                    } elseif ($row->status == 'approval') {
                        return '<span class="badge badge-warning" style = "background: #ffc800;"> Approval </span>';
                    } else {
                        return "--";
                    }
                })

                ->addColumn('action', function ($row) {
                    $editUrl = route('maintenance-request.edit', $row->id);
                    $btn  = '<a href="' . $editUrl . '" class="editType btn btn-primary btn-sm">Edit</a>';
                    $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger deleteType">Delete</button>';

                    return $btn;
                })
                ->rawColumns(['action', 'building_name', 'unit_name', 'type_name', 'request_date', 'request_creater', 'maintenance_name', 'status'])
                ->make(true);
        }
        return view('maintenance_request.pending');
    }

    public function inProgressRequest()
    {
        if (request()->ajax()) {
            $user_id = Auth::user()->id;
            $data = MaintenanceRequest::with(['buildings', 'units', 'types', 'maintainers', 'users'])
                ->where('status', 'in_progress')
                ->where('user_id', $user_id);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('building_name', function ($row) {
                    return $row->buildings->name;
                })
                ->addColumn('unit_name', function ($row) {
                    return $row->units->name;
                })
                ->addColumn('type_name', function ($row) {
                    return $row->types->title;
                })

                ->addColumn('request_date', function ($row) {
                    return $row->request_date;
                })

                ->addColumn('request_creater', function ($row) {
                    return $row->users->name;
                })

                ->addColumn('maintenance_name', function ($row) {
                    return $row->maintainers->name;
                })

                ->addColumn('status', function ($row) {
                    if ($row->status == 'Pending') {
                        return '<span class="badge badge-info" style = "background: #00b8ff;"> Pending </span>';
                    } elseif ($row->status == 'in_progress') {
                        return '<span class="badge badge-success" style = "background: #22c03c;"> In Prograass </span>';
                    } elseif ($row->status == 'completed') {
                        return '<span class="badge badge-primary" style = "background: #0014ff;"> Complate </span>';
                    } elseif ($row->status == 'reject') {
                        return '<span class="badge badge-danger" style = "background: #ff0000;"> Reject </span>';
                    } elseif ($row->status == 'approval') {
                        return '<span class="badge badge-warning" style = "background: #ffc800;"> Approval </span>';
                    } else {
                        return "--";
                    }
                })

                ->addColumn('action', function ($row) {
                    $editUrl = route('maintenance-request.edit', $row->id);
                    $btn  = '<a href="' . $editUrl . '" class="editType btn btn-primary btn-sm">Edit</a>';
                    $btn .= '<button type="button" data-id="' . $row->id . '" class="btn btn-sm btn-danger deleteType">Delete</button>';

                    return $btn;
                })
                ->rawColumns(['action', 'building_name', 'unit_name', 'type_name', 'request_date', 'request_creater', 'maintenance_name', 'status'])
                ->make(true);
        }
        return view('maintenance_request.in_progress');
    }

    public function approval()
    {
        // dd(auth()->user()->hasRole('maintainer'));
        if (request()->ajax()) {
            $isMaintainer = auth()->user()->hasRole('maintainer');

            if ($isMaintainer) {
                $data = MaintenanceRequest::with(['buildings', 'units', 'types', 'maintainers', 'users', 'approvals'])
                    ->where('maintainer_id', Auth::id());
            } else {
                $data = MaintenanceRequest::with(['buildings', 'units', 'types', 'maintainers', 'users', 'approvals'])
                    ->where('user_id', Auth::id());
            }


          

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('building_name', function ($row) {
                    return $row->buildings->name;
                })
                ->addColumn('unit_name', function ($row) {
                    return $row->units->name;
                })
                ->addColumn('type_name', function ($row) {
                    return $row->types->title;
                })

                ->addColumn('request_date', function ($row) {
                    return $row->request_date;
                })

                ->addColumn('request_creater', function ($row) {
                    return $row->users->name;
                })

                ->addColumn('maintenance_name', function ($row) {
                    return $row->maintainers->name;
                })

                ->addColumn('status', function ($row) {
                    if ($row->status == 'Pending') {
                        return '<span class="badge badge-info" style = "background: #00b8ff;"> Pending </span>';
                    } elseif ($row->status == 'in_progress') {
                        return '<span class="badge badge-success" style = "background: #22c03c;"> In Prograass </span>';
                    } elseif ($row->status == 'completed') {
                        return '<span class="badge badge-primary" style = "background: #0014ff;"> Complate </span>';
                    } elseif ($row->status == 'reject') {
                        return '<span class="badge badge-danger" style = "background: #ff0000;"> Reject </span>';
                    } elseif ($row->status == 'approval') {
                        return '<span class="badge badge-warning" style = "background: #ffc800;"> Approval </span>';
                    } else {
                        return "--";
                    }
                })

                ->addColumn('done_status', function ($row) {
                    if (!empty($row->approvals)) {
                        if ($row->approvals->done_status == 'Pending') {
                            return '<span class="badge badge-info" style = "background: #00b8ff;"> Pending </span>';
                        } elseif ($row->approvals->done_status == 'in_progress') {
                            return '<span class="badge badge-success" style = "background: #22c03c;"> In Prograass </span>';
                        } elseif ($row->approvals->done_status == 'completed') {
                            return '<span class="badge badge-primary" style = "background: #0014ff;"> Complate </span>';
                        } elseif ($row->approvals->done_status == 'reject') {
                            return '<span class="badge badge-danger" style = "background: #ff0000;"> Reject </span>';
                        } elseif ($row->approvals->done_status == 'approval') {
                            return '<span class="badge badge-warning" style = "background: #ffc800;"> approval </span>';
                        }
                    } else {
                        return '<span class="badge badge-info" style = "background: #00b8ff;"> Pending </span>';
                    }
                })

                ->addColumn('approval_status', function ($row) {
                    if (!empty($row->approvals)) {
                        if ($row->approvals->approval_status == 'Pending') {
                            return '<span class="badge badge-info" style = "background: #00b8ff;"> Pending </span>';
                        } elseif ($row->approvals->approval_status == 'in_progress') {
                            return '<span class="badge badge-success" style = "background: #22c03c;"> In Prograass </span>';
                        } elseif ($row->approvals->approval_status == 'completed') {
                            return '<span class="badge badge-primary" style = "background: #0014ff;"> Complate </span>';
                        } elseif ($row->approvals->approval_status == 'reject') {
                            return '<span class="badge badge-danger" style = "background: #ff0000;"> Reject </span>';
                        } elseif ($row->approvals->approval_status == 'approval') {
                            return '<span class="badge badge-warning" style = "background: #ffc800;"> Approval </span>';
                        }
                    } else {
                        return '<span class="badge badge-info" style = "background: #00b8ff;"> Pending </span>';
                    }
                })

                ->addColumn('action', function ($row) use ($isMaintainer) {

                    
                    if ($isMaintainer) {
                        $progressUrl = route('maintenance-request.progress', $row->id);
                        $completeUrl = route('maintenance-request.complete', $row->id);

                        if ($row->approvals->done_status === 'in_progress') {
                             $btn  = '<button type="button" class="btn btn-warning btn-sm" disabled>InProgress</button> ';
                             $btn .= '<a href="#" data-url="' . $completeUrl . '" class="btn btn-success btn-sm js-complete">Complated</a>';
                        }elseif($row->approvals->done_status === 'completed'){
                             $btn = '<a href="#" data-url="' . $progressUrl . '" class="btn btn-warning btn-sm js-progress">InProgress</a> ';
                             $btn  .= '<button type="button" class="btn btn-success btn-sm" disabled>Complated</button> ';
                        }else {
                             $btn = '<a href="#" data-url="' . $progressUrl . '" class="btn btn-warning btn-sm js-progress">InProgress</a> ';
                             $btn .= '<a href="#" data-url="' . $completeUrl . '" class="btn btn-success btn-sm js-complete">Complated</a> ';   
                        }

                        return $btn;
                    } else {
                        // Non-maintainers: Approve/Reject (your original logic)
                        $approveUrl = route('maintenance-request.approvalRequest', $row->id);
                        $rejectUrl  = route('maintenance-request.reject', $row->id);

                        if ($row->status === 'approval' || $row->status === 'approved') {
                            $btn  = '<button type="button" class="btn btn-primary btn-sm" disabled>Approved</button> ';
                            $btn .= '<a href="#" data-url="' . $rejectUrl . '"  class="btn btn-danger btn-sm js-reject">Reject</a>';
                        } else {
                            $btn  = '<a href="#" data-url="' . $approveUrl . '" class="btn btn-primary btn-sm js-approve">Approve</a> ';
                            $btn .= '<a href="#" data-url="' . $rejectUrl . '"  class="btn btn-danger btn-sm js-reject">Reject</a>';
                        }
                        return $btn;
                    }
                })
                ->rawColumns([
                    'action',
                    'building_name',
                    'unit_name',
                    'type_name',
                    'request_date',
                    'request_creater',
                    'maintenance_name',
                    'approval_status',
                    'done_status',
                    'status'
                ])
                ->make(true);
        }
        return view('maintenance_request.approval');
    }

        public function ApprovalRequest($id, Request $req)
        {
            // dd($id);
            $mr = MaintenanceRequest::findOrFail($id);
            $mr->status = 'approval';
            $mr->save();

            $apReq = new RequestApproval;
            $apReq->request_id = $mr->id;
            $apReq->approver_id = auth()->id();
            $apReq->approval_status = 'approval';
            $apReq->approval_level = 1;
            $apReq->approval_date = now();
            $apReq->save();

            return response()->json([
                'success' => true,
                'message' => 'Request approved successfully',
                'id'      => $mr->id,
                'status'  => $mr->approval_status,
            ]);
        }

    public function rejectRequest($id, Request $req)
    {
        $req->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $mr = MaintenanceRequest::findOrFail($id);
        $mr->status = "reject";
        $mr->save();

        return response()->json([
            'success' => true,
            'message' => 'Request rejected successfully',
            'id'      => $mr->id,
            'status'  => $mr->approval_status,
        ]);
    }

            public function markInProgress($id)
        {
            
            $mr = MaintenanceRequest::findOrFail($id);
            $apReq = RequestApproval::updateOrCreate(
                [
                    'request_id'  => $mr->id,
                ],
                [   
                    'request_id'  => $mr->id,
                    'done_status'     => 'in_progress',
                ]);


        return response()->json([
            'success' => true,
            'message' => 'Request approved successfully',
            'id'      => $mr->id,
            'status'  => $mr->done_status,
        ]);
        }

        public function markCompleted($id)
        {
            
            
                $mr = MaintenanceRequest::findOrFail($id);
                $apReq = RequestApproval::updateOrCreate(
                [
                    'request_id'  => $mr->id,
                ],
                [   
                    'request_id'  => $mr->id,
                    'done_status'     => 'completed',
                ]);
                
        return response()->json([
            'success' => true,
            'message' => 'Request approved successfully',
            'id'      => $mr->id,
            'status'  => $mr->done_status,
        ]);
        
        }

        public function show($id){
            $user_id = Auth::user()->id;
            $data = MaintenanceRequest::with(['buildings', 'units', 'types', 'maintainers', 'users' , 'approvals'])
                        ->where('user_id', $user_id)
                        ->where('id' , $id)->first();
            // dd( $data->toArray());
            return view("maintenance_request.show" , compact('data'));

            
        }
}
