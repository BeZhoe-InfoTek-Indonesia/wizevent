<div class="glass-panel rounded-3xl shadow-float p-6 md:p-8 bg-white/80 dark:bg-gray-800/80">
    <div class="flex items-center justify-between mb-8 pl-1">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-red-600 shadow-sm border border-red-100 dark:border-red-800/50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 dark:text-gray-100 tracking-tight">{{ __('profile.change_password') }}</h3>
        </div>
        <div class="p-2 rounded-xl glass-card shadow-inner opacity-50">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
    </div>
    
    <form wire:submit="updatePassword" class="space-y-8">
        <div class="space-y-2">
            <label for="current_password" class="block text-xs font-black uppercase tracking-widest text-gray-400 ml-1">{{ __('profile.current_password') }}</label>
            <input type="password" wire:model="current_password" id="current_password" 
                placeholder="{{ __('profile.current_password_placeholder') }}"
                class="block w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all px-5 py-3.5 sm:text-sm @error('current_password') border-red-500 @enderror"
                autocomplete="current-password"
                required>
            @error('current_password')
            <p role="alert" class="mt-1 text-xs font-bold text-red-600 dark:text-red-400 pl-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label for="password" class="block text-xs font-black uppercase tracking-widest text-gray-400 ml-1">{{ __('profile.new_password') }}</label>
                <input type="password" wire:model="password" id="password" 
                    placeholder="{{ __('profile.new_password_placeholder') }}"
                    class="block w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all px-5 py-3.5 sm:text-sm @error('password') border-red-500 @enderror"
                    autocomplete="new-password"
                    required>
                @error('password')
                <p role="alert" class="mt-1 text-xs font-bold text-red-600 dark:text-red-400 pl-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password_confirmation" class="block text-xs font-black uppercase tracking-widest text-gray-400 ml-1">{{ __('profile.confirm_password') }}</label>
                <input type="password" wire:model="password_confirmation" id="password_confirmation" 
                    placeholder="{{ __('profile.confirm_password_placeholder') }}"
                    class="block w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all px-5 py-3.5 sm:text-sm"
                    autocomplete="new-password"
                    required>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="px-12 py-4 bg-red-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-red-700 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-red-500/20 hover:scale-105 transform">
                {{ __('profile.change_password_button') }}
            </button>
        </div>
    </form>
</div>
