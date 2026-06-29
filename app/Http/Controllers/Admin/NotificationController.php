<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = SystemNotification::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status === 'unread') {
            $query->where('is_read', false);
        } elseif ($status === 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();
        $totalCount = SystemNotification::count();
        $unreadCount = SystemNotification::where('is_read', false)->count();

        // Mark all as read when visiting the list page
        SystemNotification::where('is_read', false)->update(['is_read' => true]);

        return view('notifications.index', compact('notifications', 'totalCount', 'unreadCount'));
    }

    public function markAllRead()
    {
        try {
            SystemNotification::where('is_read', false)->update(['is_read' => true]);
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(SystemNotification $notification)
    {
        try {
            $notification->delete();
            return redirect()->back()->with('success', 'Notification deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting notification: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:system_notifications,id'
        ]);

        try {
            SystemNotification::whereIn('id', $request->input('ids'))->delete();
            return response()->json([
                'success' => true,
                'message' => 'Selected notifications deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting notifications: ' . $e->getMessage()
            ], 500);
        }
    }
}
