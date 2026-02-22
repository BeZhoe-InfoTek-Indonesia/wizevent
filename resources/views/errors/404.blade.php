@component('layouts.error')
    <main class="min-h-screen flex items-center justify-center bg-white dark:bg-gray-900">
        <div class="max-w-4xl w-full p-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow px-8 py-12 flex flex-col lg:flex-row items-center gap-8">
                <div class="flex-1 text-center lg:text-left">
                    <div class="text-red-600 font-semibold text-sm uppercase">Error 404</div>
                    <h1 class="mt-4 text-6xl font-extrabold text-gray-900 dark:text-white">404</h1>
                    <p class="mt-4 text-2xl font-semibold text-gray-700 dark:text-gray-300">Oops! This page is off the guest list.</p>
                    <p class="mt-3 text-gray-500 dark:text-gray-400">It looks like the event you're looking for has ended, moved, or never existed. Don't worry — the show must go on.</p>

                    <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                        <a href="{{ url('/') }}" class="inline-block px-4 py-2 bg-red-600 text-white rounded-md shadow">Go Back Home</a>
                        <a href="{{ url('/events') }}" class="inline-block px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-md">Search Events</a>
                    </div>

                    <div class="mt-6 text-sm text-gray-400">Or check out these popular categories: Concerts · Sports · Theater · Festivals</div>
                </div>

                <div class="w-64 flex-shrink-0">
                    <div class="h-48 w-48 rounded-lg bg-gradient-to-br from-red-50 to-red-100 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                        <svg class="w-28 h-28 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 9.75l4.5 4.5M14.25 9.75l-4.5 4.5" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endcomponent
