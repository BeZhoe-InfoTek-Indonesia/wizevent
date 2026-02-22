<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $page_type
 * @property int $page_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $keywords
 * @property string|null $og_image
 * @property string|null $canonical_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read Model|\Eloquent $page
 * @method static \Database\Factories\SeoMetadataFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata forPage(string $type, int $id)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereCanonicalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereOgImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata wherePageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SeoMetadata whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeoMetadata extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'page_type',
        'page_id',
        'title',
        'description',
        'keywords',
        'og_image',
        'canonical_url',
    ];

    public function page(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeForPage($query, string $type, int $id)
    {
        return $query->where('page_type', $type)->where('page_id', $id);
    }

    /**
     * Resolve og_image to an absolute URL regardless of whether it was stored
     * as a relative storage path (from Filament FileUpload) or already as an
     * absolute URL.
     */
    protected function ogImage(): Attribute
    {
        return Attribute::make(
            get: function (?string $value): ?string {
                if (empty($value)) {
                    return null;
                }

                // Already an absolute URL — return as-is.
                if (filter_var($value, FILTER_VALIDATE_URL)) {
                    return $value;
                }

                // Relative path stored by Filament FileUpload — resolve via Storage.
                return Storage::disk('public')->url($value);
            }
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'description', 'keywords'])
            ->logOnlyDirty();
    }
}
