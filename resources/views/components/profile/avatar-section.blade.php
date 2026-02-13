@props(['user'])
<div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Profile Picture</h3>
    <div class="flex items-center space-x-6">
        <div class="flex-shrink-0">
            @if($user->avatar)
            <img class="h-24 w-24 rounded-full object-cover border-4 border-gray-100 dark:border-gray-700" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
            @else
            <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center border-4 border-gray-100 dark:border-gray-700">
                <span class="text-2xl text-white font-bold">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
            </div>
            @endif
        </div>

        <div class="flex-1">
            <form wire:submit="updateAvatar" class="space-y-4">
                <div>
                    <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload New Picture</label>
                    <input type="file" wire:model="avatar" id="avatar" 
                        class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/20 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/30 @error('avatar') border-red-500 @enderror"
                        accept="image/jpeg,image/jpg,image/png,image/webp">
                    @error('avatar')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, or WebP up to 5MB</p>
                </div>

                <div class="flex space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-25 transition">
                        Upload Picture
                    </button>
                    
                    @if($user->avatar)
                    <button type="button" wire:click="deleteAvatar" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-25 transition">
                        Remove Picture
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
