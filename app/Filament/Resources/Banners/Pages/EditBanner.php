<?php

namespace App\Filament\Resources\Banners\Pages;

use App\Filament\Resources\Banners\BannerResource;
use App\Models\FileBucket;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['banner_image'] = $this->record->fileBucket?->file_path;
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $imagePath    = $data['banner_image'] ?? null;
            $existingPath = $record->fileBucket?->file_path;
            unset($data['banner_image']);

            if ($imagePath) {
                $data['image_path'] = $imagePath;
            }

            $record->update($data);

            if ($imagePath && $imagePath !== $existingPath) {
                $record->fileBucket?->delete();

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

