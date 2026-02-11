@php
    use Filament\Support\Enums\Alignment;

    $fieldWrapperView = $getFieldWrapperView();
    $id = $getId();
    $automaticallyCropImagesAspectRatio = $getAutomaticallyCropImagesAspectRatio();
    $automaticallyResizeImagesHeight = $getAutomaticallyResizeImagesHeight();
    $automaticallyResizeImagesWidth = $getAutomaticallyResizeImagesWidth();
    $isAvatar = $isAvatar();
    $isMultiple = $isMultiple();
    $key = $getKey();
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $hasImageEditor = $hasImageEditor();
    $isImageEditorExplicitlyEnabled = $isImageEditorExplicitlyEnabled();
    $hasCircleCropper = $hasCircleCropper();
    $livewireKey = $getLivewireKey();

    $alignment = $getAlignment() ?? Alignment::Start;

    if (! $alignment instanceof Alignment) {
        $alignment = filled($alignment) ? (Alignment::tryFrom($alignment) ?? $alignment) : null;
    }
@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
    label-tag="div"
>
    <div
        x-data="{
            showDeleteModal: false,
            fileToDelete: null,
            resolveDeletePromise: null,
            confirmDelete: async function() {
                this.showDeleteModal = false;
                if (this.fileToDelete) {
                    try {
                        await $wire.callSchemaComponentMethod(
                            @js($key),
                            'deleteUploadedFile',
                            { fileKey: this.fileToDelete },
                        );
                        if (this.resolveDeletePromise) this.resolveDeletePromise();
                    } catch (e) {
                         console.error(e);
                    }
                }
                this.fileToDelete = null;
                this.resolveDeletePromise = null;
            },
            cancelDelete: function() {
                this.showDeleteModal = false;
                this.fileToDelete = null;
                if (this.resolveDeletePromise) this.resolveDeletePromise(false);
                this.resolveDeletePromise = null;
            }
        }"
    >
        <div
            x-load
            x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('file-upload', 'filament/forms') }}"
            x-data="fileUploadFormComponent({
                        acceptedFileTypes: @js($getAcceptedFileTypes()),
                        automaticallyCropImagesAspectRatio: @js($automaticallyCropImagesAspectRatio),
                        automaticallyOpenImageEditorForAspectRatio: @js($getAutomaticallyOpenImageEditorForAspectRatio()),
                        automaticallyResizeImagesMode: @js($getAutomaticallyResizeImagesMode()),
                        automaticallyResizeImagesHeight: @js($automaticallyResizeImagesHeight),
                        automaticallyResizeImagesWidth: @js($automaticallyResizeImagesWidth),
                        cancelUploadUsing: (fileKey) => {
                            $wire.cancelUpload(`{{ $statePath }}.${fileKey}`)
                        },
                        canEditSvgs: @js($canEditSvgs()),
                        confirmSvgEditingMessage: @js(__('filament-forms::components.file_upload.editor.svg.messages.confirmation')),
                        deleteUploadedFileUsing: async function (fileKey) {
                             this.fileToDelete = fileKey;
                             this.showDeleteModal = true;
                             return new Promise((resolve, reject) => {
                                 this.resolveDeletePromise = resolve;
                             });
                        },
                        disabledSvgEditingMessage: @js(__('filament-forms::components.file_upload.editor.svg.messages.disabled')),
                        getUploadedFilesUsing: async () => {
                            return await $wire.callSchemaComponentMethod(
                                @js($key),
                                'getUploadedFiles',
                            )
                        },
                        hasCircleCropper: @js($hasCircleCropper),
                        hasImageEditor: @js($hasImageEditor),
                        imageEditorEmptyFillColor: @js($getImageEditorEmptyFillColor()),
                        imageEditorMode: @js($getImageEditorMode()),
                        imageEditorViewportHeight: @js($getImageEditorViewportHeight()),
                        imageEditorViewportWidth: @js($getImageEditorViewportWidth()),
                        imagePreviewHeight: @js($getImagePreviewHeight()),
                        isAvatar: @js($isAvatar),
                        isDeletable: @js($isDeletable()),
                        isDisabled: @js($isDisabled),
                        isDownloadable: @js($isDownloadable()),
                        isImageEditorExplicitlyEnabled: @js($isImageEditorExplicitlyEnabled),
                        isMultiple: @js($isMultiple),
                        isOpenable: @js($isOpenable()),
                        isPasteable: @js($isPasteable()),
                        isPreviewable: @js($isPreviewable()),
                        isReorderable: @js($isReorderable()),
                        isSvgEditingConfirmed: @js($isSvgEditingConfirmed()),
                        itemPanelAspectRatio: @js($getItemPanelAspectRatio()),
                        loadingIndicatorPosition: @js($getLoadingIndicatorPosition()),
                        locale: @js(app()->getLocale()),
                        maxFiles: @js($maxFiles = $getMaxFiles()),
                        maxFilesValidationMessage: @js($maxFiles ? trans_choice('validation.max.array', $maxFiles, ['attribute' => $getValidationAttribute(), 'max' => $maxFiles]) : null),
                        maxParallelUploads: @js($getMaxParallelUploads()),
                        maxSize: @js(($size = $getMaxSize()) ? "{$size}KB" : null),
                        mimeTypeMap: @js($getMimeTypeMap()),
                        minSize: @js(($size = $getMinSize()) ? "{$size}KB" : null),
                        panelAspectRatio: @js($getPanelAspectRatio()),
                        panelLayout: @js($getPanelLayout()),
                        placeholder: @js($getPlaceholder()),
                        removeUploadedFileButtonPosition: @js($getRemoveUploadedFileButtonPosition()),
                        removeUploadedFileUsing: async (fileKey) => {
                            return await $wire.callSchemaComponentMethod(
                                @js($key),
                                'removeUploadedFile',
                                { fileKey },
                            )
                        },
                        reorderUploadedFilesUsing: async (fileKeys) => {
                            return await $wire.callSchemaComponentMethod(
                                @js($key),
                                'reorderUploadedFiles',
                                { fileKeys },
                            )
                        },
                        shouldAppendFiles: @js($shouldAppendFiles()),
                        shouldAutomaticallyUpscaleImagesWhenResizing: @js($shouldAutomaticallyUpscaleImagesWhenResizing()),
                        shouldOrientImageFromExif: @js($shouldOrientImagesFromExif()),
                        shouldTransformImage: @js($automaticallyCropImagesAspectRatio || $automaticallyResizeImagesHeight || $automaticallyResizeImagesWidth),
                        state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
                        uploadButtonPosition: @js($getUploadButtonPosition()),
                        uploadingMessage: @js($getUploadingMessage()),
                        uploadProgressIndicatorPosition: @js($getUploadProgressIndicatorPosition()),
                        uploadUsing: (fileKey, file, success, error, progress) => {
                            $wire.upload(
                                `{{ $statePath }}.${fileKey}`,
                                file,
                                () => {
                                    success(fileKey)
                                },
                                error,
                                (progressEvent) => {
                                    progress(true, progressEvent.detail.progress, 100)
                                },
                            )
                        },
                    })"
            wire:ignore
            wire:key="{{ $livewireKey }}.{{
                substr(md5(serialize([
                    $isDisabled,
                ])), 0, 64)
            }}"
            {{
                $attributes
                    ->merge([
                        'aria-labelledby' => "{$id}-label",
                        'id' => $id,
                        'role' => 'group',
                    ], escape: false)
                    ->merge($getExtraAttributes(), escape: false)
                    ->merge($getExtraAlpineAttributes(), escape: false)
                    ->class([
                        'fi-fo-file-upload',
                        'fi-fo-file-upload-avatar' => $isAvatar,
                        ($alignment instanceof Alignment) ? "fi-align-{$alignment->value}" : $alignment,
                    ])
            }}
        >
            <div class="fi-fo-file-upload-input-ctn">
                <input
                    x-ref="input"
                    {{
                        $getExtraInputAttributeBag()
                            ->merge([
                                'aria-labelledby' => "{$id}-label",
                                'disabled' => $isDisabled,
                                'multiple' => $isMultiple,
                                'type' => 'file',
                            ], escape: false)
                    }}
                />
            </div>

            <div
                x-show="error"
                x-text="error"
                x-cloak
                class="fi-fo-file-upload-error-message"
            ></div>


            @if ($hasImageEditor && (! $isDisabled))
                <div
                    x-show="isEditorOpen"
                    x-cloak
                    x-on:click.stop=""
                    x-trap.noscroll="isEditorOpen"
                    x-on:keydown.escape.prevent.stop="closeEditor"
                    @class([
                        'fi-fo-file-upload-editor',
                        'fi-fo-file-upload-editor-circle-cropper' => $hasCircleCropper,
                        'fi-fo-file-upload-editor-crop-only' => ! $isImageEditorExplicitlyEnabled,
                    ])
                >
                    <div
                        aria-hidden="true"
                        class="fi-fo-file-upload-editor-overlay"
                    ></div>

                    <div class="fi-fo-file-upload-editor-window">
                        <div class="fi-fo-file-upload-editor-image-ctn">
                            <img
                                x-ref="editor"
                                class="fi-fo-file-upload-editor-image"
                            />
                        </div>

                        <div class="fi-fo-file-upload-editor-control-panel">
                            <div class="fi-fo-file-upload-editor-control-panel-footer">
                                 <button type="button" x-on:click.prevent="pond.imageEditEditor.oncancel" class="fi-btn">Cancel</button>
                                 <button type="button" x-on:click.prevent="saveEditor" class="fi-btn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <x-filament::modal
                id="delete-file-confirmation"
                :visible="true"
                width="sm"
                :close-by-clicking-away="false"
                x-on:close-modal.window="cancelDelete"
                x-show="showDeleteModal"
                class="fi-modal-delete-file"
            >
                <x-slot name="heading">
                    Delete event
                </x-slot>

                <x-slot name="description">
                    Are you sure you would like to do this?
                </x-slot>

                <x-slot name="icon">
                    <x-filament::icon
                        icon="heroicon-o-trash"
                        class="h-6 w-6 text-danger-600 dark:text-danger-500"
                    />
                </x-slot>
                
                 <x-slot name="iconColor">
                    danger
                </x-slot>

                <x-slot name="footer">
                    <div class="flex flex-col gap-3 sm:flex-row-reverse">
                        <x-filament::button
                            color="danger"
                            type="button"
                            x-on:click="confirmDelete"
                        >
                            Delete
                        </x-filament::button>

                        <x-filament::button
                            color="gray"
                            type="button"
                            x-on:click="cancelDelete"
                        >
                            Cancel
                        </x-filament::button>
                    </div>
                </x-slot>
            </x-filament::modal>
        </div>
    </div>
</x-dynamic-component>
