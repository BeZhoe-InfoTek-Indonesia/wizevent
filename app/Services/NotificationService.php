<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class NotificationService
{
    public const TYPE_PAYMENT_APPROVED = 'payment_approved';

    public const TYPE_PAYMENT_REJECTED = 'payment_rejected';

    public const TYPE_EVENT_UPDATE = 'event_update';

    public const TYPE_EVENT_CANCELLED = 'event_cancelled';

    public const TYPE_LOVED_EVENT_UPDATE = 'loved_event_update';

    public const TYPE_PROMOTION = 'promotion';

    /**
     * Send payment approved notification.
     */
    public function sendPaymentApproved(Order $order): void
    {
        $user = $order->user;

        if (! $user->hasEmailNotificationEnabled('payment')) {
            return;
        }

        NotificationFacade::send($user, new \App\Notifications\PaymentApprovedNotification($order));
        $this->sendInAppNotification($user, self::TYPE_PAYMENT_APPROVED, [
            'title' => 'Payment Approved',
            'message' => "Your payment for order #{$order->order_number} has been approved.",
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);
    }

    /**
     * Send payment rejected notification.
     */
    public function sendPaymentRejected(Order $order, string $reason): void
    {
        $user = $order->user;

        if (! $user->hasEmailNotificationEnabled('payment')) {
            return;
        }

        NotificationFacade::send($user, new \App\Notifications\PaymentRejectedNotification($order, $reason));
        $this->sendInAppNotification($user, self::TYPE_PAYMENT_REJECTED, [
            'title' => 'Payment Rejected',
            'message' => "Your payment for order #{$order->order_number} was rejected. Reason: {$reason}",
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Send event update notification to all ticket holders.
     */
    public function sendEventUpdate(Event $event, string $changes): void
    {
        foreach ($event->orders as $order) {
            $user = $order->user;

            if (! $user->hasEmailNotificationEnabled('events')) {
                continue;
            }

            NotificationFacade::send($user, new \App\Notifications\EventUpdatedNotification($event, $changes));
            $this->sendInAppNotification($user, self::TYPE_EVENT_UPDATE, [
                'title' => 'Event Updated',
                'message' => "Event '{$event->title}' has been updated. {$changes}",
                'event_id' => $event->id,
                'event_title' => $event->title,
                'event_slug' => $event->slug,
                'changes' => $changes,
            ]);
        }
    }

    /**
     * Send event cancelled notification to all ticket holders.
     */
    public function sendEventCancelled(Event $event, string $reason): void
    {
        foreach ($event->orders as $order) {
            $user = $order->user;

            if (! $user->hasEmailNotificationEnabled('events')) {
                continue;
            }

            NotificationFacade::send($user, new \App\Notifications\EventCancelledNotification($event, $reason));
            $this->sendInAppNotification($user, self::TYPE_EVENT_CANCELLED, [
                'title' => 'Event Cancelled',
                'message' => "Event '{$event->title}' has been cancelled. Reason: {$reason}",
                'event_id' => $event->id,
                'event_title' => $event->title,
                'cancellation_reason' => $reason,
            ]);
        }
    }

    /**
     * Send loved event update notification to all users who loved the event.
     */
    public function sendLovedEventUpdate(Event $event, string $changes): void
    {
        foreach ($event->favorites as $favorite) {
            $user = $favorite->user;

            if (! $user->hasEmailNotificationEnabled('loved_events')) {
                continue;
            }

            NotificationFacade::send($user, new \App\Notifications\LovedEventUpdateNotification($event, $changes));
            $this->sendInAppNotification($user, self::TYPE_LOVED_EVENT_UPDATE, [
                'title' => 'Loved Event Update',
                'message' => "Event '{$event->title}' you loved has been updated. {$changes}",
                'event_id' => $event->id,
                'event_title' => $event->title,
                'event_slug' => $event->slug,
                'changes' => $changes,
            ]);
        }
    }

    /**
     * Send promotional notification to targeted users.
     */
    public function sendPromotion(array $userIds, string $title, string $description, ?string $link): void
    {
        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            if (! $user->hasEmailNotificationEnabled('promotions')) {
                continue;
            }

            NotificationFacade::send($user, new \App\Notifications\PromotionNotification($title, $description, $link));
            $this->sendInAppNotification($user, self::TYPE_PROMOTION, [
                'title' => $title,
                'message' => $description,
                'promotion_link' => $link,
            ]);
        }
    }

    /**
     * Send in-app notification to user.
     */
    protected function sendInAppNotification(User $user, string $type, array $data): void
    {
        if (! $user->hasInAppNotificationEnabled($type)) {
            return;
        }

        $notification = new \App\Notifications\InAppNotification($type, $data);
        NotificationFacade::sendNow($user, $notification);
    }

    /**
     * Get notification type label.
     */
    public function getNotificationTypeLabel(string $type): string
    {
        return match ($type) {
            self::TYPE_PAYMENT_APPROVED => 'Payment',
            self::TYPE_PAYMENT_REJECTED => 'Payment',
            self::TYPE_EVENT_UPDATE => 'Event',
            self::TYPE_EVENT_CANCELLED => 'Event',
            self::TYPE_LOVED_EVENT_UPDATE => 'Event',
            self::TYPE_PROMOTION => 'Promotion',
            default => 'Notification',
        };
    }
}
