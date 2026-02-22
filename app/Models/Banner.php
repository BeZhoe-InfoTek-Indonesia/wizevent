<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $title
 * @property string $type
 * @property string $image_path
 * @property string|null $link_url
 * @property string $link_target
 * @property int $position
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property int $click_count
 * @property int $impression_count
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner byType(?string $type)
 * @method static \Database\Factories\BannerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner scheduled()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereClickCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereImpressionCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLinkTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner withoutTrashed()
 * @property-read \App\Models\FileBucket|null $fileBucket
 * @mixin \Eloquent
 */
class Banner extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'type',
        'image_path',
        'link_url',
        'link_target',
        'position',
        'is_active',
        'start_date',
        'end_date',
        'click_count',
        'impression_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function fileBucket(): MorphOne
    {
        return $this->morphOne(FileBucket::class, 'fileable')
            ->where('bucket_type', 'banner-image')
            ->orderByDesc('id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeScheduled($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('start_date')
                ->orWhere('start_date', '<=', now());
        })->where(function ($q) {
            $q->whereNull('end_date')
                ->orWhere('end_date', '>', now());
        });
    }

    public function scopeByType($query, ?string $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }

        return $query;
    }

    public function incrementClickCount(): void
    {
        $this->increment('click_count');
    }

    public function incrementImpressionCount(): void
    {
        $this->increment('impression_count');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'type', 'is_active'])
            ->logOnlyDirty();
    }
}
