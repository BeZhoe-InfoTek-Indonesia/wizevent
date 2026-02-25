<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'notifications' => [
                'data' => $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->data['title'] ?? 'Notification',
                        'message' => $notification->data['message'] ?? '',
                        'created_at' => $notification->created_at->format('M d, H:i:s'),
                        'is_read' => $notification->read_at !== null,
                        'link' => $notification->data['link'] ?? null,
                    ];
                })->values(),
            ],
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'per_page' => 20,
                'total' => $notifications->total(),
                'has_more' => $notifications->hasMorePages(),
            ],
        ]);
    }

    public function markAsRead(int $notificationId): \Illuminate\Http\JsonResponse
    {
        $notification = Auth::user()->notifications()->find($notificationId);

        if (! $notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ]);
        }

        $notification->markAsRead();

        session()->put('unread_notifications_count', Auth::user()->unread_notifications_count);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    public function markAllAsRead(): \Illuminate\Http\JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        session()->put('unread_notifications_count', 0);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    public function dismiss(int $notificationId): \Illuminate\Http\JsonResponse
    {
        $notification = Auth::user()->notifications()->find($notificationId);

        if (! $notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ]);
        }

        $notification->delete();

        session()->put('unread_notifications_count', Auth::user()->unread_notifications_count);

        return response()->json([
            'success' => true,
            'message' => 'Notification dismissed',
        ]);
    }
}
