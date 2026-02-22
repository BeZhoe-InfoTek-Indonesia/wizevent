@props(['user'])
<div class="glass-panel rounded-3xl shadow-float p-6 md:p-8 bg-white/80 dark:bg-gray-800/80">
    <div class="flex items-center justify-between mb-8 pl-1">
        <div class="flex items-center gap-3">
            <h3 class="text-xl font-black text-gray-900 dark:text-gray-100 tracking-tight">{{ __('profile.profile_picture') }}</h3>
        </div>
    </div>
    
    <div class="flex flex-col items-center">
        <div class="relative mb-8 group">
            <div class="p-1.5 rounded-full bg-gradient-to-tr from-red-500 to-orange-400 shadow-xl group-hover:rotate-6 transition-transform duration-500">
                @if($user->avatar)
                    <img class="h-32 w-32 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-inner" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                @else
                    <div class="h-32 w-32 rounded-full bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-inner">
                        <span class="text-4xl font-black text-gray-300 dark:text-gray-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                @endif
            </div>
            @if($user->email_verified_at)
                <div class="absolute bottom-1 right-1 bg-green-500 rounded-full p-2 border-4 border-white dark:border-gray-800 shadow-lg">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            @endif
        </div>

        <div class="w-full space-y-4">
            <div class="flex flex-col items-center text-center mb-6">
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-1">{{ __('profile.choose_file') }}</span>
                <p class="text-[9px] font-bold text-gray-400">{{ __('profile.image_requirements') }}</p>
            </div>

            <div class="space-y-3">
                <label class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-white dark:bg-gray-900 border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 group hover:scale-105 hover:border-red-300 dark:hover:border-red-700 transform">
                    <input type="file" wire:model="avatar" class="hidden">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition-colors duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span class="text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-300">{{ __('profile.upload_picture') }}</span>
                </label>

                <button wire:click="updateAvatar" class="w-full py-4 text-xs font-black uppercase tracking-widest text-green-600 hover:text-green-700 hover:bg-green-50 dark:hover:bg-green-900/10 rounded-2xl transition-all duration-200 hover:scale-105 transform active:scale-95">
                    {{ __('profile.save_picture') }}
                </button>

                @if($user->avatar)
                    <button wire:click="deleteAvatar" class="w-full py-4 text-xs font-black uppercase tracking-widest text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-2xl transition-all duration-200 hover:scale-105 transform active:scale-95">
                        {{ __('profile.remove_picture') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
