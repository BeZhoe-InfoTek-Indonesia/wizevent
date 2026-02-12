<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperTestimonial
 *
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property string $content
 * @property int $rating
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Event $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestimonialVote> $votes
 * @property-read int|null $votes_count
 * @property-read int $helpful_votes_count
 * @property-read int $not_helpful_votes_count
 *
 * @method static \Database\Factories\TestimonialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial approved()
 *
 * @mixin \Eloquent
 */
class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'content',
        'rating',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(TestimonialVote::class);
    }

    public function getHelpfulVotesCountAttribute(): int
    {
        return $this->votes()->where('is_helpful', true)->count();
    }

    public function getNotHelpfulVotesCountAttribute(): int
    {
        return $this->votes()->where('is_helpful', false)->count();
    }

    public function scopeApproved($query): void
    {
        $query->where('status', 'approved');
    }

    public function scopePending($query): void
    {
        $query->where('status', 'pending');
    }

    public function canBeVotedBy(User $user): bool
    {
        return ! $this->votes()->where('user_id', $user->id)->exists();
    }

    public function hasPurchasedTicket(User $user): bool
    {
        return Ticket::whereHas('ticketType', function ($query) {
            $query->where('event_id', $this->event_id);
        })->whereHas('orderItem.order', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 'completed');
        })->exists();
    }
}
