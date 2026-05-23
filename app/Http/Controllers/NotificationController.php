<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $notifications = Notification::where('user_id', $user->id)
            ->orWhereNull('user_id') // System-wide notifications
            ->latest()
            ->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Check if user can access this notification
        if ($notification->user_id && $notification->user_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        return back()->with('success', 'Notification marked as read.');
    }
    
    /**
     * Mark all notifications as read for the user.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->orWhereNull('user_id') // System-wide notifications
            ->unread()
            ->update(['is_read' => true]);
        
        return back()->with('success', 'All notifications marked as read.');
    }
    
    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        // Check if user can access this notification
        if ($notification->user_id && $notification->user_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->delete();
        
        return back()->with('success', 'Notification deleted.');
    }
}
