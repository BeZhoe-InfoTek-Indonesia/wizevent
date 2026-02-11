<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    protected array $imageSizes = [
        'thumbnail' => [400, 300],
        'medium' => [800, 600],
        'large' => [1200, 900],
    ];

    public function processEventBanner(string $filePath, Event $event): array
    {
        $image = Image::make($filePath);

        $originalPath = 'storage/app/public/events/banners/'.$event->id.'/original.'.$image->extension();

        Storage::disk('public')->put($originalPath, $image->stream());

        $sizes = [];

        foreach ($this->imageSizes as $sizeName => [$width, $height]) {
            $sizedImage = clone $image;

            $sizedImage->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $sizePath = 'storage/app/public/events/banners/'.$event->id.'/'.$sizeName.'.'.$sizedImage->extension();

            Storage::disk('public')->put($sizePath, $sizedImage->encode());

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
}
