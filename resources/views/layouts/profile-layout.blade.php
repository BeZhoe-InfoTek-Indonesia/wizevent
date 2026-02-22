@php $user = $user ?? auth()->user(); @endphp
<div class="min-h-screen overflow-x-hidden dark:bg-gray-900" x-data="{ activeTab: '{{ $initialProfileTab ?? '' }}' }" x-init="activeTab = activeTab || (new URLSearchParams(window.location.search).get('tab')) || localStorage.getItem('profileActiveTab') || 'dashboard'; $watch('activeTab', value => localStorage.setItem('profileActiveTab', value))">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="lg:w-64 flex-shrink-0">
                <!-- User Profile Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6 text-center border border-gray-100 dark:border-gray-700">
                    <div class="relative inline-block mb-4">
                        @if($user && $user->avatar)
                            <img class="h-24 w-24 rounded-full object-cover mx-auto border-4 border-white dark:border-gray-700 shadow-md" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                        @else
                            <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mx-auto border-4 border-white dark:border-gray-700 shadow-md">
                                <span class="text-3xl text-white font-bold">{{ $user ? strtoupper(substr($user->name, 0, 2)) : '' }}</span>
                            </div>
                        @endif
                        <div class="absolute bottom-1 right-1 bg-blue-500 rounded-full p-1.5 border-2 border-white dark:border-gray-800">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-gray-100 text-lg">{{ $user?->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('profile.verified_member') }}</p>
                    <button type="button" @click="activeTab = 'account'" class="inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-full text-sm font-medium hover:bg-red-100 transition-colors">
                        {{ __('profile.edit_profile') }}
                    </button>
                </div>

                <!-- Navigation Menu -->
                <nav role="tablist" aria-orientation="vertical" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
                    <a href="{{ route('profile', ['tab' => 'dashboard']) }}" role="tab" class="flex items-center gap-3 px-4 py-3" :class="activeTab === 'dashboard' ? 'bg-gray-50 dark:bg-gray-900' : ''">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>{{ __('profile.dashboard') }}</span>
                    </a>
                    <a href="{{ route('profile', ['tab' => 'account']) }}" role="tab" class="flex items-center gap-3 px-4 py-3 border-t border-gray-100 dark:border-gray-700" :class="activeTab === 'account' ? 'bg-gray-50 dark:bg-gray-900' : ''">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ __('profile.account') }}</span>
                    </a>
                    <a href="{{ route('profile', ['tab' => 'reviews']) }}" role="tab" class="flex items-center gap-3 px-4 py-3 border-t border-gray-100 dark:border-gray-700" :class="activeTab === 'reviews' ? 'bg-gray-50 dark:bg-gray-900' : ''">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <span>{{ __('profile.your_reviews') }}</span>
                    </a>
                    <a href="{{ route('profile', ['tab' => 'wishlist']) }}" role="tab" class="flex items-center gap-3 px-4 py-3 border-t border-gray-100 dark:border-gray-700" :class="activeTab === 'wishlist' ? 'bg-gray-50 dark:bg-gray-900' : ''">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span>{{ __('profile.wishlist') }}</span>
                    </a>
                    <a href="{{ route('profile', ['tab' => 'orders']) }}" role="tab" class="flex items-center gap-3 px-4 py-3 border-t border-gray-100 dark:border-gray-700" :class="activeTab === 'orders' ? 'bg-gray-50 dark:bg-gray-900' : ''">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>{{ __('profile.your_orders') }}</span>
                    </a>
                    <a href="{{ route('profile', ['tab' => 'help']) }}" role="tab" class="flex items-center gap-3 px-4 py-3 border-t border-gray-100 dark:border-gray-700" :class="activeTab === 'help' ? 'bg-gray-50 dark:bg-gray-900' : ''">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ __('profile.help_center') }}</span>
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ __('profile.new') }}</span>
                    </a>
                    <a href="{{ route('profile', ['tab' => 'settings']) }}" role="tab" class="flex items-center gap-3 px-4 py-3 border-t border-gray-100 dark:border-gray-700" :class="activeTab === 'settings' ? 'bg-gray-50 dark:bg-gray-900' : ''">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ __('profile.settings') }}</span>
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0">
                @yield('profile-content')
            </main>
        </div>
    </div>
    <x-footer />
</div>
