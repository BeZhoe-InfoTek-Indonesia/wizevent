<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $event_plan_id
 * @property string $title
 * @property string|null $description
 * @property string $start_time
 * @property string $end_time
 * @property string $type
 * @property int|null $event_plan_talent_id
 * @property string|null $notes
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\EventPlan $eventPlan
 * @property-read int $duration_minutes
 * @property-read string $type_color
 * @property-read \App\Models\EventPlanTalent|null $talent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereEventPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereEventPlanTalentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanRundown whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventPlanRundown extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'event_plan_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'type',
        'event_plan_talent_id',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function eventPlan(): BelongsTo
    {
        return $this->belongsTo(EventPlan::class);
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(EventPlanTalent::class, 'event_plan_talent_id');
    }

    /**
     * Scope to order by sort_order then start_time.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('start_time');
    }

    /**
     * Get the type badge color.
     */
    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'ceremony' => 'warning',
            'performance' => 'purple',
            'break' => 'gray',
            'setup' => 'info',
            'networking' => 'success',
            'registration' => 'teal',
            default => 'secondary',
        };
    }

    /**
     * Calculate duration in minutes from start_time and end_time.
     */
    public function getDurationMinutesAttribute(): int
    {
        if (! $this->start_time || ! $this->end_time) {
            return 0;
        }

        $start = \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time);

        return (int) $start->diffInMinutes($end);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
