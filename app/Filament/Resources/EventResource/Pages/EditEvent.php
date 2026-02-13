<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\FileBucket;
use App\Services\FileBucketService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;
    public static bool $formActionsAreSticky = true;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load banner image paths into the form state
        $data['banner_image'] = $this->record->banners->pluck('file_path')->toArray();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $paths = $data['banner_image'] ?? [];
            unset($data['banner_image']);
            $record->update($data);

            if (!is_array($paths)) {
                $paths = $paths ? [$paths] : [];
            }

            $service = app(FileBucketService::class);
            $existingBuckets = FileBucket::where('fileable_type', get_class($record))
                ->where('fileable_id', $record->id)
                ->where('bucket_type', 'event-banners')
                ->get();

            $existingPaths = $existingBuckets->pluck('file_path')->toArray();

            // 1. Delete buckets that are no longer in the paths array
            $existingBuckets->filter(function (FileBucket $bucket) use ($paths) {
                return !in_array($bucket->file_path, $paths);
            })->each(function (FileBucket $bucket) {
                $bucket->delete();
            });

            // 2. Update or Create buckets for paths
            foreach ($paths as $path) {
                $exists = Storage::disk('public')->exists($path);

                $mimeType = $exists ? Storage::disk('public')->mimeType($path) : 'application/octet-stream';
                $fileSize = $exists ? Storage::disk('public')->size($path) : 0;
                $url = Storage::disk('public')->url($path);

                $bucket = $existingBuckets->firstWhere('file_path', $path);

                if ($bucket) {
                    $bucket->update([
                        'mime_type' => $mimeType,
                        'file_size' => $fileSize,
                        'url' => $url,
                    ]);
                } else {
                    $bucket = FileBucket::create([
                        'fileable_type' => get_class($record),
                        'fileable_id' => $record->id,
                        'bucket_type' => 'event-banners',
                        'original_filename' => basename($path),
                        'stored_filename' => basename($path),
                        'file_path' => $path,
                        'url' => $url,
                        'mime_type' => $mimeType,
                        'file_size' => $fileSize,
                        'created_by' => auth()->id(),
                    ]);
                }

                // Generate thumbnails
                if ($exists) {
                    $service->processImage($bucket);
                }
            }

            return $record;
        });
    }
}
