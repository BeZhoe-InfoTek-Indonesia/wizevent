<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPaymentProof
 *
 * @property int $id
 * @property int $order_id
 * @property int $file_bucket_id
 * @property string $status
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\FileBucket $fileBucket
 * @property-read \App\Models\User|null $verifier
 * @property-read bool $is_pending
 * @property-read bool $is_approved
 * @property-read bool $is_rejected
 */
class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'file_bucket_id', 'status', 'rejection_reason',
        'verified_at', 'verified_by',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function fileBucket(): BelongsTo
    {
        return $this->belongsTo(FileBucket::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === 'rejected';
    }

    public function canBeVerified(): bool
    {
        return $this->status === 'pending' && $this->order && $this->order->can_be_verified;
    }

    public function approve(int $verifierId): void
    {
        $this->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => $verifierId,
        ]);
    }

    public function reject(int $verifierId, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'verified_at' => now(),
            'verified_by' => $verifierId,
        ]);
    }
}
