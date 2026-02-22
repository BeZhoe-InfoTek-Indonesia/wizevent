<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property array<array-key, mixed> $content
 * @property string $status
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $og_image
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\SeoMetadata|null $seoMetadata
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage draft()
 * @method static \Database\Factories\CmsPageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereOgImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CmsPage withoutTrashed()
 * @mixin \Eloquent
 */
class CmsPage extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'seo_title',
        'seo_description',
        'og_image',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'content' => 'array',
        'published_at' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'page');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'status'])
            ->logOnlyDirty();
    }
}
