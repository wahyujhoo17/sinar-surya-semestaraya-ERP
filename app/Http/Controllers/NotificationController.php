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
        try {
            // Use the custom notification model
            $notifications = auth()->user()
                ->notifications()
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Format notifications for the frontend
            $formattedNotifications = $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'link' => $notification->link,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s')
                ];
            });

            return response()->json([
                'notifications' => $formattedNotifications,
                'unread_count' => auth()->user()->notifications()->whereNull('read_at')->count()
            ]);
        } catch (\Exception $e) {
            // Return a proper JSON error response instead of HTML
            return response()->json([
                'error' => 'Failed to load notifications',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            $notification = auth()->user()
                ->notifications()
                ->findOrFail($id);

            $notification->read_at = now();
            $notification->save();

            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'unread_count' => auth()->user()->unreadNotifications()->count()
                ]);
            }

            return redirect($notification->link ?? route('notifications.index'))
                ->with('success', 'Notifikasi telah ditandai sebagai telah dibaca');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menandai notifikasi sebagai telah dibaca',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menandai notifikasi sebagai telah dibaca');
        }
    }

    public function markAllAsRead()
    {
        try {
            auth()->user()
                ->notifications()
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'unread_count' => 0
                ]);
            }

            return redirect()->route('notifications.index')
                ->with('success', 'Semua notifikasi telah ditandai sebagai telah dibaca');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menandai semua notifikasi sebagai telah dibaca',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal menandai semua notifikasi sebagai telah dibaca');
        }
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
        try {
            return response()->json([
                'count' => auth()->user()->notifications()->whereNull('read_at')->count()
            ]);
        } catch (\Exception $e) {
            // Return a proper JSON error response
            return response()->json([
                'error' => 'Failed to get unread count',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
