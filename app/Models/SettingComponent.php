<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperSettingComponent
 * @property int $id
 * @property int $setting_id
 * @property string $name
 * @property string $type
 * @property string|null $value
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $createdBy
 * @property mixed $typed_value
 * @property-read \App\Models\Setting $setting
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Database\Factories\SettingComponentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SettingComponent withoutTrashed()
 * @mixin \Eloquent
 */
class SettingComponent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'setting_id',
        'name',
        'type',
        'value',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];



    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getTypedValueAttribute()
    {
        return match ($this->type) {
            'string' => (string) $this->value,
            'integer' => (int) $this->value,
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            default => $this->value,
        };
    }

    public function setTypedValueAttribute($value)
    {
        return match ($this->type) {
            'string' => $this->attributes['value'] = (string) $value,
            'integer' => $this->attributes['value'] = (int) $value,
            'boolean' => $this->attributes['value'] = $value ? 'true' : 'false',
            default => $this->attributes['value'] = $value,
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->type === 'integer' && $model->value !== null && ! is_numeric($model->value)) {
                throw new \InvalidArgumentException('Value must be numeric for integer type');
            }
            if ($model->type === 'boolean') {
                $model->value = in_array(strtolower($model->value), ['true', '1', 'yes', 'on']) ? 'true' : 'false';
            }
        });
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_setting_pivot');
    }
}
