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
    <nav x-data="{ open: false, langMenuOpen: false, avatarMenuOpen: false }" class="fixed top-6 left-0 right-0 z-40 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border border-white/40 dark:border-gray-700/50 rounded-full shadow-[0_8px_32px_rgba(0,0,0,0.1)] px-6 py-3 flex justify-between items-center transition-all duration-300 hover:shadow-[0_12px_40px_rgba(0,0,0,0.15)] hover:scale-[1.005]">
                
                <!-- Left: Logo -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('welcome') }}" class="flex items-center space-x-3 group">
                        <div class="p-2 rounded-xl bg-gradient-to-br from-red-500 to-orange-500 shadow-md group-hover:shadow-lg transition-all duration-300 group-hover:scale-110">
                            <x-application-logo class="h-6 w-6 fill-current text-white" />
                        </div>
                        <span class="text-xl font-black tracking-tight text-gray-900 dark:text-gray-100 hidden sm:inline group-hover:text-red-600 transition-colors">{{ config('app.name', 'Event Management') }}</span>
                    </a>
                </div>

                <!-- Center: Main navigation -->
                <nav class="hidden lg:flex items-center gap-1 p-1 bg-gray-100/50 dark:bg-gray-800/50 rounded-full border border-gray-200/50 dark:border-gray-700/50">
                    <a href="{{ route('welcome') }}" class="px-5 py-2 rounded-full text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-white dark:hover:bg-gray-700 hover:shadow-sm transition-all duration-200">Home</a>
                    <a href="{{ route('events.index') }}" class="px-5 py-2 rounded-full text-sm font-bold text-white bg-gray-900 shadow-md transform scale-105">Events</a>
                </nav>

                <!-- Right: Controls -->
                <div class="flex items-center gap-3">
                    <!-- Theme toggle button -->
                    <button 
                        @click="
                            if (localStorage.theme === 'dark') {
                                localStorage.theme = 'light';
                                document.documentElement.classList.remove('dark');
                            } else {
                                localStorage.theme = 'dark';
                                document.documentElement.classList.add('dark');
                            }
                        " 
                        type="button" 
                        class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 shadow-[2px_2px_5px_rgba(0,0,0,0.05),-2px_-2px_5px_rgba(255,255,255,1)] dark:shadow-none dark:border dark:border-gray-700 flex items-center justify-center hover:scale-110 active:scale-95 transition-all"
                    >
                        <svg class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>
                    
                    <!-- Auth links -->
                    @guest
                        <div class="hidden sm:flex items-center gap-2 pl-4 border-l border-gray-200 dark:border-gray-700">
                             <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full text-sm font-bold text-gray-700 hover:text-gray-900 transition-colors">Login</a>
                             <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-full bg-gray-900 text-white text-sm font-bold shadow-lg hover:bg-black hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">Register</a>
                        </div>
                    @else
                        @php
                            $parts = explode(' ', trim(Auth::user()->name));
                            $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                        @endphp
                        <div class="relative ml-2">
                            <button @click="avatarMenuOpen = !avatarMenuOpen" type="button" class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-800 to-black text-white shadow-lg flex items-center justify-center text-sm font-bold ring-2 ring-white ring-offset-2 ring-offset-gray-100 transition-all hover:scale-110 active:scale-95">
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
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-bold text-gray-700 rounded-2xl hover:bg-gray-100 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        Orders
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
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 pb-[env(safe-area-inset-bottom)] lg:hidden">
        <div class="flex justify-around items-center h-16 relative">
            <!-- Home -->
            <a href="{{ route('welcome') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group">
                 <div class="relative p-2 rounded-full transition-all duration-300 {{ request()->routeIs('welcome') ? 'bg-red-50 dark:bg-white/5' : '' }}">
                     <svg class="w-6 h-6 transition-colors duration-300 {{ request()->routeIs('welcome') ? 'text-red-600 fill-red-600' : 'text-gray-400 dark:text-gray-600 group-hover:text-gray-600 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                     @if(request()->routeIs('welcome'))
                     <div class="absolute inset-0 bg-red-400/20 rounded-full blur-md"></div>
                     @endif
                 </div>
            </a>

            <!-- Search -->
            <a href="{{ route('events.index') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group">
                 <svg class="w-6 h-6 transition-colors duration-300 {{ request()->routeIs('events.index') ? 'text-red-600' : 'text-gray-400 dark:text-gray-600 group-hover:text-gray-600 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </a>

            <!-- Center Ticket (Floating) -->
            <div class="relative w-full flex justify-center h-full pointer-events-none">
                 <a href="{{ route('events.index') }}" wire:navigate class="absolute -top-6 pointer-events-auto group">
                     <div class="w-14 h-14 rounded-full bg-red-600 shadow-[0_8px_25px_rgba(220,38,38,0.5)] flex items-center justify-center text-white border-[5px] border-gray-50 dark:border-gray-900 transform transition-transform duration-300 group-active:scale-95 group-hover:scale-105 group-hover:shadow-[0_12px_30px_rgba(220,38,38,0.6)]">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2a2 2 0 00-2 2zM15 5h-2a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2zM15 9h-2v2h2V9zm0 4h-2v2h2v-2zm-6-8v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2zM7 5v2h2V5H7zm0 4v2h2V9H7zm0 4v2h2v-2H7z"></path></svg>
                     </div>
                 </a>
            </div>

            <!-- Favorites (Orders/Dashboard) -->
            <a href="{{ route('dashboard') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group">
                 <svg class="w-6 h-6 transition-colors duration-300 {{ request()->routeIs('dashboard') ? 'text-red-600 fill-red-600' : 'text-gray-400 dark:text-gray-600 group-hover:text-gray-600 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </a>

            <!-- Profile -->
            @guest
                <a href="{{ route('login') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group">
                     <svg class="w-6 h-6 transition-colors duration-300 {{ request()->routeIs('login') ? 'text-red-600' : 'text-gray-400 dark:text-gray-600 group-hover:text-gray-600 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </a>
            @else
                <a href="{{ route('profile') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full space-y-1 group">
                     <svg class="w-6 h-6 transition-colors duration-300 {{ request()->routeIs('profile') ? 'text-red-600 fill-red-600' : 'text-gray-400 dark:text-gray-600 group-hover:text-gray-600 dark:group-hover:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </a>
            @endguest
        </div>
    </div>
</div>
