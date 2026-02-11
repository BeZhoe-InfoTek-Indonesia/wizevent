<?php

namespace App\Services;

use App\Models\FileBucket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Laravel\Facades\Image;
use InvalidArgumentException;

class FileBucketService
{
    protected array $bucketTypes = [
        'event-banners',
        'event-galleries',
        'category-icons',
        'ticket-type-images',
        'user-avatars',
        'payment-proofs',
    ];

    protected array $imageSizes = [
        'thumbnail' => [400, 300],
        'medium' => [800, 600],
        'large' => [1200, 900],
    ];

    /**
     * Upload a file and create FileBucket record
     */
    public function upload(Model $fileable, UploadedFile $file, string $bucketType, array $options = []): FileBucket
    {
        // Validate bucket type
        $this->validateBucketType($bucketType);

        // Validate file
        $this->validateFile($file, $bucketType);

        // Replace existing if requested
        if ($options['replace'] ?? false) {
            $this->deleteAll($fileable, $bucketType);
        }

        // Generate unique filename
        $storedFilename = $this->generateUniqueFilename($file);

        // Build file path
        $filePath = $this->buildFilePath($fileable, $bucketType, $storedFilename);

        // Store file
        Storage::disk('public')->put($filePath, file_get_contents($file->getRealPath()));

        // Create FileBucket record
        $fileBucket = FileBucket::create([
            'fileable_type' => get_class($fileable),
            'fileable_id' => $fileable->id,
            'bucket_type' => $bucketType,
            'collection' => $options['collection'] ?? null,
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => $storedFilename,
            'file_path' => $filePath,
            'url' => Storage::disk('public')->url($filePath),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'created_by' => auth()->id(),
        ]);

        // Process image if applicable
        if ($this->isImage($file)) {
            $this->processImage($fileBucket, $options);
        }

        // Log activity
        if (function_exists('activity')) {
            activity()
                ->performedOn($fileable)
                ->causedBy(auth()->user())
                ->withProperties(['bucket_type' => $bucketType, 'filename' => $storedFilename])
                ->log('File uploaded');
        }

        return $fileBucket;
    }

    /**
     * Upload multiple files
     */
    public function uploadMultiple(Model $fileable, array $files, string $bucketType, array $options = []): Collection
    {
        $fileBuckets = collect();

        foreach ($files as $file) {
            $fileBuckets->push($this->upload($fileable, $file, $bucketType, $options));
        }

        return $fileBuckets;
    }

    /**
     * Retrieve a single file
     */
    public function retrieve(Model $fileable, string $bucketType, ?string $size = null): ?FileBucket
    {
        return FileBucket::where('fileable_type', get_class($fileable))
            ->where('fileable_id', $fileable->id)
            ->where('bucket_type', $bucketType)
            ->latest()
            ->first();
    }

    /**
     * Retrieve all files of a bucket type
     */
    public function retrieveAll(Model $fileable, string $bucketType): Collection
    {
        return FileBucket::where('fileable_type', get_class($fileable))
            ->where('fileable_id', $fileable->id)
            ->where('bucket_type', $bucketType)
            ->latest()
            ->get();
    }

    /**
     * Delete a single file
     */
    public function delete(FileBucket $fileBucket): bool
    {
        // Delete physical files handled by model delete()
        return $fileBucket->delete();
    }

    /**
     * Delete all files of a bucket type for an entity
     */
    public function deleteAll(Model $fileable, string $bucketType): int
    {
        $fileBuckets = $this->retrieveAll($fileable, $bucketType);
        $count = 0;

        foreach ($fileBuckets as $fileBucket) {
            if ($this->delete($fileBucket)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get public URL for a file
     */
    public function getUrl(FileBucket $fileBucket, ?string $size = null): string
    {
        if ($size && isset($fileBucket->sizes[$size])) {
            $path = $fileBucket->sizes[$size];
            if (Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            return Storage::disk('public')->url($path);
        }

        if ($fileBucket->url) {
            return $fileBucket->url;
        }

        return Storage::disk('public')->url($fileBucket->file_path);
    }

    /**
     * Process image and generate sizes
     */
    public function processImage(FileBucket $fileBucket, array $options = []): void
    {
        $sizes = $options['sizes'] ?? $this->imageSizes;
        $preserveAspectRatio = $options['preserve_aspect_ratio'] ?? false;

        $fullPath = Storage::disk('public')->path($fileBucket->file_path);

        try {
            // Intervention Image v3 usage
            $image = Image::read($fullPath);

            // Store original dimensions
            $fileBucket->update([
                'width' => $image->width(),
                'height' => $image->height(),
            ]);

            // Generate sizes
            $generatedSizes = [];

            foreach ($sizes as $sizeName => [$width, $height]) {
                $sizedImage = clone $image;

                if ($preserveAspectRatio) {
                    $sizedImage->scale(width: $width, height: $height);
                } else {
                    $sizedImage->cover($width, $height);
                }

                $sizePath = $this->buildSizePath($fileBucket->file_path, $sizeName);

                $extension = strtolower(pathinfo($sizePath, PATHINFO_EXTENSION));
                $encoded = match ($extension) {
                    'png' => $sizedImage->toPng(),
                    'webp' => $sizedImage->toWebp(),
                    'gif' => $sizedImage->toGif(),
                    default => $sizedImage->toJpeg(),
                };

                Storage::disk('public')->put($sizePath, (string) $encoded);

                $generatedSizes[$sizeName] = $sizePath;
            }

            $fileBucket->update(['sizes' => $generatedSizes]);
        } catch (\Exception $e) {
            // \Illuminate\Support\Facades\Log::error('Image processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate bucket type
     */
    protected function validateBucketType(string $bucketType): void
    {
        if (! in_array($bucketType, $this->bucketTypes)) {
            throw new InvalidArgumentException("Invalid bucket type: {$bucketType}");
        }
    }

    /**
     * Validate file
     */
    protected function validateFile(UploadedFile $file, string $bucketType): void
    {
        // Validate file size (5MB max)
        if ($file->getSize() > 5 * 1024 * 1024) {
            throw ValidationException::withMessages(['file' => 'File size exceeds maximum allowed (5MB)']);
        }

        // Validate file type based on bucket type
        $allowedMimes = $this->getAllowedMimes($bucketType);

        if (! in_array($file->getMimeType(), $allowedMimes)) {
            throw ValidationException::withMessages(['file' => 'File type not supported']);
        }
    }

    /**
     * Get allowed MIME types for bucket type
     */
    protected function getAllowedMimes(string $bucketType): array
    {
        return match ($bucketType) {
            'event-banners', 'event-galleries', 'category-icons', 'ticket-type-images', 'user-avatars' => ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'],
            'payment-proofs' => ['image/jpeg', 'image/png', 'application/pdf'],
            default => ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'],
        };
    }

    /**
     * Generate unique filename
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        return Str::uuid().'.'.$file->getClientOriginalExtension();
    }

    /**
     * Build file path
     */
    protected function buildFilePath(Model $fileable, string $bucketType, string $filename): string
    {
        $fileableType = Str::plural(Str::snake(class_basename($fileable)));

        return "file-buckets/{$bucketType}/{$fileableType}/{$fileable->id}/{$filename}";
    }

    /**
     * Build size path
     */
    protected function buildSizePath(string $originalPath, string $sizeName): string
    {
        $pathInfo = pathinfo($originalPath);

        return $pathInfo['dirname'].'/'.$pathInfo['filename']."_{$sizeName}.".$pathInfo['extension'];
    }

    /**
     * Check if file is an image
     */
    protected function isImage(UploadedFile $file): bool
    {
        return Str::startsWith($file->getMimeType(), 'image/');
    }
}
