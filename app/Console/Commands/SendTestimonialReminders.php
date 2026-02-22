<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Testimonial;
use App\Models\Ticket;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendTestimonialReminders extends Command
{
    protected $signature = 'app:send-testimonial-reminders';

    protected $description = 'Send testimonial reminder emails to checked-in attendees 24 hours after event ends';

    public function handle(NotificationService $notificationService): int
    {
        $yesterday = now()->subHours(24);
        $yesterdayEnd = $yesterday->copy()->endOfDay();

        $events = Event::where('status', 'published')
            ->where('event_date', '<=', $yesterday)
            ->where('event_date', '>=', $yesterdayEnd)
            ->with(['orders' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->get();

        $remindersSent = 0;

        foreach ($events as $event) {
            $checkedInUsers = Ticket::whereHas('ticketType', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })->whereHas('orderItem.order', function ($query) {
                $query->where('status', 'completed');
            })->whereNotNull('checked_in_at')
                ->with('orderItem.order.user')
                ->get()
                ->pluck('orderItem.order.user')
                ->unique('id');

            foreach ($checkedInUsers as $user) {
                $hasSubmitted = Testimonial::where('user_id', $user->id)
                    ->where('event_id', $event->id)
                    ->exists();

                if (! $hasSubmitted) {
                    $notificationService->sendTestimonialReminder($user, $event);
                    $remindersSent++;
                    $this->info("Sent testimonial reminder to user {$user->id} for event {$event->id}");
                }
            }
        }

        $this->info("Total testimonial reminders sent: {$remindersSent}");

        return Command::SUCCESS;
    }
}
