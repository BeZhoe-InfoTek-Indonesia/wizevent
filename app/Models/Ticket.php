<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTicket
 *
 * @property int $id
 * @property int $order_item_id
 * @property int $ticket_type_id
 * @property string $ticket_number
 * @property string|null $holder_name
 * @property string $status
 * @property string|null $qr_code_content
 * @property \Illuminate\Support\Carbon|null $checked_in_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OrderItem $orderItem
 * @property-read \App\Models\TicketType $ticketType
 * @property-read bool $is_active
 * @property-read bool $is_used
 * @property-read bool $is_cancelled
 */
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id', 'ticket_type_id', 'ticket_number', 'holder_name',
        'status', 'qr_code_content', 'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function getOrderAttribute()
    {
        return $this->orderItem?->order;
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getIsUsedAttribute(): bool
    {
        return $this->status === 'used';
    }

    public function getIsCancelledAttribute(): bool
    {
        return $this->status === 'cancelled';
    }

    public function generateTicketNumber(int $orderId): string
    {
        return 'TKT-'.$orderId.'-'.strtoupper(substr(uniqid(), -8));
    }

    public function canBeUsed(): bool
    {
        return $this->status === 'active';
    }

    public function markAsUsed(): void
    {
        $this->update(['status' => 'used', 'checked_in_at' => now()]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
