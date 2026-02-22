<?php

namespace App\Filament\Resources\Banners\Pages;

use App\Filament\Resources\Banners\BannerResource;
use App\Models\FileBucket;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateBanner extends CreateRecord
{
    protected static string $resource = BannerResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $imagePath = $data['banner_image'] ?? null;
            unset($data['banner_image']);

            if ($imagePath) {
                $data['image_path'] = $imagePath;
            }

            $record = static::getModel()::create($data);

            if ($imagePath) {
                $exists = Storage::disk('public')->exists($imagePath);
                FileBucket::create([
                    'fileable_type'     => get_class($record),
                    'fileable_id'       => $record->id,
                    'bucket_type'       => 'banner-image',
                    'original_filename' => basename($imagePath),
                    'stored_filename'   => basename($imagePath),
                    'file_path'         => $imagePath,
                    'url'               => Storage::disk('public')->url($imagePath),
                    'mime_type'         => $exists ? Storage::disk('public')->mimeType($imagePath) : 'image/jpeg',
                    'file_size'         => $exists ? Storage::disk('public')->size($imagePath) : 0,
                    'created_by'        => auth()->id(),
                ]);
            }

            return $record;
        });
    }
}
