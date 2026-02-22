<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $testimonial_id
 * @property int $user_id
 * @property bool $is_helpful
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Testimonial $testimonial
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereIsHelpful($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereTestimonialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestimonialVote whereUserId($value)
 * @mixin \Eloquent
 */
class TestimonialVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'testimonial_id',
        'user_id',
        'is_helpful',
    ];

    protected $casts = [
        'is_helpful' => 'boolean',
    ];

    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
