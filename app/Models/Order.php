<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string|null $uuid
 * @property string $order_number
 * @property int $user_id
 * @property int $event_id
 * @property string $status
 * @property numeric $subtotal
 * @property numeric $discount_amount
 * @property numeric $tax_amount
 * @property numeric $total_amount
 * @property string|null $notes
 * @property string|null $cancellation_reason
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Event $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileBucket> $files
 * @property-read int|null $files_count
 * @property-read bool $can_be_verified
 * @property-read bool $is_completed
 * @property-read bool $is_expired
 * @property-read bool $is_pending_payment
 * @property-read bool $is_pending_verification
 * @property-read int $total_tickets
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\PaymentProof|null $paymentProof
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order expired()
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order pendingPayment()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order pendingVerification()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order withoutTrashed()
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'order_number', 'user_id', 'event_id', 'status',
        'subtotal', 'discount_amount', 'tax_amount', 'total_amount',
        'notes', 'cancellation_reason', 'expires_at', 'completed_at',
    ];

    protected $casts = [
        'uuid' => 'string',
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

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->uuid)) {
                $order->uuid = (string) Str::ulid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
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

    public function canDownloadInvoice(): bool
    {
        return in_array($this->status, ['pending_payment', 'pending_verification', 'completed']);
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
