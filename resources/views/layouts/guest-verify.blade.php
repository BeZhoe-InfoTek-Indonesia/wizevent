<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- PWA Meta Tags --}}
        <meta name="theme-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="EventMgmt">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="icon" type="image/svg+xml" href="{{ asset('icons/icon-192.svg') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-['Figtree'] text-slate-900 antialiased">
        <div class="min-h-screen bg-slate-100">
            <div class="relative min-h-screen overflow-hidden">
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -top-32 -right-24 h-72 w-72 rounded-full bg-rose-200/60 blur-3xl"></div>
                    <div class="absolute bottom-0 -left-24 h-72 w-72 rounded-full bg-red-200/60 blur-3xl"></div>
                    <div class="absolute left-1/2 top-24 h-48 w-48 -translate-x-1/2 rounded-full bg-white/70 blur-2xl"></div>
                </div>

                <div class="relative z-10 px-4 py-10 sm:px-8">
                    <div class="mx-auto w-full max-w-5xl">
                        <div class="mb-8 flex items-center justify-center">
                            <div class="flex items-center gap-4 rounded-3xl bg-white/80 px-6 py-4 shadow-lg shadow-slate-200/60 ring-1 ring-slate-200/70 backdrop-blur">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-600 text-white shadow-lg shadow-red-200/80">
                                    <x-application-logo class="h-7 w-7 fill-current" />
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-slate-900">{{ config('app.name', 'EventMgmt') }}</p>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('Ticketing Platform') }}</p>
                                </div>
                            </div>
                        </div>

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Service Worker Registration --}}
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
        </script>
    </body>
</html>
