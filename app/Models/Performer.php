<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $phone
 * @property int|null $photo_file_bucket_id
 * @property int|null $type_setting_component_id
 * @property int|null $profession_setting_component_id
 * @property bool $is_active
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \App\Models\FileBucket|null $photo
 * @property-read \App\Models\SettingComponent|null $profession
 * @property-read \App\Models\SettingComponent|null $type
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer wherePhotoFileBucketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereProfessionSettingComponentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereTypeSettingComponentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performer withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventPlanTalent> $eventPlanTalents
 * @property-read int|null $event_plan_talents_count
 * @mixin \Eloquent
 */
class Performer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'phone',
        'photo_file_bucket_id',
        'type_setting_component_id',
        'profession_setting_component_id',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function photo(): BelongsTo
    {
        return $this->belongsTo(FileBucket::class, 'photo_file_bucket_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(SettingComponent::class, 'type_setting_component_id');
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(SettingComponent::class, 'profession_setting_component_id');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_performer');
    }

    public function eventPlanTalents(): HasMany
    {
        return $this->hasMany(EventPlanTalent::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
