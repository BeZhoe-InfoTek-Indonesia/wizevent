<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\FileBucket;
use App\Services\FileBucketService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Remove banner_image from data to avoid mass-assignment issues if not in fillable
            // and because we handle it manually
            $bannerData = $data['banner_image'] ?? [];
            unset($data['banner_image']);

            $record = static::getModel()::create($data);

            if (!empty($bannerData)) {
                $service = app(FileBucketService::class);
                
                $paths = is_array($bannerData) 
                    ? $bannerData 
                    : [$bannerData];

                foreach ($paths as $path) {
                    $exists = Storage::disk('public')->exists($path);

                    $fileBucket = FileBucket::create([
                        'fileable_type' => get_class($record),
                        'fileable_id' => $record->id,
                        'bucket_type' => 'event-banners',
                        'original_filename' => basename($path),
                        'stored_filename' => basename($path),
                        'file_path' => $path,
                        'url' => Storage::disk('public')->url($path),
                        'mime_type' => $exists ? Storage::disk('public')->mimeType($path) : 'application/octet-stream',
                        'file_size' => $exists ? Storage::disk('public')->size($path) : 0,
                        'created_by' => auth()->id(),
                    ]);

                    if ($exists) {
                        $service->processImage($fileBucket);
                    }
                }
            }

            return $record;
        });
    }
}
