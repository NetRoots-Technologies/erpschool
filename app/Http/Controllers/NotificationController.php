<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApprovalRequest;
use App\Models\HR\LeaveRequest;
use App\Models\ApprovalAuthority;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    // getNotifications

    // getCount
    public function getNotifications()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data['notifications'] = NotificationService::getAllNotifications(auth()->id());

        return response()->json(['success' => true, 'data' => $data], 200);
    }

    public function getUnreadCount()
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $data['count'] = NotificationService::getUnreadNotificationCount(auth()->id());
        return response()->json(['success' => true, 'unread' => $data], 200);
    }

    public function approve(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        $currentRequest = ApprovalRequest::findOrFail($request->id);

        // Get the associated approval authority
        $currentAuthority = ApprovalAuthority::find($currentRequest->approval_authority_id);

        if (!$currentAuthority || $currentAuthority->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $currentRole = DB::table('approval_roles')
            ->where('id', $currentAuthority->approval_role_id)
            ->first();

        if (!$currentRole) {
            return response()->json(['error' => 'Role not found'], 404);
        }


        $currentRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Check for next level
        $nextLevel = $currentRole->level + 1;

        $nextRole = DB::table('approval_roles')
            ->where('level', $nextLevel)
            ->first();

        if ($nextRole) {
            // Find the next approver (authority with higher role level)
            $nextAuthority = ApprovalAuthority::where('module', 'leave')
                ->where('company_id', $currentAuthority->company_id)
                ->where('branch_id', $currentAuthority->branch_id)
                ->where('approval_role_id', $nextRole->id)
                ->where('is_active', 1)
                ->first();

            if ($nextAuthority) {
                ApprovalRequest::create([
                    'leave_request_id' => $currentRequest->leave_request_id,
                    'approval_authority_id' => $nextAuthority->id,
                    'status' => 'Pending',
                    'parent_request_id' => $currentRequest->id,
                ]);

                return response()->json(['success' => true, 'message' => 'Moved to next approver']);
            }
        }

        if ($currentRequest) {
            LeaveRequest::where('id', $currentRequest->leave_request_id)->update([
                'status' => '1'
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Final approval done']);
    }

    public function pending(Request $request)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        ApprovalRequest::where('id', $request->id)->update([
            'status' => 'Rejected',
            'approved_by' => Auth::id(),
            'approved_at' => null,
        ]);

        $record = ApprovalRequest::where('id', $request->id)->first();
        if ($record) {
            LeaveRequest::where('id', $record->leave_request_id)->update([
                'status' => '2',
            ]);
        }

        return response()->json(['success' => true]);
    }

}
