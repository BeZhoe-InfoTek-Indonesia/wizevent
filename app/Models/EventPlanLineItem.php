<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int $event_plan_id
 * @property string $category
 * @property string|null $description
 * @property string $type
 * @property numeric $planned_amount
 * @property numeric|null $actual_amount
 * @property string|null $notes
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\EventPlan $eventPlan
 * @property-read float $variance
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem expense()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem revenue()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereActualAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereEventPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem wherePlannedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanLineItem withoutTrashed()
 * @mixin \Eloquent
 */
class EventPlanLineItem extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'event_plan_id',
        'category',
        'description',
        'type',
        'planned_amount',
        'actual_amount',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'planned_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    protected $with = [
        'eventPlan',
    ];

    public function eventPlan(): BelongsTo
    {
        return $this->belongsTo(EventPlan::class);
    }

    public function getVarianceAttribute(): float
    {
        if ($this->actual_amount === null) {
            return 0.0;
        }

        return (float) ($this->planned_amount - $this->actual_amount);
    }

    public function scopeExpense($query): void
    {
        $query->where('type', 'expense');
    }

    public function scopeRevenue($query): void
    {
        $query->where('type', 'revenue');
    }

    public function scopeOrdered($query): void
    {
        $query->orderBy('sort_order');
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
