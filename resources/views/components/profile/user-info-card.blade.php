@props(['user'])
<div class="glass-panel rounded-3xl shadow-float p-6 mb-6 text-center border border-white/20">
    <div class="relative inline-block mb-4 group">
        @if($user->avatar)
            <div class="p-1 rounded-full bg-gradient-to-tr from-red-500 to-orange-400 shadow-lg group-hover:rotate-6 transition-transform">
                <img class="h-20 w-20 rounded-full object-cover mx-auto border-4 border-white dark:border-gray-800 shadow-inner" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
            </div>
        @else
            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center mx-auto border-4 border-white dark:border-gray-800 shadow-skeuo group-hover:rotate-6 transition-transform">
                <span class="text-2xl text-white font-black tracking-tighter">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
            </div>
        @endif
        <div class="absolute bottom-0 right-0 bg-green-500 rounded-full p-1.5 border-4 border-white dark:border-gray-800 shadow-sm">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
    </div>
    <h3 class="text-lg font-black text-gray-900 dark:text-gray-100 tracking-tight">{{ $user->name }}</h3>
</div>
