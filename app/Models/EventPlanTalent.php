<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\EventPlanLineItem|null $budgetLineItem
 * @property-read \App\Models\EventPlan|null $eventPlan
 * @property-read string $contract_status_color
 * @property-read \App\Models\Performer|null $performer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanTalent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanTalent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlanTalent query()
 * @mixin \Eloquent
 */
class EventPlanTalent extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'event_plan_talents';

    protected $fillable = [
        'event_plan_id',
        'performer_id',
        'planned_fee',
        'actual_fee',
        'slot_time',
        'slot_duration_minutes',
        'performance_order',
        'contract_status',
        'rider_notes',
        'notes',
        'budget_line_item_id',
    ];

    protected $casts = [
        'planned_fee' => 'decimal:2',
        'actual_fee' => 'decimal:2',
        'slot_duration_minutes' => 'integer',
        'performance_order' => 'integer',
    ];

    public function eventPlan(): BelongsTo
    {
        return $this->belongsTo(EventPlan::class);
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(Performer::class);
    }

    public function budgetLineItem(): BelongsTo
    {
        return $this->belongsTo(EventPlanLineItem::class, 'budget_line_item_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    /**
     * Get the contract status badge color.
     */
    public function getContractStatusColorAttribute(): string
    {
        return match ($this->contract_status) {
            'draft' => 'gray',
            'negotiating' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }
}
