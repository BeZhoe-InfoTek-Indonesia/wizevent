<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property string $content
 * @property int $rating
 * @property string $status
 * @property bool $is_published
 * @property bool $is_featured
 * @property string|null $image_path
 * @property string|null $image_original_name
 * @property string|null $image_mime_type
 * @property int|null $image_width
 * @property int|null $image_height
 * @property int|null $image_file_size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read int $helpful_votes_count
 * @property-read int $not_helpful_votes_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestimonialVote> $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial featured()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial notPublished()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial pending()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageOriginalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereImageWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereIsPublished($value)
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
        'is_published',
        'is_featured',
        'image_path',
        'image_original_name',
        'image_mime_type',
        'image_width',
        'image_height',
        'image_file_size',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'image_width' => 'integer',
        'image_height' => 'integer',
        'image_file_size' => 'integer',
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

    public function scopePublished($query): void
    {
        $query->where('is_published', true);
    }

    public function scopeFeatured($query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeNotPublished($query): void
    {
        $query->where('is_published', false);
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

    public function userHasCheckedIn(User $user): bool
    {
        return Ticket::whereHas('ticketType', function ($query) {
            $query->where('event_id', $this->event_id);
        })->whereHas('orderItem.order', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 'completed');
        })->whereNotNull('checked_in_at')->exists();
    }
}
