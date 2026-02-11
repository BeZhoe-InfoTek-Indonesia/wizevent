<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperOrder
 *
 * @property int $id
 * @property string $order_number
 * @property int $user_id
 * @property int $event_id
 * @property string $status
 * @property float $subtotal
 * @property float $discount_amount
 * @property float $tax_amount
 * @property float $total_amount
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Event $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\PaymentProof|null $paymentProof
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @property-read bool $can_be_verified
 * @property-read bool $is_expired
 * @property-read bool $is_pending_payment
 * @property-read bool $is_pending_verification
 * @property-read bool $is_completed
 * @property-read int $total_tickets
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'event_id', 'status',
        'subtotal', 'discount_amount', 'tax_amount', 'total_amount',
        'notes', 'cancellation_reason', 'expires_at', 'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $with = [
        'user', 'event', 'orderItems', 'paymentProof',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentProof(): HasOne
    {
        return $this->hasOne(PaymentProof::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(FileBucket::class, 'fileable');
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, OrderItem::class);
    }

    public function getTotalTicketsAttribute(): int
    {
        return $this->orderItems()->sum('quantity');
    }

    public function getIsPendingPaymentAttribute(): bool
    {
        return $this->status === 'pending_payment';
    }

    public function getIsPendingVerificationAttribute(): bool
    {
        return $this->status === 'pending_verification';
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getCanBeVerifiedAttribute(): bool
    {
        return $this->status === 'pending_verification' && $this->paymentProof && ! $this->is_expired;
    }

    public function generateOrderNumber(): string
    {
        return 'ORD-'.now()->format('Ymd').'-'.strtoupper(substr(uniqid(), -6));
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending_payment', 'pending_verification']);
    }

    public function scopePendingVerification($query): void
    {
        $query->where('status', 'pending_verification');
    }

    public function scopePendingPayment($query): void
    {
        $query->where('status', 'pending_payment');
    }

    public function scopeCompleted($query): void
    {
        $query->where('status', 'completed');
    }

    public function scopeExpired($query): void
    {
        $query->where('expires_at', '<', now());
    }
}
