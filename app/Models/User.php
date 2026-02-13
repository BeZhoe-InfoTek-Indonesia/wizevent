<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin IdeHelperUser
 *
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Testimonial> $testimonials
 * @property-read int|null $testimonials_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestimonialVote> $testimonialVotes
 * @property-read int|null $testimonial_votes_count
 * @property int $unread_notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Notifications\DatabaseNotification> $unreadNotifications
 * @property array|null $email_notifications
 * @property array|null $in_app_notifications
 * @property array|null $email_notifications
 * @property array|null $in_app_notifications
 *
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
 * @method bool hasEmailNotificationEnabled(string $type)
 * @method bool hasInAppNotificationEnabled(string $type)
 * @method array getDefaultNotificationPreferences()
 * @method int getUnreadNotificationsCount()
 * @method static void boot()
 *                            *
 * @mixin \Eloquent
 */
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, HasRoles, LogsActivity, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'google_id',
        'email_notifications',
        'in_app_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'email_notifications' => 'array',
            'in_app_notifications' => 'array',
        ];
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

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }

    public function testimonialVotes(): HasMany
    {
        return $this->hasMany(TestimonialVote::class);
    }

    /**
     * Determine if the user can access Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Only users with admin-level roles can access Filament
        return $this->hasAnyRole([
            'Super Admin',
            'Event Manager',
            'Finance Admin',
            'Check-in Staff',
        ]);
    }

    /**
     * Get the default notification preferences for a new user.
     */
    public static function getDefaultNotificationPreferences(): array
    {
        return [
            'email_notifications' => [
                'payment' => true,
                'events' => true,
                'loved_events' => true,
                'promotions' => true,
            ],
            'in_app_notifications' => [
                'payment' => true,
                'events' => true,
                'loved_events' => true,
                'promotions' => true,
            ],
        ];
    }

    /**
     * Check if user has email notification enabled for a specific type.
     */
    public function hasEmailNotificationEnabled(string $type): bool
    {
        $preferences = $this->email_notifications ?? self::getDefaultNotificationPreferences()['email_notifications'];

        return $preferences[$type] ?? true;
    }

    /**
     * Check if user has in-app notification enabled for a specific type.
     */
    public function hasInAppNotificationEnabled(string $type): bool
    {
        $preferences = $this->in_app_notifications ?? self::getDefaultNotificationPreferences()['in_app_notifications'];

        return $preferences[$type] ?? true;
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Boot method to set default notification preferences.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($user) {
            if (empty($user->email_notifications) || empty($user->in_app_notifications)) {
                $user->email_notifications = self::getDefaultNotificationPreferences()['email_notifications'];
                $user->in_app_notifications = self::getDefaultNotificationPreferences()['in_app_notifications'];
                $user->saveQuietly();
            }
        });
    }
}
