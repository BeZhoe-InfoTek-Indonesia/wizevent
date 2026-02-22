<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    protected array $imageSizes = [
        'thumbnail' => [400, 300],
        'medium' => [800, 600],
        'large' => [1200, 900],
    ];

    public function processEventBanner(string $filePath, Event $event): array
    {
        $image = Image::read($filePath);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION) ?: 'jpg';

        $originalPath = 'events/banners/'.$event->id.'/original.'.$extension;

        Storage::disk('public')->put($originalPath, (string) $image->encodeByExtension($extension));

        $sizes = [];

        foreach ($this->imageSizes as $sizeName => [$width, $height]) {
            $sizedImage = Image::read($filePath);

            $sizedImage->scaleDown(width: $width, height: $height);

            $sizePath = 'events/banners/'.$event->id.'/'.$sizeName.'.'.$extension;

            Storage::disk('public')->put($sizePath, (string) $sizedImage->encodeByExtension($extension));

            $sizes[$sizeName] = $sizePath;
        }

        $event->update([
            'banner_image' => $originalPath,
            'thumbnail_400' => $sizes['thumbnail'] ?? null,
            'thumbnail_800' => $sizes['medium'] ?? null,
            'thumbnail_1200' => $sizes['large'] ?? null,
        ]);

        return [
            'original' => Storage::disk('public')->url($originalPath),
            'thumbnails' => array_map(function ($path) {
                return Storage::disk('public')->url($path);
            }, $sizes),
        ];
    }

    public function deleteEventBanner(Event $event): void
    {
        $paths = [
            $event->banner_image,
            $event->thumbnail_400,
            $event->thumbnail_800,
            $event->thumbnail_1200,
        ];

        foreach ($paths as $path) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }

        $event->update([
            'banner_image' => null,
            'thumbnail_400' => null,
            'thumbnail_800' => null,
            'thumbnail_1200' => null,
        ]);
    }

    public function processTestimonialImage(string $filePath, string $originalName, int $testimonialId): array
    {
        $disk = Storage::disk('public');
        $absolutePath = $disk->path($filePath);
        
        $image = Image::read($absolutePath);
        $originalSize = filesize($absolutePath);

        $image->orient();

        $image->scaleDown(width: 1920, height: 1080);

        $encoded = $image->toWebp(85);

        $path = 'testimonials/'.$testimonialId.'.webp';
        $disk->put($path, (string) $encoded);

        return [
            'image_path' => $path,
            'image_original_name' => $originalName,
            'image_mime_type' => 'image/webp',
            'image_width' => $image->width(),
            'image_height' => $image->height(),
            'image_file_size' => strlen((string) $encoded),
        ];
    }

    public function deleteTestimonialImage(string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    public function validateTestimonialImage(\Illuminate\Http\UploadedFile $file): array
    {
        $errors = [];

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        if (! in_array($file->getMimeType(), $allowedMimes, true)) {
            $errors[] = __('validation.mimes', ['attribute' => 'image', 'values' => 'jpeg, png, webp']);
        }

        $maxSize = 5 * 1024 * 1024;
        if ($file->getSize() > $maxSize) {
            $errors[] = __('validation.max.file', ['attribute' => 'image', 'max' => '5MB']);
        }

        try {
            $image = Image::read($file);
            if ($image->width() < 300 || $image->height() < 300) {
                $errors[] = __('testimonial.image_too_small', ['min' => '300x300']);
            }

            if ($image->width() > 4096 || $image->height() > 4096) {
                $errors[] = __('testimonial.image_too_large', ['max' => '4096x4096']);
            }
        } catch (\Exception $e) {
            $errors[] = 'Invalid image file.';
        }

        return $errors;
    }
}
