<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property int|null $event_id
 * @property string $title
 * @property string|null $description
 * @property string|null $event_category
 * @property int|null $target_audience_size
 * @property string|null $target_audience_description
 * @property numeric|null $budget_target
 * @property numeric|null $revenue_target
 * @property \Illuminate\Support\Carbon|null $event_date
 * @property string|null $location
 * @property string $status
 * @property string|null $ai_concept_result
 * @property string $concept_status
 * @property string|null $theme
 * @property string|null $tagline
 * @property string|null $narrative_summary
 * @property \Illuminate\Support\Carbon|null $concept_synced_at
 * @property array<array-key, mixed>|null $ai_budget_result
 * @property array<array-key, mixed>|null $ai_pricing_result
 * @property array<array-key, mixed>|null $ai_risk_result
 * @property string|null $notes
 * @property int $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Event|null $event
 * @property-read float $actual_net_profit
 * @property-read float $planned_net_profit
 * @property-read float $total_actual_expenses
 * @property-read float $total_actual_revenue
 * @property-read float $total_confirmed_talent_fees
 * @property-read float $total_planned_expenses
 * @property-read float $total_planned_revenue
 * @property-read float $total_planned_talent_fees
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventPlanLineItem> $lineItems
 * @property-read int|null $line_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventPlanRundown> $rundowns
 * @property-read int|null $rundowns_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventPlanTalent> $talents
 * @property-read int|null $talents_count
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan archived()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan draft()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereAiBudgetResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereAiConceptResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereAiPricingResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereAiRiskResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereBudgetTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereConceptStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereConceptSyncedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereEventCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereNarrativeSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereRevenueTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTagline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTargetAudienceDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTargetAudienceSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventPlan withoutTrashed()
 * @mixin \Eloquent
 */
class EventPlan extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'event_id',
        'title',
        'description',
        'event_category',
        'target_audience_size',
        'target_audience_description',
        'budget_target',
        'revenue_target',
        'event_date',
        'location',
        'status',
        'ai_concept_result',
        'concept_status',
        'theme',
        'tagline',
        'narrative_summary',
        'concept_synced_at',
        'ai_budget_result',
        'ai_pricing_result',
        'ai_risk_result',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'budget_target' => 'decimal:2',
        'revenue_target' => 'decimal:2',
        'concept_synced_at' => 'datetime',
        'ai_budget_result' => 'array',
        'ai_pricing_result' => 'array',
        'ai_risk_result' => 'array',
    ];

    protected $with = [
        'event',
        'creator',
        'updater',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(EventPlanLineItem::class);
    }

    public function talents(): HasMany
    {
        return $this->hasMany(EventPlanTalent::class);
    }

    public function rundowns(): HasMany
    {
        return $this->hasMany(EventPlanRundown::class)->orderBy('sort_order')->orderBy('start_time');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getTotalPlannedExpensesAttribute(): float
    {
        return (float) $this->lineItems()
            ->where('type', 'expense')
            ->sum('planned_amount');
    }

    public function getTotalPlannedRevenueAttribute(): float
    {
        return (float) $this->lineItems()
            ->where('type', 'revenue')
            ->sum('planned_amount');
    }

    public function getTotalActualExpensesAttribute(): float
    {
        return (float) $this->lineItems()
            ->where('type', 'expense')
            ->whereNotNull('actual_amount')
            ->sum('actual_amount');
    }

    public function getTotalActualRevenueAttribute(): float
    {
        return (float) $this->lineItems()
            ->where('type', 'revenue')
            ->whereNotNull('actual_amount')
            ->sum('actual_amount');
    }

    public function getPlannedNetProfitAttribute(): float
    {
        return $this->total_planned_revenue - $this->total_planned_expenses;
    }

    public function getActualNetProfitAttribute(): float
    {
        return $this->total_actual_revenue - $this->total_actual_expenses;
    }

    public function scopeDraft($query): void
    {
        $query->where('status', 'draft');
    }

    public function getTotalPlannedTalentFeesAttribute(): float
    {
        return (float) $this->talents()->sum('planned_fee');
    }

    public function getTotalConfirmedTalentFeesAttribute(): float
    {
        return (float) $this->talents()->where('contract_status', 'confirmed')->sum('planned_fee');
    }

    public function scopeActive($query): void
    {
        $query->where('status', 'active');
    }

    public function scopeCompleted($query): void
    {
        $query->where('status', 'completed');
    }

    public function scopeArchived($query): void
    {
        $query->where('status', 'archived');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
