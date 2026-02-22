<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\FileBucket;
use App\Services\FileBucketService;
use App\Services\AiService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;
    public static bool $formActionsAreSticky = true;

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getAiAssistantActionGroup(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getAiAssistantActionGroup(): Actions\ActionGroup
    {
        return Actions\ActionGroup::make([
            $this->getAiConfigureAction(),
            $this->getAiEnhanceAction(),
            $this->getAiSeoAction(),
        ])
        ->label(__('event.labels.ai_assistant'))
        ->icon('heroicon-o-cpu-chip')
        ->color('info')
        ->button();
    }

    protected function getAiConfigureAction(): Actions\Action
    {
        return Actions\Action::make('ai_configure')
            ->label(__('event.actions.ai_configure'))
            ->icon('heroicon-o-cog-6-tooth')
            ->modalIcon('heroicon-o-cpu-chip')
            ->modalWidth('4xl')
            ->form([
                TextInput::make('target_audience')
                    ->label(__('event.labels.target_audience'))
                    ->placeholder(__('event.placeholders.target_audience'))
                    ->helperText(__('event.helper_texts.target_audience'))
                    ->default(fn () => $this->record->target_audience),

                Select::make('ai_tone_style')
                    ->label(__('event.labels.ai_tone_style'))
                    ->options([
                        'professional' => __('event.tones.professional'),
                        'casual' => __('event.tones.casual'),
                        'promotional' => __('event.tones.promotional'),
                    ])
                    ->default(fn () => $this->record->ai_tone_style),

                Textarea::make('key_activities')
                    ->label(__('event.labels.key_activities'))
                    ->placeholder(__('event.placeholders.key_activities'))
                    ->helperText(__('event.helper_texts.key_activities'))
                    ->rows(3)
                    ->default(fn () => $this->record->key_activities),
                
                RichEditor::make('ai_generated_result')
                    ->label(__('event.labels.description') . ' (AI Preview)')
                    ->columnSpanFull()
                    ->hintAction(
                        \Filament\Actions\Action::make('generate_content')
                            ->label(__('event.actions.generate_optimized_content'))
                            ->icon('heroicon-o-sparkles')
                            ->color('info')
                            ->action(function (array $data, $set) {
                                $aiService = app(AiService::class);
                                $record = $this->record;
                                $formState = $this->form->getRawState();

                                // Use form state for latest values, fallback to record
                                $title = $formState['title'] ?? $record->title;
                                $shortDesc = $formState['short_description'] ?? $record->short_description ?? '';
                                $venueName = $formState['venue_name'] ?? $record->venue_name ?? '';
                                $location = $formState['location'] ?? $record->location;
                                $eventDate = $formState['event_date'] ?? $record->event_date;

                                // Resolve relationship names from form state (IDs)
                                $categoryNames = \App\Models\SettingComponent::whereIn('id', $formState['categories'] ?? [])->pluck('name')->toArray();
                                $tagNames = \App\Models\SettingComponent::whereIn('id', $formState['tags'] ?? [])->pluck('name')->toArray();
                                $organizerNames = \App\Models\Organizer::whereIn('id', $formState['organizers'] ?? [])->pluck('name')->toArray();
                                $performerNames = \App\Models\Performer::whereIn('id', $formState['performers'] ?? [])->pluck('name')->toArray();

                                $enhancedDescription = $aiService->generateDescription([
                                    'title' => $title,
                                    'short_description' => $shortDesc,
                                    'event_date' => $eventDate,
                                    'location' => $location,
                                    'venue_name' => $venueName,
                                    'target_audience' => $data['target_audience'] ?? '',
                                    'key_activities' => $data['key_activities'] ?? '',
                                    'ai_tone_style' => $data['ai_tone_style'] ?? 'professional',
                                    'categories' => !empty($categoryNames) ? $categoryNames : $record->categories->pluck('name')->toArray(),
                                    'tags' => !empty($tagNames) ? $tagNames : $record->tags->pluck('name')->toArray(),
                                    'organizers' => !empty($organizerNames) ? $organizerNames : $record->organizers->pluck('name')->toArray(),
                                    'performers' => !empty($performerNames) ? $performerNames : $record->performers->pluck('name')->toArray(),
                                ]);

                                if ($enhancedDescription) {
                                    $set('ai_generated_result', $enhancedDescription);
                                }
                            })
                    ),
            ])
            ->modalSubmitActionLabel(__('event.actions.apply_ai_magic'))
            ->action(function (array $data) {
                if (!empty($data['ai_generated_result'])) {
                    $this->record->update([
                        'description' => $data['ai_generated_result'],
                        'target_audience' => $data['target_audience'],
                        'ai_tone_style' => $data['ai_tone_style'],
                        'key_activities' => $data['key_activities'],
                    ]);

                    $this->refreshFormData([
                        'description',
                        'target_audience',
                        'ai_tone_style',
                        'key_activities',
                    ]);

                    Notification::make()
                        ->title(__('event.notifications.ai_success'))
                        ->success()
                        ->send();
                } else {
                    $this->record->update([
                        'target_audience' => $data['target_audience'],
                        'ai_tone_style' => $data['ai_tone_style'],
                        'key_activities' => $data['key_activities'],
                    ]);

                    $this->refreshFormData([
                        'target_audience',
                        'ai_tone_style',
                        'key_activities',
                    ]);

                    Notification::make()
                        ->title('Settings saved')
                        ->success()
                        ->send();
                }
            });
    }

    protected function getAiEnhanceAction(): Actions\Action
    {
        return Actions\Action::make('ai_enhance')
            ->label(__('event.actions.ai_enhance'))
            ->icon('heroicon-o-sparkles')
            ->requiresConfirmation()
            ->action(function () {
                $record = $this->record;
                $formState = $this->form->getRawState();

                // Use form state for latest values, fallback to record
                $title = $formState['title'] ?? $record->title;
                $shortDesc = $formState['short_description'] ?? $record->short_description ?? '';
                $venueName = $formState['venue_name'] ?? $record->venue_name ?? '';
                $location = $formState['location'] ?? $record->location;
                $eventDate = $formState['event_date'] ?? $record->event_date;

                // Resolve relationship names from form state (IDs)
                $categoryNames = \App\Models\SettingComponent::whereIn('id', $formState['categories'] ?? [])->pluck('name')->toArray();
                $tagNames = \App\Models\SettingComponent::whereIn('id', $formState['tags'] ?? [])->pluck('name')->toArray();
                $organizerNames = \App\Models\Organizer::whereIn('id', $formState['organizers'] ?? [])->pluck('name')->toArray();
                $performerNames = \App\Models\Performer::whereIn('id', $formState['performers'] ?? [])->pluck('name')->toArray();

                $data = [
                    'title' => $title,
                    'short_description' => $shortDesc,
                    'event_date' => $eventDate,
                    'location' => $location,
                    'venue_name' => $venueName,
                    'target_audience' => $record->target_audience,
                    'key_activities' => $record->key_activities,
                    'ai_tone_style' => $record->ai_tone_style,
                    'categories' => !empty($categoryNames) ? $categoryNames : $record->categories->pluck('name')->toArray(),
                    'tags' => !empty($tagNames) ? $tagNames : $record->tags->pluck('name')->toArray(),
                    'organizers' => !empty($organizerNames) ? $organizerNames : $record->organizers->pluck('name')->toArray(),
                    'performers' => !empty($performerNames) ? $performerNames : $record->performers->pluck('name')->toArray(),
                ];

                $aiService = app(AiService::class);
                $enhancedDescription = $aiService->generateDescription($data);

                if ($enhancedDescription) {
                    $record->update(['description' => $enhancedDescription]);

                    $this->refreshFormData(['description']);

                    Notification::make()
                        ->title(__('event.notifications.ai_success'))
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title(__('event.notifications.ai_error'))
                        ->danger()
                        ->send();
                }
            });
    }

    protected function getAiSeoAction(): Actions\Action
    {
        return Actions\Action::make('ai_seo')
            ->label(__('event.actions.ai_seo'))
            ->icon('heroicon-o-presentation-chart-line')
            ->requiresConfirmation()
            ->action(function () {
                $record = $this->record;
                $formState = $this->form->getRawState();

                // Resolve relationship names from form state (IDs)
                $categoryNames = \App\Models\SettingComponent::whereIn('id', $formState['categories'] ?? [])->pluck('name')->toArray();
                $tagNames = \App\Models\SettingComponent::whereIn('id', $formState['tags'] ?? [])->pluck('name')->toArray();
                $organizerNames = \App\Models\Organizer::whereIn('id', $formState['organizers'] ?? [])->pluck('name')->toArray();
                $performerNames = \App\Models\Performer::whereIn('id', $formState['performers'] ?? [])->pluck('name')->toArray();

                $data = [
                    'title' => $formState['title'] ?? $record->title,
                    'description' => $formState['description'] ?? $record->description,
                    'short_description' => $formState['short_description'] ?? $record->short_description,
                    'venue_name' => $formState['venue_name'] ?? $record->venue_name,
                    'categories' => !empty($categoryNames) ? $categoryNames : $record->categories->pluck('name')->toArray(),
                    'tags' => !empty($tagNames) ? $tagNames : $record->tags->pluck('name')->toArray(),
                    'organizers' => !empty($organizerNames) ? $organizerNames : $record->organizers->pluck('name')->toArray(),
                    'performers' => !empty($performerNames) ? $performerNames : $record->performers->pluck('name')->toArray(),
                ];

                $aiService = app(AiService::class);
                $seoData = $aiService->generateSeoMetadata($data);

                if ($seoData) {
                    if ($record->seoMetadata) {
                        $record->seoMetadata->update([
                            'title' => $seoData['title'] ?? $record->seoMetadata->title,
                            'description' => $seoData['description'] ?? $record->seoMetadata->description,
                            'keywords' => $seoData['keywords'] ?? $record->seoMetadata->keywords,
                        ]);
                    } else {
                        $record->seoMetadata()->create([
                            'title' => $seoData['title'] ?? null,
                            'description' => $seoData['description'] ?? null,
                            'keywords' => $seoData['keywords'] ?? null,
                        ]);
                    }

                    // Refresh the SEO metadata form fields
                    $this->refreshFormData([
                        'seoMetadata.title',
                        'seoMetadata.description',
                        'seoMetadata.keywords',
                    ]);

                    Notification::make()
                        ->title('SEO Metadata generated successfully!')
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Failed to generate SEO metadata.')
                        ->danger()
                        ->send();
                }
            });
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load banner image paths into the form state
        $data['banner_image'] = $this->record->banners->pluck('file_path')->toArray();

        // Load SEO metadata into the form state
        if ($this->record->seoMetadata) {
            $data['seoMetadata'] = [
                'title' => $this->record->seoMetadata->title,
                'description' => $this->record->seoMetadata->description,
                'keywords' => $this->record->seoMetadata->keywords,
                'og_image' => $this->record->seoMetadata->og_image,
                'canonical_url' => $this->record->seoMetadata->canonical_url,
            ];
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $paths = $data['banner_image'] ?? [];
            unset($data['banner_image']);

            // Remove seoMetadata from data to avoid mass-assignment issues
            // and because we handle it manually
            $seoMetadataData = $data['seoMetadata'] ?? [];
            unset($data['seoMetadata']);

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

            // Create or update SEO metadata
            if (!empty($seoMetadataData)) {
                if ($record->seoMetadata) {
                    $record->seoMetadata->update($seoMetadataData);
                } else {
                    $record->seoMetadata()->create($seoMetadataData);
                }
            }

            return $record;
        });
    }
}
