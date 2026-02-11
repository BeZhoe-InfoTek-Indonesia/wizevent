<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperFileBucket
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
 * @property-read Model|\Eloquent $fileable
 * @property-read string $formatted_size
 * @property-read bool $is_image
 * @method static Builder<static>|FileBucket documents()
 * @method static Builder<static>|FileBucket images()
 * @method static Builder<static>|FileBucket newModelQuery()
 * @method static Builder<static>|FileBucket newQuery()
 * @method static Builder<static>|FileBucket ofType(string $bucketType)
 * @method static Builder<static>|FileBucket onlyTrashed()
 * @method static Builder<static>|FileBucket query()
 * @method static Builder<static>|FileBucket whereBucketType($value)
 * @method static Builder<static>|FileBucket whereCollection($value)
 * @method static Builder<static>|FileBucket whereCreatedAt($value)
 * @method static Builder<static>|FileBucket whereCreatedBy($value)
 * @method static Builder<static>|FileBucket whereDeletedAt($value)
 * @method static Builder<static>|FileBucket whereFilePath($value)
 * @method static Builder<static>|FileBucket whereFileSize($value)
 * @method static Builder<static>|FileBucket whereFileableId($value)
 * @method static Builder<static>|FileBucket whereFileableType($value)
 * @method static Builder<static>|FileBucket whereHeight($value)
 * @method static Builder<static>|FileBucket whereId($value)
 * @method static Builder<static>|FileBucket whereMetadata($value)
 * @method static Builder<static>|FileBucket whereMimeType($value)
 * @method static Builder<static>|FileBucket whereOriginalFilename($value)
 * @method static Builder<static>|FileBucket whereSizes($value)
 * @method static Builder<static>|FileBucket whereStoredFilename($value)
 * @method static Builder<static>|FileBucket whereUpdatedAt($value)
 * @method static Builder<static>|FileBucket whereUrl($value)
 * @method static Builder<static>|FileBucket whereWidth($value)
 * @method static Builder<static>|FileBucket withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|FileBucket withoutTrashed()
 * @mixin \Eloquent
 */
class FileBucket extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::saving(function (FileBucket $fileBucket) {
            if ($fileBucket->isDirty('file_path') && $fileBucket->file_path) {
                if (Storage::disk('public')->exists($fileBucket->file_path)) {
                    $fileBucket->mime_type = Storage::disk('public')->mimeType($fileBucket->file_path);
                    $fileBucket->file_size = Storage::disk('public')->size($fileBucket->file_path);
                    
                    if (empty($fileBucket->stored_filename)) {
                         $fileBucket->stored_filename = basename($fileBucket->file_path);
                    }
                }
            }
            
            if (empty($fileBucket->created_by) && auth()->check()) {
                $fileBucket->created_by = auth()->id();
            }
        });
    }

    protected $fillable = [
        'fileable_type', 'fileable_id', 'bucket_type', 'collection',
        'original_filename', 'stored_filename', 'file_path',
        'url', 'mime_type', 'file_size', 'width', 'height',
        'metadata', 'sizes', 'created_by'
    ];

    protected $casts = [
        'metadata' => 'array',
        'sizes' => 'array',
        'collection' => 'array',
    ];

    // Polymorphic Relationship
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getUrlAttribute(?string $value): string
    {
        if ($value) {
            return $value;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    public function getSizeUrl(string $size): ?string
    {
        if (isset($this->sizes[$size])) {
            // Check if size path is a full URL or relative path
            $path = $this->sizes[$size];
            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }
            return Storage::disk('public')->url($path);
        }

        return null;
    }

    public function getIsImageAttribute(): bool
    {
        return Str::startsWith($this->mime_type, 'image/');
    }

    public function getFormattedSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($this->file_size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    // Scopes
    public function scopeOfType(Builder $query, string $bucketType): void
    {
        $query->where('bucket_type', $bucketType);
    }

    public function scopeImages(Builder $query): void
    {
        $query->where('mime_type', 'like', 'image/%');
    }

    public function scopeDocuments(Builder $query): void
    {
        $query->where('mime_type', 'not like', 'image/%');
    }

    // Business Logic
    public function delete(): bool
    {
        // Delete physical files if it's a local file (not an external URL)
        if ($this->file_path && !Str::startsWith($this->file_path, ['http://', 'https://'])) {
            Storage::disk('public')->delete($this->file_path);
            if ($this->sizes) {
                foreach ($this->sizes as $path) {
                    if (!Str::startsWith($path, ['http://', 'https://'])) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
        }
        
        return parent::delete();
    }
}
