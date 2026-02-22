<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @method static \Database\Factories\EventTagFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTag query()
 * @mixin \Eloquent
 */
class EventTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug',
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_tag');
    }
}
