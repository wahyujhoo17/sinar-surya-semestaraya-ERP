<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function getLatest()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => auth()->user()->unreadNotifications()->count()
        ]);
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => auth()->user()->unreadNotifications()->count()
            ]);
        }

        return redirect($notification->data['link'] ?? route('notifications.index'))
            ->with('success', 'Notifikasi telah ditandai sebagai telah dibaca');
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications
            ->markAsRead();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => 0
            ]);
        }

        return redirect()->route('notifications.index')
            ->with('success', 'Semua notifikasi telah ditandai sebagai telah dibaca');
    }

    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi telah dihapus'
            ]);
        }

        return redirect()->route('notifications.index')
            ->with('success', 'Notifikasi telah dihapus');
    }

    public function getUnreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count()
        ]);
    }
}