<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $website
 * @property array<array-key, mixed>|null $social_media
 * @property string|null $address
 * @property int|null $logo_file_bucket_id
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read \App\Models\FileBucket|null $logo
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereLogoFileBucketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereSocialMedia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Organizer withoutTrashed()
 * @mixin \Eloquent
 */
class Organizer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'email',
        'phone',
        'website',
        'social_media',
        'address',
        'logo_file_bucket_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'social_media' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(FileBucket::class, 'logo_file_bucket_id');
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_organizer');
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
