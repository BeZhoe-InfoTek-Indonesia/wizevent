<div class="space-y-8">
    <div class="flex items-center justify-between pl-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-red-600 shadow-sm border border-red-100 dark:border-red-800/50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 dark:text-gray-100 tracking-tight">{{ __('profile.profile_information') }}</h3>
        </div>
        <div class="p-2 rounded-xl glass-card shadow-inner opacity-50">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
            </svg>
        </div>
    </div>

    <form wire:submit="updateProfile" class="space-y-8">
        <!-- Basic Information Card -->
        <div class="glass-panel rounded-3xl shadow-float p-6 md:p-8 bg-white/80 dark:bg-gray-800/80">
            <div class="flex items-center gap-3 mb-8">
                <span class="text-xs font-black uppercase tracking-widest text-gray-400">{{ __('profile.basic_info') }}</span>
                <div class="h-px flex-1 bg-gray-100 dark:bg-gray-700/50"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-black uppercase tracking-widest text-gray-400 ml-1">{{ __('profile.name') }}</label>
                    <input type="text" wire:model="name" id="name" placeholder="{{ __('profile.name_placeholder') }}"
                        class="block w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all px-5 py-3.5 sm:text-sm @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                    <p role="alert" class="mt-1 text-xs font-bold text-red-600 dark:text-red-400 pl-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-xs font-black uppercase tracking-widest text-gray-400 ml-1">{{ __('profile.email') }}</label>
                    <input type="email" wire:model="email" id="email" placeholder="{{ __('profile.email_placeholder') }}"
                        class="block w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all px-5 py-3.5 sm:text-sm @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                    <p role="alert" class="mt-1 text-xs font-bold text-red-600 dark:text-red-400 pl-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Identity & Verification Card -->
        <div class="glass-panel rounded-3xl shadow-float p-6 md:p-8 bg-white/80 dark:bg-gray-800/80">
            <div class="flex items-center gap-3 mb-8">
                <span class="text-xs font-black uppercase tracking-widest text-gray-400">{{ __('profile.identity_verification') }}</span>
                <div class="h-px flex-1 bg-gray-100 dark:bg-gray-700/50"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label for="mobile_phone_number" class="block text-xs font-black uppercase tracking-widest text-gray-400 ml-1">{{ __('profile.mobile_phone_number') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                            <div class="flex items-center gap-2 pr-4 border-r border-gray-100 dark:border-gray-700">
                                <svg class="w-5 h-3.5 shadow-sm" viewBox="0 0 3 2" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="3" height="2" fill="white"/>
                                    <rect width="3" height="1" fill="#FF0000"/>
                                </svg>
                                <span class="text-gray-500 dark:text-gray-400 text-xs font-black tracking-tighter">+62</span>
                            </div>
                        </div>
                        <input type="text" wire:model="mobile_phone_number" id="mobile_phone_number" placeholder="{{ __('profile.phone_placeholder') }}" required
                            class="block w-full pl-[6rem] rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all py-3.5 sm:text-sm @error('mobile_phone_number') border-red-500 @enderror">
                    </div>
                    @error('mobile_phone_number')
                    <p role="alert" class="mt-1 text-xs font-bold text-red-600 dark:text-red-400 pl-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="identity_number" class="block text-xs font-black uppercase tracking-widest text-gray-400 ml-1">{{ __('profile.identity_number') }}</label>
                    <input type="text" wire:model="identity_number" id="identity_number" placeholder="{{ __('profile.identity_number_placeholder') }}" required
                        class="block w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900/50 dark:text-gray-100 shadow-skeuo-inset focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all px-5 py-3.5 sm:text-sm @error('identity_number') border-red-500 @enderror">
                    @error('identity_number')
                    <p role="alert" class="mt-1 text-xs font-bold text-red-600 dark:text-red-400 pl-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="w-full flex justify-center md:justify-end pt-4">
            <button type="submit"
                class="w-full md:w-auto px-8 md:px-12 py-3 md:py-4 bg-red-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-red-700 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-red-500/20 hover:scale-105 transform focus:outline-none focus:ring-2 focus:ring-red-500/50">
                {{ __('profile.update_profile') }}
            </button>
        </div>
    </form>
</div>
