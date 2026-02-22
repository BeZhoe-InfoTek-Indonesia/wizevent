<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $title
 * @property string $message
 * @property \Illuminate\Support\Carbon $target_date
 * @property string|null $url
 * @property bool $is_active
 * @property string $display_location
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown active()
 * @method static \Database\Factories\PromoCountdownFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown forLocation(string $location)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereDisplayLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereTargetDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PromoCountdown withoutTrashed()
 * @mixin \Eloquent
 */
class PromoCountdown extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'message',
        'target_date',
        'url',
        'is_active',
        'display_location',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'target_date' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('target_date', '>', now());
    }

    public function scopeForLocation($query, string $location)
    {
        return $query->where('display_location', $location)
            ->orWhere('display_location', 'all');
    }

    public function isExpired(): bool
    {
        return $this->target_date->isPast();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'message', 'is_active', 'target_date'])
            ->logOnlyDirty();
    }
}
