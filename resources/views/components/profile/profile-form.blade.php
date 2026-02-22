<div>
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('profile.profile_information') }}</h3>
        <button type="button" class="text-gray-400 hover:text-gray-600 pointer-events-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
        </button>
    </div>
    <form wire:submit="updateProfile" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('profile.name') }}</label>
            <input type="text" wire:model="name" id="name" placeholder="{{ __('profile.name_placeholder') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('name') border-red-500 @enderror"
                required>
            @error('name')
            <p role="alert" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('profile.email') }}</label>
            <input type="email" wire:model="email" id="email" placeholder="{{ __('profile.email_placeholder') }}"
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('email') border-red-500 @enderror"
                required>
            @error('email')
            <p role="alert" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="mobile_phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('profile.mobile_phone_number') }}</label>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-6 h-4 mr-2 border border-gray-200 shadow-sm" viewBox="0 0 3 2" xmlns="http://www.w3.org/2000/svg">
                        <rect width="3" height="2" fill="white"/>
                        <rect width="3" height="1" fill="#FF0000"/>
                    </svg>
                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm font-medium">+62</span>
                </div>
                <input type="text" wire:model="mobile_phone_number" id="mobile_phone_number" placeholder="{{ __('profile.phone_placeholder') }}" required
                    class="block w-full pl-[4.5rem] rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('mobile_phone_number') border-red-500 @enderror">
            </div>
            @error('mobile_phone_number')
            <p role="alert" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="identity_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('profile.identity_number') }}</label>
            <input type="text" wire:model="identity_number" id="identity_number" placeholder="{{ __('profile.identity_number_placeholder') }}" required
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('identity_number') border-red-500 @enderror">
            @error('identity_number')
            <p role="alert" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-red-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-25 transition shadow-sm hover:shadow">
                {{ __('profile.update_profile') }}
            </button>
        </div>
    </form>
</div>
