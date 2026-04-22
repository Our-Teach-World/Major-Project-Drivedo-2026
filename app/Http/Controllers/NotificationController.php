<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read and return a success response.
     */
    public function markAsRead(Request $request, $id)
    {
        // Try to get the notification for either a default auth user or an admin session
        $notifiable = auth()->user();

        if (!$notifiable) {
            $adminId = session('admin_id');
            if ($adminId) {
                $notifiable = Admin::find($adminId);
            }
        }

        if (!$notifiable) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $notification = $notifiable->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
}
