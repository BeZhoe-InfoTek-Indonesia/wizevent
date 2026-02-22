<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $payment_method
 * @property string $content
 * @property bool $is_active
 * @property string $locale
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction byLocale(string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction byMethod(string $method)
 * @method static \Database\Factories\PaymentInstructionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentInstruction withoutTrashed()
 * @mixin \Eloquent
 */
class PaymentInstruction extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'payment_method',
        'content',
        'is_active',
        'locale',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeByLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['payment_method', 'is_active', 'locale'])
            ->logOnlyDirty();
    }
}
