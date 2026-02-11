<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    @vite(['resources/css/visitor.css', 'resources/js/visitor.js'])
    @livewireStyles
</head>
<body class="visitor-layout">
    <!-- Visitor Header -->
    <header class="visitor-header">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-8">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ config('app.name', 'Event Management') }}
                    </h1>
                    <nav class="hidden md:flex space-x-6">
                        <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-primary-600">
                            Home
                        </a>
                        <a href="{{ route('events.index') }}" class="text-gray-700 hover:text-primary-600">
                            Events
                        </a>
                        <a href="#" class="text-gray-700 hover:text-primary-600">
                            About
                        </a>
                        <a href="#" class="text-gray-700 hover:text-primary-600">
                            Contact
                        </a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
                            Register
                        </a>
                    @else
                        <span class="text-sm text-gray-600">
                            {{ Auth::user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-700 hover:text-primary-600">
                                Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="visitor-main">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Visitor Footer -->
    <footer class="visitor-footer">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center text-gray-600">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Event Management') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>