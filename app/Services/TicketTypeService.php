<?php

namespace App\Services;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Support\Facades\DB;

class TicketTypeService
{
    public function createTicketType(Event $event, array $data): TicketType
    {
        $ticketType = TicketType::create([
            'event_id' => $event->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'min_purchase' => $data['min_purchase'] ?? 1,
            'max_purchase' => $data['max_purchase'] ?? 10,
            'sales_start_at' => $data['sales_start_at'] ?? null,
            'sales_end_at' => $data['sales_end_at'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $event->total_capacity = $event->ticketTypes()->sum('quantity');

        activity()
            ->performedOn($ticketType)
            ->causedBy(auth()->user())
            ->log('Ticket type created');

        return $ticketType;
    }

    public function updateTicketType(TicketType $ticketType, array $data): TicketType
    {
        $oldQuantity = $ticketType->quantity;

        $ticketType->update([
            'name' => $data['name'] ?? $ticketType->name,
            'description' => $data['description'] ?? $ticketType->description,
            'price' => $data['price'] ?? $ticketType->price,
            'quantity' => $data['quantity'] ?? $ticketType->quantity,
            'min_purchase' => $data['min_purchase'] ?? $ticketType->min_purchase,
            'max_purchase' => $data['max_purchase'] ?? $ticketType->max_purchase,
            'sales_start_at' => $data['sales_start_at'] ?? $ticketType->sales_start_at,
            'sales_end_at' => $data['sales_end_at'] ?? $ticketType->sales_end_at,
            'is_active' => $data['is_active'] ?? $ticketType->is_active,
            'sort_order' => $data['sort_order'] ?? $ticketType->sort_order,
        ]);

        if ($ticketType->event->total_capacity != $ticketType->event->ticketTypes()->sum('quantity')) {
            $ticketType->event->total_capacity = $ticketType->event->ticketTypes()->sum('quantity');
        }

        activity()
            ->performedOn($ticketType)
            ->causedBy(auth()->user())
            ->log('Ticket type updated');

        return $ticketType->refresh();
    }

    public function deleteTicketType(TicketType $ticketType): bool
    {
        if ($ticketType->sold_count > 0) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Cannot delete ticket type with sold tickets');
        }

        $eventTotalCapacity = $ticketType->event->ticketTypes()->sum('quantity') - $ticketType->quantity;

        $ticketType->delete();

        $ticketType->event->total_capacity = $eventTotalCapacity;

        activity()
            ->performedOn($ticketType)
            ->causedBy(auth()->user())
            ->log('Ticket type deleted');

        return true;
    }

    public function reserveTickets(TicketType $ticketType, int $quantity): void
    {
        DB::transaction(function () use ($ticketType, $quantity) {
            $ticketType->lockForUpdate()->first();

            if (! $ticketType->canPurchase($quantity)) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Insufficient ticket inventory');
            }

            $ticketType->increment('sold_count', $quantity);

            if ($ticketType->getAvailableCountAttribute() === 0) {
                $ticketType->event->update(['status' => 'sold_out']);
            }
        });
    }
}
