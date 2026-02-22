<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $event_id
 * @property string|null $name
 * @property string|null $description
 * @property numeric $price
 * @property int $quantity
 * @property int $sold_count
 * @property int $held_count
 * @property int $min_purchase
 * @property int $max_purchase
 * @property \Illuminate\Support\Carbon|null $sales_start_at
 * @property \Illuminate\Support\Carbon|null $sales_end_at
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read int $available_count
 * @property-read bool $is_available_for_sale
 * @property-read bool $is_sold_out
 * @property-read int $real_available_count
 * @method static \Database\Factories\TicketTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereHeldCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereMaxPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereMinPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSalesEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSalesStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSoldCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'name', 'description', 'price', 'quantity',
        'sold_count', 'held_count', 'min_purchase', 'max_purchase',
        'sales_start_at', 'sales_end_at', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sales_start_at' => 'datetime',
        'sales_end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $with = [
        // 'event',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getAvailableCountAttribute(): int
    {
        return max(0, $this->quantity - $this->sold_count - $this->held_count);
    }

    public function getRealAvailableCountAttribute(): int
    {
        return max(0, $this->quantity - $this->sold_count);
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->quantity > 0 && $this->sold_count >= $this->quantity;
    }

    public function getIsAvailableForSaleAttribute(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->sales_end_at && now()->greaterThan($this->sales_end_at)) {
             return false;
        }

        if ($this->sales_start_at && now()->lessThan($this->sales_start_at)) {
             return false;
        }

        return $this->getAvailableCountAttribute() > 0;
    }

    public function canPurchase(int $quantity): bool
    {
        if (! $this->is_available_for_sale) {
            return false;
        }

        if ($quantity < $this->min_purchase || $quantity > $this->max_purchase) {
            return false;
        }

        return $this->getAvailableCountAttribute() >= $quantity;
    }

    public function reserveTickets(int $quantity): void
    {
        DB::transaction(function () use ($quantity) {
            $this->lockForUpdate()->first();

            if ($this->canPurchase($quantity)) {
                $this->increment('held_count', $quantity);
            }
        });
    }

    public function commitTickets(int $quantity): void
    {
        DB::transaction(function () use ($quantity) {
            $this->lockForUpdate()->first();

            $this->decrement('held_count', $quantity);
            $this->increment('sold_count', $quantity);
        });
    }

    public function releaseTickets(int $quantity): void
    {
        DB::transaction(function () use ($quantity) {
            $this->lockForUpdate()->first();

            $this->decrement('held_count', $quantity);
        });
    }
}
