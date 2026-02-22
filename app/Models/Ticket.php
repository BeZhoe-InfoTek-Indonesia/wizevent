<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $ulid
 * @property int $order_item_id
 * @property int $ticket_type_id
 * @property string $ticket_number
 * @property string|null $holder_name
 * @property string $status
 * @property string|null $qr_code_content
 * @property \Illuminate\Support\Carbon|null $checked_in_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $is_active
 * @property-read bool $is_cancelled
 * @property-read bool $is_used
 * @property-read mixed $order
 * @property-read \App\Models\OrderItem $orderItem
 * @property-read \App\Models\TicketType $ticketType
 * @method static \Database\Factories\TicketFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCheckedInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereHolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereQrCodeContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereTicketNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereTicketTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUlid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ticket whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ulid', 'order_item_id', 'ticket_type_id', 'ticket_number', 'holder_name',
        'status', 'qr_code_content', 'checked_in_at',
    ];

    protected $casts = [
        'ulid' => 'string',
        'checked_in_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ticket $ticket) {
            if (empty($ticket->ulid)) {
                $ticket->ulid = (string) \Illuminate\Support\Str::ulid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

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
