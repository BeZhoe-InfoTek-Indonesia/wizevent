<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased font-sans bg-gray-50 text-gray-900">
        <div class="min-h-screen">
            <!-- Navigation -->
            <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center gap-8 flex-1">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}">
                                    <x-application-logo class="block h-9 w-auto fill-current text-blue-600" />
                                </a>
                            </div>

                            <!-- Header Nav (Desktop) -->
                            <div class="hidden md:flex items-center gap-6">
                                <a href="#" class="text-sm font-medium text-gray-600 hover:text-blue-600">Flights</a>
                                <a href="#" class="text-sm font-medium text-gray-600 hover:text-blue-600">Hotels</a>
                                <a href="#" class="text-sm font-medium text-gray-600 hover:text-blue-600">Villas & Apt.</a>
                                <a href="#" class="text-sm font-medium text-gray-600 hover:text-blue-600 underline decoration-blue-500 decoration-2 underline-offset-8">To Dos</a>
                                <a href="#" class="text-sm font-medium text-gray-600 hover:text-blue-600">Trains</a>
                            </div>
                        </div>

                        <!-- Auth Buttons -->
                        <div class="flex items-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">Log in</a>
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Sign up</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
                
                <!-- Auth Banner (Optional) -->
                @guest
                <div class="relative bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between overflow-hidden">
                    <div class="flex items-center gap-6 relative z-10">
                        <div class="flex-shrink-0 bg-blue-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Have you got an account?</h3>
                            <p class="text-gray-500 text-sm mt-1">Create one or log into it to enjoy member-only discounts & benefits!</p>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-blue-50 text-blue-600 font-semibold rounded-lg hover:bg-blue-100 transition">
                            Log in
                        </a>
                    </div>
                    <div class="absolute right-0 top-0 h-full w-1/3 opacity-10 bg-gradient-to-l from-blue-500 to-transparent"></div>
                </div>
                @endguest

                <!-- Event Discovery (Livewire) -->
                <livewire:event.event-discovery />

            </main>

            <footer class="bg-white border-t border-gray-100 pt-16 pb-8 mt-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                        <!-- Column 1: Custom Logo & Contact -->
                        <div class="col-span-1 lg:col-span-1">
                            <div class="mb-6">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                                    <x-application-logo class="h-8 w-auto fill-current text-blue-600" />
                                    <span class="text-xl font-bold text-gray-900 tracking-tight">{{ config('app.name') }}</span>
                                </a>
                            </div>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <div class="bg-gray-100 p-2 rounded-full text-gray-600 mt-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">WhatsApp</div>
                                        <div class="text-gray-900 font-medium">+62 858 1150 0888</div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Column 2: Company & Products -->
                        <div class="col-span-1 lg:col-span-1 grid grid-cols-2 gap-8">
                            <div>
                                <h3 class="font-bold text-gray-900 mb-4">Company</h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li><a href="#" class="hover:text-blue-600">Blog</a></li>
                                    <li><a href="#" class="hover:text-blue-600">Newsroom</a></li>
                                    <li><a href="#" class="hover:text-blue-600">Careers</a></li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-4">Products</h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li><a href="#" class="hover:text-blue-600">Flights</a></li>
                                    <li><a href="#" class="hover:text-blue-600">Hotels</a></li>
                                    <li><a href="#" class="hover:text-blue-600">Events</a></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Column 3: Support -->
                        <div class="col-span-1 lg:col-span-1">
                            <h3 class="font-bold text-gray-900 mb-4">Support</h3>
                            <ul class="space-y-3 text-sm text-gray-600">
                                <li><a href="#" class="hover:text-blue-600">Help Center</a></li>
                                <li><a href="#" class="hover:text-blue-600">Privacy Policy</a></li>
                                <li><a href="#" class="hover:text-blue-600">Terms & Conditions</a></li>
                            </ul>
                        </div>

                        <!-- Column 4: App Download -->
                        <div class="col-span-1 lg:col-span-1">
                             <h3 class="font-bold text-gray-900 mb-4">Cheaper on the app</h3>
                            <div class="space-y-3">
                                <a href="#" class="block bg-black text-white px-4 py-2 rounded-lg flex items-center gap-3 w-48 hover:opacity-90 transition">
                                    <div class="text-left">
                                        <div class="text-[0.6rem] leading-none uppercase">Download on the</div>
                                        <div class="text-lg font-bold leading-none">App Store</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-200 mt-12 pt-8 text-center sm:text-left text-xs text-gray-500">
                        &copy; 2011-{{ date('Y') }} PT. Global Tiket Network. All Rights Reserved.
                    </div>
                </div>
            </footer>
        </div>
        @livewireScripts
    </body>
</html>
