<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>
<div>
    <nav x-data="{ open: false, langMenuOpen: false, avatarMenuOpen: false, searchOpen: false }" class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Left: Logo -->
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-2 group">
                        <div class="transform group-hover:scale-110 transition-transform duration-300">
                            <img src="{{ asset('images/logo-difan.png') }}" alt="Logo" class="h-8 w-auto">
                        </div>
                    </a>
                </div>

                <!-- Center: Main navigation -->
                <nav class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('welcome') }}" class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-red-600 transition-colors">Home</a>
                    <a href="{{ route('events.index') }}" class="text-sm font-bold text-red-600">Events</a>
                    <a href="#" class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-red-600 transition-colors">Organizers</a>
                    <a href="#" class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-red-600 transition-colors">Blog</a>
                </nav>

                <!-- Right: Controls -->
                <div class="flex items-center gap-5">
                    <!-- Search Input & Icons -->
                    <div class="flex items-center gap-3">
                        <div x-show="searchOpen"
                             x-transition:enter="transition-all ease-out duration-300"
                             x-transition:enter-start="opacity-0 w-0"
                             x-transition:enter-end="opacity-100 w-48 lg:w-64"
                             x-transition:leave="transition-all ease-in duration-200"
                             x-transition:leave-start="opacity-100 w-48 lg:w-64"
                             x-transition:leave-end="opacity-0 w-0"
                             class="overflow-hidden"
                             style="display: none;">
                            <form action="{{ route('events.index') }}" method="GET" class="m-0">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="w-full px-4 py-2 text-sm rounded-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 dark:text-white transition-colors outline-none">
                            </form>
                        </div>
                        
                        <button @click="searchOpen = !searchOpen" class="text-gray-500 hover:text-red-600 dark:hover:text-red-500 transition-colors" title="Search">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                        
                        <!-- Filter Button -->
                        @if(request()->routeIs('events.index'))
                        <button class="text-gray-500 hover:text-red-600 dark:hover:text-red-500 transition-colors" title="Filter" @click="window.dispatchEvent(new CustomEvent('open-filter-modal'))">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </button>
                        @else
                        <a href="{{ route('events.index') }}" class="text-gray-500 hover:text-red-600 dark:hover:text-red-500 transition-colors" title="Filter">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </a>
                        @endif
                    </div>

                    <!-- Divider -->
                    <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
                    
                    <!-- Auth links -->
                    @guest
                        <div class="hidden sm:flex items-center gap-4">
                             <a href="{{ route('login') }}" class="text-sm font-bold text-gray-900 dark:text-white hover:text-red-600 transition-colors">Login</a>
                             <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-lg bg-[#EE2E24] hover:bg-[#d42820] text-white text-sm font-bold shadow-lg shadow-red-500/20 hover:shadow-red-500/40 hover:-translate-y-0.5 transition-all duration-300">Register</a>
                        </div>
                    @else
                        @php
                            $parts = explode(' ', trim(Auth::user()->name));
                            $initials = strtoupper((substr($parts[0] ?? '', 0, 1)) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                        @endphp
                        <div class="relative ml-2">
                            <button @click="avatarMenuOpen = !avatarMenuOpen" type="button" class="h-10 w-10 rounded-full bg-gradient-to-br from-red-500 to-red-600 text-white shadow-lg shadow-red-500/30 flex items-center justify-center text-sm font-bold ring-2 ring-white ring-offset-2 ring-offset-gray-100 transition-all hover:scale-110 active:scale-95">
                                {{ $initials }}
                            </button>
                            
                             <!-- Dropdown -->
                            <div x-show="avatarMenuOpen" @click.away="avatarMenuOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute right-0 mt-4 w-72 bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.1)] py-4 z-50 border border-white/50 ring-1 ring-black/5">
                                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ Auth::user()?->name }}</p>
                                    <p class="text-xs font-medium text-gray-500 mt-0.5 truncate">{{ Auth::user()?->email }}</p>
                                </div>
                                 <div class="p-2 space-y-1">
                                     <a href="{{ route('profile') }}" class="flex items-center px-4 py-3 text-sm font-bold text-gray-700 rounded-2xl hover:bg-gray-100 hover:text-red-500 transition-colors">
                                         <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                         Profile
                                     </a>
                                     <a href="{{ route('profile') }}?tab=orders" class="flex items-center px-4 py-3 text-sm font-bold text-gray-700 rounded-2xl hover:bg-gray-100 hover:text-red-500 transition-colors">
                                         <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                         Your Orders
                                     </a>
                                     <a href="{{ route('profile') }}?tab=reviews" class="flex items-center px-4 py-3 text-sm font-bold text-gray-700 rounded-2xl hover:bg-gray-100 hover:text-red-500 transition-colors">
                                         <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                         Your Reviews
                                     </a>
                                     <a href="{{ route('profile') }}?tab=wishlist" class="flex items-center px-4 py-3 text-sm font-bold text-gray-700 rounded-2xl hover:bg-gray-100 hover:text-red-500 transition-colors">
                                         <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                         Wishlist
                                     </a>
                                     <div class="h-px bg-gray-100 dark:bg-gray-700 my-2 mx-4"></div>
                                     <button wire:click="logout" class="w-full flex items-center px-4 py-3 text-sm font-bold text-red-600 rounded-2xl hover:bg-red-50 transition-colors">
                                         <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                         Logout
                                     </button>
                                 </div>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Navigation -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 pb-[env(safe-area-inset-bottom)] lg:hidden shadow-[0_-4px_10px_rgba(0,0,0,0.05)]">
        <div class="grid grid-cols-4 h-16">
            <!-- Home -->
            <a href="{{ route('welcome') }}" wire:navigate class="flex flex-col items-center justify-center space-y-1 group">
                <div class="{{ request()->routeIs('welcome') ? 'text-red-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                    @if(request()->routeIs('welcome'))
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
                            <path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.725A1.875 1.875 0 013.85 19.875V13.677c.031-.028.061-.056.091-.086L12 5.432z" />
                        </svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    @endif
                </div>
                <span class="text-[10px] font-bold {{ request()->routeIs('welcome') ? 'text-red-600' : 'text-gray-500' }}">Home</span>
            </a>

            <!-- Wishlist -->
            @php $isWishlist = request()->get('tab') === 'wishlist' || request()->routeIs('dashboard.loved-events'); @endphp
            <a href="{{ route('profile') }}?tab=wishlist" wire:navigate class="flex flex-col items-center justify-center space-y-1 group">
                <div class="{{ $isWishlist ? 'text-red-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <svg class="w-6 h-6" fill="{{ $isWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold {{ $isWishlist ? 'text-red-600' : 'text-gray-500' }}">Wishlist</span>
            </a>

            <!-- Your Orders -->
            @php $isOrders = request()->routeIs('dashboard') || request()->get('tab') === 'orders'; @endphp
            <a href="{{ route('profile') }}?tab=orders" wire:navigate class="flex flex-col items-center justify-center space-y-1 group">
                <div class="{{ $isOrders ? 'text-red-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <svg class="w-6 h-6" fill="{{ $isOrders ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2a2 2 0 00-2 2zM15 5h-2a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2zM9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span class="text-[10px] font-bold {{ $isOrders ? 'text-red-600' : 'text-gray-500' }}">Your Orders</span>
            </a>

            <!-- Account -->
            @php $isAccount = request()->routeIs('profile') && !request()->has('tab'); @endphp
            <a href="{{ Auth::check() ? route('profile') : route('login') }}" wire:navigate class="flex flex-col items-center justify-center space-y-1 group">
                <div class="{{ $isAccount ? 'text-red-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <svg class="w-6 h-6" fill="{{ $isAccount ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-bold {{ $isAccount ? 'text-red-600' : 'text-gray-500' }}">Account</span>
            </a>
        </div>
    </div>
</div>
