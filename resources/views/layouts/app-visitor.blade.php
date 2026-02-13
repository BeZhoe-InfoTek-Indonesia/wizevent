<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($pageTitle) ? $pageTitle . ' - ' : '' }}{{ config('app.name', 'Event Management') }}</title>

    @isset($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endisset

    @isset($metaImage)
        <meta property="og:image" content="{{ $metaImage }}">
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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        @livewire('layouts.navigation')

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
