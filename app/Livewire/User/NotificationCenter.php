<?php

namespace App\Livewire\User;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app-visitor')]
class NotificationCenter extends Component
{
    use WithPagination;

    public string $filter = 'all';

    public string $search = '';

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        if (! Auth::check()) {
            redirect()->route('login');
        }
    }

    public function getNotificationsProperty()
    {
        $query = Auth::user()->notifications();

        if ($this->filter === 'all') {
            return $query->orderBy('created_at', 'desc')->paginate(20);
        } elseif ($this->filter === 'unread') {
            return $query->whereNull('read_at')->orderBy('created_at', 'desc')->paginate(20);
        } elseif ($this->filter === 'payment') {
            return $query->where('data->type', 'payment_approved')->orWhere('data->type', 'payment_rejected')->orderBy('created_at', 'desc')->paginate(20);
        } elseif ($this->filter === 'events') {
            return $query->where('data->type', 'event_update')->orWhere('data->type', 'event_cancelled')->orderBy('created_at', 'desc')->paginate(20);
        } elseif ($this->filter === 'loved_events') {
            return $query->where('data->type', 'loved_event_update')->orderBy('created_at', 'desc')->paginate(20);
        } elseif ($this->filter === 'promotions') {
            return $query->where('data->type', 'promotion')->orderBy('created_at', 'desc')->paginate(20);
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function markAsRead($notificationId): void
    {
        $notification = Auth::user()->notifications()->whereNull('read_at')->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications()->each(function ($notification) {
            $notification->markAsRead();
        });
    }

    public function dismiss($notificationId): void
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
        }
    }

    public function getNotificationTypeLabel(string $type): string
    {
        return match ($type) {
            'payment_approved', 'payment_rejected' => 'Payment',
            'event_update', 'event_cancelled' => 'Event',
            'loved_event_update' => 'Event',
            'promotion' => 'Promotion',
            default => 'Notification',
        };
    }

    public function getNotificationIcon(string $type): string
    {
        return match ($type) {
            'payment_approved' => 'check-circle',
            'payment_rejected' => 'x-circle',
            'event_update' => 'information-circle',
            'event_cancelled' => 'exclamation-circle',
            'loved_event_update' => 'heart',
            'promotion' => 'tag',
            default => 'bell',
        };
    }

    public function render(): View
    {
        return view('livewire.user.notification-center');
    }
}
