<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $short_description
 * @property string $description
 * @property \Illuminate\Support\Carbon $event_date
 * @property \Illuminate\Support\Carbon|null $event_end_date
 * @property string $location
 * @property string|null $city_code
 * @property string|null $venue_name
 * @property numeric|null $latitude
 * @property numeric|null $longitude
 * @property string|null $google_place_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $sales_start_at
 * @property \Illuminate\Support\Carbon|null $sales_end_at
 * @property bool $seating_enabled
 * @property int $total_capacity
 * @property string|null $cancellation_reason
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\FileBucket|null $banner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FileBucket> $banners
 * @property-read int|null $banners_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $categories
 * @property-read int|null $categories_count
 * @property-read \Laravolt\Indonesia\Models\City|null $city
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read int $available_tickets
 * @property-read array $banner_images
 * @property-read float|null $from_price
 * @property-read bool $is_draft
 * @property-read bool $is_published
 * @property-read bool $is_sold_out
 * @property-read int $sold_tickets
 * @property-read int $total_favorites
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\EventPlan|null $eventPlan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Organizer> $organizers
 * @property-read int|null $organizers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentBank> $paymentBanks
 * @property-read int|null $payment_banks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Performer> $performers
 * @property-read int|null $performers_count
 * @property-read \App\Models\SeoMetadata|null $seoMetadata
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Testimonial> $testimonials
 * @property-read int|null $testimonials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketType> $ticketTypes
 * @property-read int|null $ticket_types_count
 * @property-read \App\Models\User|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event draft()
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event past()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereEventEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereGooglePlaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSalesEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSalesStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSeatingEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTotalCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereVenueName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTrashed()
 * @property string|null $target_audience
 * @property string|null $key_activities
 * @property string|null $ai_tone_style
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $rules
 * @property-read int|null $rules_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereAiToneStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereKeyActivities($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTargetAudience($value)
 * @mixin \Eloquent
 */
class Event extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'short_description', 'description', 'target_audience', 'key_activities', 'ai_tone_style',
        'event_date', 'event_end_date',
        'location', 'city_code', 'venue_name', 'latitude', 'longitude', 'google_place_id',
        'status', 'published_at',
        'sales_start_at', 'sales_end_at', 'seating_enabled', 'total_capacity',
        'cancellation_reason', 'created_by', 'updated_by',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'city_code', 'code');
    }

    protected $casts = [
        'event_date' => 'datetime',
        'event_end_date' => 'datetime',
        'published_at' => 'datetime',
        'sales_start_at' => 'datetime',
        'sales_end_at' => 'datetime',
        'seating_enabled' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected $with = [
        'ticketTypes',
        'categories',
        'tags',
        'rules',
        'organizers',
        'performers',
        'creator',
        'updater',
        'banner',
    ];

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class, 'event_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(SettingComponent::class, 'event_setting_pivot')
            ->whereHas('setting', function ($query) {
                $query->where('key', 'event_categories');
            });
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(SettingComponent::class, 'event_setting_pivot')
            ->whereHas('setting', function ($query) {
                $query->where('key', 'event_tags');
            });
    }

    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(SettingComponent::class, 'event_setting_pivot')
            ->whereHas('setting', function ($query) {
                $query->where('key', 'terms_&_condition');
            })
            ->withPivot('description')
            ->withTimestamps();
    }

    public function organizers(): BelongsToMany
    {
        return $this->belongsToMany(Organizer::class, 'event_organizer');
    }

    public function performers(): BelongsToMany
    {
        return $this->belongsToMany(Performer::class, 'event_performer');
    }

    public function paymentBanks(): BelongsToMany
    {
        return $this->belongsToMany(PaymentBank::class, 'event_payment_bank')
            ->withTimestamps();
    }

    public function seoMetadata(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'page');
    }

    /**
     * Sync tags by passing an array of setting_component IDs.
     */
    public function syncTags(array $tagIds): void
    {
        $this->syncMasterData($tagIds, 'event_tags');
    }

    /**
     * Sync categories by passing an array of setting_component IDs.
     */
    public function syncCategories(array $categoryIds): void
    {
        $this->syncMasterData($categoryIds, 'event_categories');
    }

    /**
     * Sync rules by passing an array of setting_component IDs.
     */
    public function syncRules(array $ruleIds): void
    {
        $this->syncMasterData($ruleIds, 'terms_&_condition');
    }

    /**
     * Sync organizers by passing an array of organizer IDs.
     */
    public function syncOrganizers(array $organizerIds): void
    {
        $this->organizers()->sync($organizerIds);
    }

    /**
     * Sync performers by passing an array of performer IDs.
     */
    public function syncPerformers(array $performerIds): void
    {
        $this->performers()->sync($performerIds);
    }

    /**
     * Helper to sync specific setting components without affecting others.
     */
    protected function syncMasterData(array $ids, string $settingKey): void
    {
        $otherIds = $this->belongsToMany(SettingComponent::class, 'event_setting_pivot')
            ->whereHas('setting', function ($query) use ($settingKey) {
                $query->where('key', '!=', $settingKey);
            })
            ->pluck('setting_components.id')
            ->toArray();

        $this->belongsToMany(SettingComponent::class, 'event_setting_pivot')->sync(array_merge($otherIds, $ids));
    }

    public function getBannerImagesAttribute(): array
    {
        return $this->banners->pluck('file_path')->toArray();
    }

    public function banners(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(FileBucket::class, 'fileable')
            ->where('bucket_type', 'event-banners');
    }

    public function banner(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(FileBucket::class, 'fileable')
            ->where('bucket_type', 'event-banners')
            ->orderByDesc('id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function eventPlan(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(EventPlan::class);
    }

    public function getTotalFavoritesAttribute(): int
    {
        return $this->favorites()->count();
    }

    public function isLovedBy(User $user): bool
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function hasPurchasedTicket(User $user): bool
    {
        return $this->orders()
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending_payment', 'pending_verification', 'completed'])
            ->exists();
    }

    public function getAvailableTicketsAttribute(): int
    {
        return $this->ticketTypes()->sum('available_count');
    }

    public function getSoldTicketsAttribute(): int
    {
        return $this->ticketTypes()->sum('sold_count');
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->status === 'sold_out';
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }

    public function getIsDraftAttribute(): bool
    {
        return $this->status === 'draft';
    }

    public function getFromPriceAttribute(): ?float
    {
        $activeTicketTypes = $this->ticketTypes->filter(function ($ticketType) {
            return $ticketType->is_active && $ticketType->available_count > 0;
        });

        if ($activeTicketTypes->isEmpty()) {
            return null;
        }

        return $activeTicketTypes->min('price');
    }

    public function scopePublished($query): void
    {
        $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function scopeAvailable($query): void
    {
        $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function scopeDraft($query): void
    {
        $query->where('status', 'draft');
    }

    public function scopeUpcoming($query): void
    {
        $query->where('event_date', '>=', now())->where('status', 'published');
    }

    public function scopePast($query): void
    {
        $query->where('event_date', '<', now());
    }

    public function canBePublished(): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        if (empty($this->title) || strlen($this->title) < 5 || strlen($this->title) > 200) {
            return false;
        }

        if (empty($this->description) || strlen($this->description) < 50 || strlen($this->description) > 10000) {
            return false;
        }

        if (empty($this->event_date)) {
            return false;
        }

        if (empty($this->location)) {
            return false;
        }

        if (empty($this->banner_image)) {
            return false;
        }

        // if ($this->ticketTypes()->where('is_active', true)->count() === 0) {
        //     return false;
        // }

        return true;
    }

    public function canBeCancelled(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        return true;
    }

    public function canBeDeleted(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}
