<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6 sm:p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('testimonial.submit_title') }}</h1>
            <p class="text-gray-600 mb-6">{{ __('testimonial.submit_description', ['event' => $event->title]) }}</p>

            <form wire:submit="submit">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        {{ __('testimonial.rating_label') }}
                    </label>
                    <div class="flex items-center space-x-2">
                        @foreach($this->stars as $star)
                            <button type="button"
                                    wire:click="$set('rating', {{ $star }})"
                                    class="text-3xl transition-colors duration-200 focus:outline-none {{ $rating >= $star ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-300' }}">
                                ★
                            </button>
                        @endforeach
                        <span class="ml-3 text-sm text-gray-600">{{ $rating }}/5</span>
                    </div>
                    @error('rating')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('testimonial.content_label') }}
                    </label>
                    <textarea
                        id="content"
                        wire:model.live="content"
                        rows="5"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="{{ __('testimonial.content_placeholder') }}"
                    ></textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ Str::length($content) }} / 1000
                    </p>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('testimonial.image_label') }} <span class="text-gray-400">({{ __('testimonial.optional') }})</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            @if($imagePreviewUrl)
                                <div class="relative inline-block">
                                    <img src="{{ $imagePreviewUrl }}" class="h-48 w-auto rounded-lg object-cover">
                                    <button type="button"
                                            wire:click="$set('image', null); $set('imagePreviewUrl', '')"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>{{ __('testimonial.upload_button') }}</span>
                                        <input id="image" type="file" wire:model="image" class="sr-only" accept="image/jpeg,image/png,image/webp">
                                    </label>
                                    <p class="pl-1">{{ __('testimonial.or_drag_drop') }}</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    {{ __('testimonial.file_requirements', ['size' => '5MB', 'formats' => 'JPEG, PNG, WEBP']) }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('events.show', ['slug' => $event->slug]) }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('testimonial.cancel_button') }}
                    </a>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove>{{ __('testimonial.submit_button') }}</span>
                        <span wire:loading>{{ __('testimonial.submitting') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($showSuccessModal)
        <x-modal>
            <div class="bg-white rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900">{{ __('testimonial.success_title') }}</h3>
                </div>
                <p class="text-sm text-gray-500 mb-6">
                    {{ __('testimonial.success_message') }}
                </p>
                <div class="flex justify-end">
                    <button wire:click="closeSuccessModal"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('testimonial.close_button') }}
                    </button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
