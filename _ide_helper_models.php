<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property \Illuminate\Support\Carbon $event_date
 * @property \Illuminate\Support\Carbon|null $event_end_date
 * @property string $location
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\User|null $creator
 * @property-read int $available_tickets
 * @property-read string|null $banner_image
 * @property-read bool $is_draft
 * @property-read bool $is_published
 * @property-read bool $is_sold_out
 * @property-read int $sold_tickets
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $tags
 * @property-read int|null $tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketType> $ticketTypes
 * @property-read int|null $ticket_types_count
 * @property-read \App\Models\User|null $updater
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTotalCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereVenueName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperEvent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $fileable_type
 * @property int $fileable_id
 * @property string $bucket_type
 * @property array<array-key, mixed>|null $collection
 * @property string $original_filename
 * @property string $stored_filename
 * @property string $file_path
 * @property string $url
 * @property string $mime_type
 * @property int $file_size
 * @property int|null $width
 * @property int|null $height
 * @property array<array-key, mixed>|null $metadata
 * @property array<array-key, mixed>|null $sizes
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $fileable
 * @property-read string $formatted_size
 * @property-read bool $is_image
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket documents()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket images()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket ofType(string $bucketType)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereBucketType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereCollection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereFileableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereFileableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereOriginalFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereSizes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereStoredFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket whereWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FileBucket withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperFileBucket {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string $name
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SettingComponent> $components
 * @property-read int|null $components_count
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\User|null $updatedBy
 * @method static \Database\Factories\SettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Setting withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSetting {}
}

namespace App\Models{
/**
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
	#[\AllowDynamicProperties]
	class IdeHelperSettingComponent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string|null $name
 * @property string|null $description
 * @property numeric $price
 * @property int $quantity
 * @property int $sold_count
 * @property int $min_purchase
 * @property int $max_purchase
 * @property \Illuminate\Support\Carbon|null $sales_start_at
 * @property \Illuminate\Support\Carbon|null $sales_end_at
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read int $available_count
 * @property-read bool $is_available_for_sale
 * @property-read bool $is_sold_out
 * @method static \Database\Factories\TicketTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereMaxPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereMinPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSalesEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSalesStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSoldCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketType withoutTrashed()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTicketType {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $avatar
 * @property string|null $google_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

