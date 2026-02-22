@props(['user'])
<div>
    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('profile.profile_picture') }}</h3>
    <div class="flex flex-col items-center">
        <div class="relative mb-6">
            @if($user->avatar)
                <img class="h-32 w-32 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-md" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
            @else
                <div class="h-32 w-32 rounded-full bg-red-500 flex items-center justify-center border-4 border-white dark:border-gray-700 shadow-md">
                    <span class="text-4xl text-white font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                </div>
            @endif
            
            @if($user->hasVerifiedEmail())
                <div class="absolute bottom-1 right-1 bg-green-500 rounded-full p-1.5 border-4 border-white dark:border-gray-800">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            @endif
        </div>

        <form wire:submit="updateAvatar" class="w-full space-y-4">
            <div class="w-full">
                <input type="file" wire:model="avatar" id="avatar" class="hidden" accept="image/jpeg,image/jpg,image/png,image/webp">
                <label for="avatar" class="block w-full text-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer transition-colors shadow-sm">
                    {{ __('profile.choose_file') }}
                </label>
                @error('avatar')
                    <p role="alert" class="mt-1 text-sm text-red-600 dark:text-red-400 text-center">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-400 text-center">{{ __('profile.image_requirements') }}</p>
            </div>

            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-lg text-sm font-bold hover:bg-red-100 transition-colors">
                {{ __('profile.upload_picture') }}
            </button>
            
            @if($user->avatar)
                <button type="button" wire:click="deleteAvatar" class="w-full flex items-center justify-center px-4 py-2 text-gray-500 hover:text-red-600 text-sm font-medium transition-colors">
                    {{ __('profile.remove_picture') }}
                </button>
            @endif
        </form>
    </div>
</div>
