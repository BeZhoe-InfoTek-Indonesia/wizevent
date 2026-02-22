@props(['user'])

<div>
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('profile.account_information') }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        {{-- User ID Card --}}
        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-4 flex flex-col justify-between group hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors border border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                </div>
                <span class="font-medium text-gray-500 dark:text-gray-400">{{ __('profile.user_id') }}</span>
            </div>
            <p class="text-xl font-bold text-gray-900 dark:text-gray-100 pl-1">#{{ $user->id }}</p>
        </div>

        {{-- Created At Card --}}
        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-4 flex flex-col justify-between group hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors border border-gray-100 dark:border-gray-700/50">
             <div class="flex items-center gap-3 mb-2">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="font-medium text-gray-500 dark:text-gray-400">{{ __('profile.member_since') }}</span>
            </div>
            <p class="text-lg font-bold text-gray-900 dark:text-gray-100 pl-1">{{ $user->created_at->format('M j, Y') }}</p>
        </div>

        {{-- Email Verified Card --}}
        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-4 flex flex-col justify-between group hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors border border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center gap-3 mb-2">
                 <div class="p-2 {{ $user->email_verified_at ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' }} rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                 </div>
                <span class="font-medium text-gray-500 dark:text-gray-400">{{ __('profile.status') }}</span>
            </div>
            <div>
                 <p class="text-lg font-bold text-gray-900 dark:text-gray-100 pl-1">
                    {{ $user->email_verified_at ? __('profile.verified') : __('profile.unverified') }}
                 </p>
                 @if($user->email_verified_at)
                    <p class="text-xs text-gray-500 dark:text-gray-400 pl-1">{{ $user->email_verified_at->format('M j, Y') }}</p>
                 @endif
            </div>
        </div>

        {{-- Roles Card (Wide) --}}
        <div class="col-span-1 md:col-span-3 bg-gray-50 dark:bg-gray-700/30 rounded-2xl p-4 group hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors border border-gray-100 dark:border-gray-700/50">
            <div class="flex items-center gap-3 mb-3">
                 <div class="p-2 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                 </div>
                <span class="font-medium text-gray-500 dark:text-gray-400">Assigned Roles</span>
            </div>
            <div class="flex flex-wrap gap-2 pl-1">
                @forelse($user->getRoleNames() as $role)
                    <span class="px-3 py-1 bg-white dark:bg-gray-600 border border-gray-200 dark:border-gray-500 rounded-full text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm">
                        {{ $role }}
                    </span>
                @empty
                     <span class="text-gray-500 dark:text-gray-400 italic">{{ __('profile.no_roles_assigned') }}</span>
                @endforelse
            </div>
        </div>
    </div>
</div>
