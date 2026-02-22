<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $template_type
 * @property int $template_id
 * @property string $content
 * @property int $version
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\EmailTemplate|null $emailTemplate
 * @property-read \App\Models\WhatsappTemplate|null $whatsappTemplate
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereTemplateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TemplateVersion whereVersion($value)
 * @mixin \Eloquent
 */
class TemplateVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_type',
        'template_id',
        'content',
        'version',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function emailTemplate(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    public function whatsappTemplate(): BelongsTo
    {
        return $this->belongsTo(WhatsappTemplate::class, 'template_id');
    }
}
