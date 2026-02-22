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


    <title>{{ isset($pageTitle) ? $pageTitle . ' - ' : '' }}{{ config('app.name', 'Event Management') }}</title>

    @isset($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endisset

    @isset($metaKeywords)
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endisset

    @isset($metaImage)
        <meta property="og:image" content="{{ $metaImage }}">
    @endisset

    @isset($metaDescription)
        <meta property="og:description" content="{{ $metaDescription }}">
    @endisset

    @isset($metaType)
        <meta property="og:type" content="{{ $metaType }}">
    @else
        <meta property="og:type" content="website">
    @endisset

    <meta property="og:title" content="{{ isset($pageTitle) ? $pageTitle : config('app.name', 'Event Management') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name', 'Event Management') }}">

    @isset($canonicalUrl)
        <link rel="canonical" href="{{ $canonicalUrl }}">
    @endisset

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
 
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')

</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen pt-28 pb-24 lg:pb-0">
        @livewire('layouts.navigation')

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts

    @stack('scripts')

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
