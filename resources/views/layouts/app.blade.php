<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- PWA Meta Tags --}}
        <meta name="theme-color" content="#4F46E5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="EventMgmt">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="icon" type="image/svg+xml" href="{{ asset('icons/icon-192.svg') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        <!-- Theme initialization (prevents flash) -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
        @stack('styles')

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 pt-28 pb-20 lg:pb-0">
            <x-toaster-hub />
            <livewire:layouts.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>

        @stack('scripts')
        @livewireScripts

        {{-- Service Worker Registration & Offline Detection --}}
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('{{ asset('service-worker.js') }}')
                        .then((registration) => {
                            console.log('Service Worker registered:', registration.scope);
                        })
                        .catch((error) => {
                            console.log('Service Worker registration failed:', error);
                        });
                });
            }

            window.addEventListener('online', () => {
                if (window.alpineOfflineStore) {
                    window.alpineOfflineStore.isOnline = true;
                }
            });

            window.addEventListener('offline', () => {
                if (window.alpineOfflineStore) {
                    window.alpineOfflineStore.isOnline = false;
                }
            });
        </script>

        {{-- Offline Notification Banner --}}
        <div
            x-data="{
                isOnline: navigator.onLine,
                showBanner: !navigator.onLine
            }"
            x-init="
                window.alpineOfflineStore = { isOnline: $data.isOnline };
                $watch('isOnline', value => {
                    window.alpineOfflineStore.isOnline = value;
                    if (value) {
                        setTimeout(() => showBanner = false, 3000);
                    } else {
                        showBanner = true;
                    }
                });
            "
            x-show="showBanner"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-full"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-full"
            class="fixed top-0 left-0 right-0 z-50"
            style="display: none;"
        >
            <div class="bg-amber-500 text-white px-4 py-3 shadow-lg">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="font-medium" x-text="isOnline ? 'You are back online!' : 'You are offline. Some features may not be available.'"></span>
                    </div>
                    <button
                        x-show="isOnline"
                        @click="showBanner = false"
                        class="text-white hover:text-gray-100 focus:outline-none"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

    </body>
</html>
