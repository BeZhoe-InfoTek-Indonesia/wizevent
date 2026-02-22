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
 * @property string $content
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate byKey(string $key)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate byLocale(string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate default()
 * @method static \Database\Factories\WhatsappTemplateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate whereVariables($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WhatsappTemplate withoutTrashed()
 * @mixin \Eloquent
 */
class WhatsappTemplate extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'key',
        'name',
        'content',
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
            ->where('template_type', 'whatsapp')
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
            'template_type' => 'whatsapp',
            'template_id' => $this->id,
            'content' => $this->content,
            'version' => $version,
            'created_by' => auth()->id(),
        ]);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'locale', 'is_default'])
            ->logOnlyDirty();
    }
}
