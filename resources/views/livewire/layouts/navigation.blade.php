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

<nav x-data="{ open: false, langMenuOpen: false, avatarMenuOpen: false }" class="sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left: Logo -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('welcome') }}" class="flex items-center space-x-3">
                    <x-application-logo class="h-8 w-8 rounded-md fill-current text-gray-800 dark:text-gray-200" />
                    <span class="text-xl font-semibold text-gray-900 dark:text-gray-100 hidden sm:inline">{{ config('app.name', 'Event Management') }}</span>
                </a>
            </div>

            <!-- Center: Main navigation -->
            <nav class="hidden lg:flex items-center gap-6">
                <a href="{{ route('welcome') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 transition">Home</a>
                <a href="{{ route('events.index') }}" class="text-blue-600 dark:text-blue-400 font-semibold">Events</a>
            </nav>

            <!-- Right: Controls (theme, device, language, auth) -->
            <div class="flex items-center space-x-4">
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
                    class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hidden sm:inline-flex transition-colors"
                    aria-label="Toggle theme"
                >
                    <!-- Sun icon (shown in dark mode) -->
                    <svg class="h-5 w-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <!-- Moon icon (shown in light mode) -->
                    <svg class="h-5 w-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
                
                <!-- Device icon (kept minimal) -->
                <button aria-hidden="true" class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hidden sm:inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 3a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm0 2h12v8H4V5z" />
                    </svg>
                </button>

                <!-- Mail icon (square) -->
                <button class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hidden sm:inline-flex" aria-label="inbox">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </button>

                <!-- Language selector -->
                <div class="relative hidden sm:inline-flex">
                    <button @click="langMenuOpen = !langMenuOpen" type="button" class="flex items-center space-x-1 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-2 py-1 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2a10 10 0 100 20 10 10 0 000-20zM4.93 4.93A8 8 0 1119.07 19.07 8 8 0 014.93 4.93z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ strtoupper(app()->getLocale()) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 dark:text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.584l3.71-4.354a.75.75 0 111.14.976l-4.25 5a.75.75 0 01-1.14 0l-4.25-5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div x-show="langMenuOpen" @click.away="langMenuOpen = false" class="absolute right-0 mt-10 w-36 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('lang.switch', 'en') }}" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">English</a>
                        <a href="{{ route('lang.switch', 'id') }}" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Bahasa</a>
                    </div>
                </div>

                <!-- Auth links -->
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 hidden sm:inline">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Register</a>
                @else
                    @php
                        $parts = explode(' ', trim(Auth::user()->name));
                        $initials = strtoupper(substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                    @endphp
                    <div class="relative">
                        <button @click="avatarMenuOpen = !avatarMenuOpen" type="button" class="h-9 w-9 rounded-full bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center text-sm font-semibold text-white ring-2 ring-transparent hover:ring-blue-300 dark:hover:ring-blue-500 focus:outline-none focus:ring-2">
                            {{ $initials }}
                        </button>
                        <div x-show="avatarMenuOpen" @click.away="avatarMenuOpen = false" class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl py-2 z-50 border border-gray-100 dark:border-gray-700">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                        @if(Auth::user() && Auth::user()->avatar)
                                            <img src="{{ Storage::url(Auth::user()->avatar) }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-600 dark:text-gray-300 font-bold text-lg">{{ $initials }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ Auth::user()?->name ?? 'User' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()?->email ?? '' }}</div>
                                    </div>
                                </div>
                            </div>
                             <div class="py-2">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Profile
                                </a>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Orders
                                </a>
                                <a href="{{ route('dashboard.loved-events') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    Loved Events
                                </a>
                                <div class="border-t border-gray-100 dark:border-gray-700 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest

                <!-- Hamburger -->
                <div class="flex items-center lg:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1 border-t border-gray-100 dark:border-gray-700">
            <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Flights</a>
            <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Hotels</a>
            <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Villas &amp; Apt.</a>
            <a href="{{ route('events.index') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-blue-600 dark:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">To Dos</a>
            <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Trains</a>
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()?->name ?? 'User' }}</div>
                    <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()?->email ?? '' }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Profile</a>
                    <a href="#" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Orders</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left pl-3 pr-4 py-2 text-base font-medium text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700 space-y-1">
                <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Login</a>
                <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 text-base font-medium text-blue-600 dark:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700">Register</a>
            </div>
        @endguest
    </div>
</nav>
