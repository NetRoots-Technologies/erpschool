<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Notification;
use App\Models\ApprovalRequest;
use App\Models\HR\LeaveRequest;
use App\Models\ApprovalAuthority;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NotificationService
{
    public static function sendNotification($sender_id, $reciver_id, $title, $message, $link = null)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            Notification::insert([
                'sender_id' => $sender_id,
                'reciver_id' => $reciver_id,
                'title' => $title,
                'message' => $message,
                'is_read' => 0,
                'link' => $link,
                'created_at' => Carbon::now(),
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public static function markNotificationAsRead($notification_id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        try {
            Notification::where('id', $notification_id)->update(['is_read' => 1]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public static function getUnreadNotificationCount($user_id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Notification::where('reciver_id', $user_id)->where('is_read', 0)->count();
    }

    public static function getAllUnreadNotifications($user_id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return Notification::where('reciver_id', $user_id)->where('is_read', 0)->get();
    }

    public static function getAllNotifications($user_id)
    {
        if (!Gate::allows('students')) {
            return abort(503);
        }
        return DB::transaction(function () {
            $userId = Auth::id();


            $approvalAuthorityIds = ApprovalAuthority::where('user_id', $userId)->pluck('id');

            $approvalRequests = ApprovalRequest::with('leaveRequest.employee')
                ->whereIn('approval_authority_id', $approvalAuthorityIds)
                ->where('status', 'pending')
                ->get();

            if ($approvalRequests->isEmpty()) {
                return collect();
            }

            $notifications = $approvalRequests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'title' => 'Pending Leave Request',
                    'message' => 'Leave request from ' . ($request->leaveRequest->employee->name ?? 'Unknown') .
                        ' is pending your approval.',
                ];
            });
            return $notifications;
        });

    }

}