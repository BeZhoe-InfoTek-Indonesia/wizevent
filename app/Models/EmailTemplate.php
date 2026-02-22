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
 * @property string $key
 * @property string $name
 * @property string $subject
 * @property string $html_content
 * @property string|null $text_content
 * @property array<array-key, mixed>|null $variables
 * @property string $locale
 * @property bool $is_default
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TemplateVersion> $versions
 * @property-read int|null $versions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate byKey(string $key)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate byLocale(string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate default()
 * @method static \Database\Factories\EmailTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereHtmlContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereTextContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereVariables($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate withoutTrashed()
 * @mixin \Eloquent
 */
class EmailTemplate extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'key',
        'name',
        'subject',
        'html_content',
        'text_content',
        'variables',
        'locale',
        'is_default',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_default' => 'boolean',
    ];

    public function versions(): HasMany
    {
        return $this->hasMany(TemplateVersion::class, 'template_id')
            ->where('template_type', 'email')
            ->orderBy('version', 'desc');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    public function createVersion(): TemplateVersion
    {
        $latestVersion = $this->versions()->first();
        $version = ($latestVersion->version ?? 0) + 1;

        return TemplateVersion::create([
            'template_type' => 'email',
            'template_id' => $this->id,
            'content' => $this->html_content,
            'version' => $version,
            'created_by' => auth()->id(),
        ]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'subject', 'locale', 'is_default'])
            ->logOnlyDirty();
    }
}
