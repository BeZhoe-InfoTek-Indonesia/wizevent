@php $user = $user ?? auth()->user(); @endphp
<style>
    html.profile-text-small {
        font-size: 14px;
    }

    @media (min-width: 768px) {
        html.profile-text-small {
            font-size: 15px;
        }
    }

    @media (min-width: 1280px) {
        html.profile-text-small {
            font-size: 15.5px;
        }
    }
</style>
<div class="min-h-screen overflow-x-hidden dark:bg-gray-900" x-data="{ activeTab: '{{ $initialProfileTab ?? '' }}', showMobileTab: false }" x-init="activeTab = activeTab || (new URLSearchParams(window.location.search).get('tab')) || localStorage.getItem('profileActiveTab') || 'dashboard'; $watch('activeTab', value => localStorage.setItem('profileActiveTab', value))">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="lg:w-72 flex-shrink-0 space-y-6 hidden lg:block">
                <!-- User Profile Card -->
                <div class="glass-panel rounded-3xl shadow-float p-6 text-center bg-white/80 dark:bg-gray-800/80 relative overflow-hidden group">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-red-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                    
                    <div class="relative inline-block mb-6">
                        @if($user && $user->avatar)
                            <div class="p-1.5 rounded-full bg-gradient-to-tr from-red-500 via-orange-400 to-red-600 shadow-xl group-hover:rotate-6 transition-transform duration-500">
                                <img class="h-28 w-28 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-inner" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                            </div>
                        @else
                            <div class="h-28 w-28 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center mx-auto border-4 border-white dark:border-gray-800 shadow-skeuo group-hover:rotate-6 transition-all duration-500">
                                <span class="text-4xl text-white font-black tracking-tighter">{{ $user ? strtoupper(substr($user->name, 0, 1)) : '' }}</span>
                            </div>
                        @endif
                        <div class="absolute bottom-1.5 right-1.5 bg-green-500 rounded-full p-2 border-4 border-white dark:border-gray-800 shadow-lg">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="font-black text-gray-900 dark:text-gray-100 text-2xl tracking-tighter">{{ $user?->name }}</h3>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-50 dark:bg-gray-900/50 rounded-full border border-gray-100 dark:border-gray-700 mt-2 mb-6 shadow-sm">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                        <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-[0.2em]">{{ __('profile.verified_member') }}</span>
                    </div>
                    
                    <a href="{{ route('profile', ['tab' => 'account']) }}" class="block bg-red-600 w-full py-4 rounded-2xl text-xs font-black uppercase tracking-widest text-white active:scale-95 transition-all shadow-lg hover:bg-red-700 text-center">
                        {{ __('profile.edit_profile') }}
                    </a>
                </div>

                <!-- Navigation Menu -->
                <nav role="tablist" aria-orientation="vertical" class="glass-panel p-2.5 rounded-[2rem] shadow-3xl bg-white/80 dark:bg-gray-800/80 transition-all border-4 border-white dark:border-gray-800 transform-gpu scale-105 hover:scale-110 duration-300">
                    <div class="space-y-1.5">
                        <x-profile.nav-item label="{{ __('profile.dashboard') }}" tab="dashboard" icon="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        <x-profile.nav-item label="{{ __('profile.account') }}" tab="account" icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        <x-profile.nav-item label="{{ __('profile.your_reviews') }}" tab="reviews" icon="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        <x-profile.nav-item label="{{ __('profile.wishlist') }}" tab="wishlist" icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        <x-profile.nav-item label="{{ __('profile.your_orders') }}" tab="orders" icon="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        <x-profile.nav-item label="{{ __('profile.help_center') }}" tab="help" icon="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" isNew="true" />
                        <x-profile.nav-item label="{{ __('profile.settings') }}" tab="settings" icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </div>
                </nav>
            </aside>

            <!-- Mobile Navigation -->
            <div class="lg:hidden fixed inset-0 z-40 pointer-events-none" x-show="!showMobileTab">
                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-1.5 rounded-full bg-gradient-to-tr from-red-500 via-orange-400 to-red-600">
                            @if($user && $user->avatar)
                                <img class="h-12 w-12 rounded-full object-cover border-2 border-white dark:border-gray-800" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                            @else
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center border-2 border-white dark:border-gray-800">
                                    <span class="text-lg text-white font-black">{{ $user ? strtoupper(substr($user->name, 0, 1)) : '' }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-gray-100">{{ $user?->name }}</h3>
                            <p class="text-xs text-gray-500">{{ __('profile.verified_member') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Tab Menu -->
            <div class="lg:hidden fixed inset-0 z-30 overflow-y-auto bg-white dark:bg-gray-800 pointer-events-none" x-show="!showMobileTab">
                <div class="pt-24 px-4 pb-8 pointer-events-auto">
                    <nav class="space-y-2">
                        <button @click="activeTab = 'dashboard'; showMobileTab = true" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20" :class="activeTab === 'dashboard' && 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-200 font-bold'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            {{ __('profile.dashboard') }}
                        </button>
                        <button @click="activeTab = 'account'; showMobileTab = true" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20" :class="activeTab === 'account' && 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-200 font-bold'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('profile.account') }}
                        </button>
                        <button @click="activeTab = 'reviews'; showMobileTab = true" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20" :class="activeTab === 'reviews' && 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-200 font-bold'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            {{ __('profile.your_reviews') }}
                        </button>
                        <button @click="activeTab = 'wishlist'; showMobileTab = true" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20" :class="activeTab === 'wishlist' && 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-200 font-bold'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            {{ __('profile.wishlist') }}
                        </button>
                        <button @click="activeTab = 'orders'; showMobileTab = true" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20" :class="activeTab === 'orders' && 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-200 font-bold'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            {{ __('profile.your_orders') }}
                        </button>
                        <button @click="activeTab = 'help'; showMobileTab = true" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 relative" :class="activeTab === 'help' && 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-200 font-bold'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('profile.help_center') }}
                            <span class="ml-auto inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-500 text-white">NEW</span>
                        </button>
                        <button @click="activeTab = 'settings'; showMobileTab = true" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20" :class="activeTab === 'settings' && 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-200 font-bold'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            </svg>
                            {{ __('profile.settings') }}
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 min-w-0 lg:block" :class="{ 'hidden': !showMobileTab }" x-show.transition="showMobileTab || window.innerWidth >= 1024">
                @yield('profile-content')
            </main>
        </div>
    </div>
    <x-footer />
</div>

<script>
const applyProfileFontScale = () => {
    document.documentElement.classList.add('profile-text-small');
};

const removeProfileFontScale = () => {
    document.documentElement.classList.remove('profile-text-small');
};

applyProfileFontScale();
window.addEventListener('beforeunload', removeProfileFontScale);
window.addEventListener('pagehide', removeProfileFontScale);

document.addEventListener('alpine:init', () => {
    // Handle window resize to reset mobile tab view on desktop
    window.addEventListener('resize', function() {
        const profileDiv = document.querySelector('[x-data*="showMobileTab"]');
        if (profileDiv && window.innerWidth >= 1024) {
            Alpine.store('profile') = Alpine.store('profile') || {};
            Alpine.store('profile').showMobileTab = false;
        }
    });
});
</script>
