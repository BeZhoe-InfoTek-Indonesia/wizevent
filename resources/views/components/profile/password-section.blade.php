<div>
    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">{{ __('profile.change_password') }}</h3>
    <form wire:submit="updatePassword" class="space-y-4">
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('profile.current_password') }}</label>
            <input type="password" wire:model="current_password" id="current_password" 
                placeholder="{{ __('profile.current_password_placeholder') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('current_password') border-red-500 @enderror"
                autocomplete="current-password"
                required>
            @error('current_password')
            <p role="alert" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('profile.new_password') }}</label>
            <input type="password" wire:model="password" id="password" 
                placeholder="{{ __('profile.new_password_placeholder') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('password') border-red-500 @enderror"
                autocomplete="new-password"
                required>
            @error('password')
            <p role="alert" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('profile.confirm_password') }}</label>
            <input type="password" wire:model="password_confirmation" id="password_confirmation" 
                placeholder="{{ __('profile.confirm_password_placeholder') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('password_confirmation') border-red-500 @enderror"
                autocomplete="new-password"
                required>
            @error('password_confirmation')
            <p role="alert" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-red-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-25 transition shadow-sm hover:shadow">
                {{ __('profile.change_password_button') }}
            </button>
        </div>
    </form>
</div>
