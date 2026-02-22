<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Faq> $faqs
 * @property-read int|null $faqs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory active()
 * @method static \Database\Factories\FaqCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqCategory withoutTrashed()
 * @mixin \Eloquent
 */
class FaqCategory extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class, 'category_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug', 'is_active'])
            ->logOnlyDirty();
    }
}
