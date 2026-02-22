<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $bank_name
 * @property string $account_number
 * @property string $account_holder
 * @property string|null $qr_code_path
 * @property string|null $logo_path
 * @property bool $is_active
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank active()
 * @method static \Database\Factories\PaymentBankFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereAccountHolder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereLogoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereQrCodePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentBank withoutTrashed()
 * @mixin \Eloquent
 */
class PaymentBank extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'qr_code_path',
        'logo_path',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['bank_name', 'account_number', 'is_active'])
            ->logOnlyDirty();
    }
}
